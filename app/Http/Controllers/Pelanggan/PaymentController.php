<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    // GET /checkout/payment — Step 2 (Halaman Upload Pembayaran)
    public function index()
    {
        $addressId    = session('checkout_address_id');
        $shipping     = session('checkout_shipping');
        $shippingCost = session('checkout_shipping_cost');
        
        $selectedIds  = session('checkout_ids', []);
        $buyNowItem   = session('buy_now_item'); // Dukungan fitur beli sekarang

        if (!$addressId || !$shipping || (!$buyNowItem && empty($selectedIds))) {
            return redirect()->route('pelanggan.checkout.index')
                ->with('error', 'Sesi checkout tidak valid. Ulangi dari awal.');
        }

        // Cek apakah item berasal dari "Beli Sekarang" atau "Keranjang"
        if ($buyNowItem) {
            $product = Product::findOrFail($buyNowItem['product_id']);
            $cartItems = collect([(object)[
                'id'    => 'buy_now',
                'name'  => $product->name,
                'sku'   => $product->sku ?? null,
                'image' => $product->main_image ? asset('storage/' . $product->main_image) : null,
                'size'  => $buyNowItem['size'],
                'qty'   => $buyNowItem['qty'],
                'price' => $buyNowItem['price'],
            ]]);
        } else {
            $cartItems = CartItem::whereIn('id', $selectedIds)
                ->where('user_id', auth()->id())
                ->with('product')
                ->get()
                ->map(fn($item) => (object)[
                    'id'    => $item->id,
                    'name'  => $item->product->name,
                    'sku'   => $item->product->sku ?? null,
                    'image' => $item->product->main_image ? asset('storage/' . $item->product->main_image) : null,
                    'size'  => $item->size,
                    'qty'   => $item->quantity,
                    'price' => $item->product->price,
                ]);
        }

        $address = Address::findOrFail($addressId);

        // PPN DIHAPUS. Logic murni Subtotal + Ongkir - Voucher
        $subtotal        = collect($cartItems)->sum(fn($i) => $i->price * $i->qty);
        $voucherDiscount = session('tanken_voucher_discount', 0);
        $ppn             = 0; 
        
        $total           = $subtotal + $shippingCost - $voucherDiscount;
        if($total < 0) $total = 0;

        $banks = [
            ['value' => 'bca',     'label' => 'BCA',     'number' => '1234567890', 'name' => 'TANKEN ID'],
            ['value' => 'mandiri', 'label' => 'Mandiri', 'number' => '0987654321', 'name' => 'TANKEN ID'],
            ['value' => 'bri',     'label' => 'BRI',     'number' => '1122334455', 'name' => 'TANKEN ID'],
        ];

        return view('pelanggan.payment', compact(
            'cartItems', 'address', 'shipping', 'shippingCost', 'subtotal', 'ppn', 'total', 'banks', 'voucherDiscount'
        ));
    }

    // POST /checkout/payment/simpan — Simpan bukti bayar ke session, pindah ke Review
    public function simpan(Request $request)
    {
        $request->validate([
            'payment_method'   => 'required|in:bank_transfer,qris',
            'payment_proof'    => 'required|file|mimes:jpg,jpeg,png|max:3072',
            'bank_provider'    => 'required_if:payment_method,bank_transfer|nullable|string',
        ]);

        $proofPath = $request->file('payment_proof')->store('payment-proofs', 'public');

        session([
            'checkout_payment_method'    => $request->payment_method,
            'checkout_payment_reference' => $request->bank_provider ?? 'qris',
            'checkout_payment_proof'     => $proofPath,
        ]);

        // Redirect ke Tinjauan / Review, bukan langsung Success!
        return redirect()->route('pelanggan.checkout.review');
    }

    // GET /checkout/review — Step 3 (Halaman Tinjauan Pesanan)
    public function review()
    {
        $addressId       = session('checkout_address_id');
        $shippingCost    = session('checkout_shipping_cost', 0);
        $voucherDiscount = session('tanken_voucher_discount', 0);
        
        $selectedIds = session('checkout_ids', []);
        $buyNowItem  = session('buy_now_item');

        if (!$addressId || (!session()->has('checkout_payment_method'))) {
            return redirect()->route('pelanggan.checkout.index');
        }

        if ($buyNowItem) {
            $product = Product::findOrFail($buyNowItem['product_id']);
            $cartItems = collect([[
                'id'    => 'buy_now',
                'name'  => $product->name,
                'image' => $product->main_image ? asset('storage/' . $product->main_image) : null,
                'size'  => $buyNowItem['size'],
                'qty'   => $buyNowItem['qty'],
                'price' => $buyNowItem['price'],
            ]]);
        } else {
            $cartItems = CartItem::whereIn('id', $selectedIds)
                ->where('user_id', auth()->id())
                ->with('product')
                ->get()
                ->map(fn($item) => [
                    'id'    => $item->id,
                    'name'  => $item->product->name,
                    'image' => $item->product->main_image ? asset('storage/' . $item->product->main_image) : null,
                    'size'  => $item->size,
                    'qty'   => $item->quantity,
                    'price' => $item->product->price,
                ]);
        }

        $address = Address::findOrFail($addressId);
        $shippingAddress = [
            'name'     => $address->name,
            'address'  => $address->street,
            'city_zip' => $address->region . ' ' . $address->postal,
            'phone'    => $address->phone,
        ];

        $subtotal = collect($cartItems)->sum(fn($i) => $i['price'] * $i['qty']);
        $total    = $subtotal + $shippingCost - $voucherDiscount;
        if($total < 0) $total = 0;

        return view('pelanggan.review-payment', compact(
            'cartItems', 'shippingAddress', 'shippingCost', 'voucherDiscount', 'subtotal', 'total'
        ));
    }

    // POST /checkout/place-order — Pembuatan Data Pesanan ke Database (Final)
    public function placeOrder(Request $request)
    {
        $addressId       = session('checkout_address_id');
        $shipping        = session('checkout_shipping');
        $shippingCost    = (int) session('checkout_shipping_cost');
        $shippingDays    = session('checkout_shipping_days', '2-3 hari');
        $voucherDiscount = (int) session('tanken_voucher_discount', 0);
        
        $paymentMethod   = session('checkout_payment_method');
        $paymentRef      = session('checkout_payment_reference');
        $paymentProof    = session('checkout_payment_proof');

        $selectedIds     = session('checkout_ids', []);
        $buyNowItem      = session('buy_now_item');

        if (!$addressId || !$paymentMethod || (!$buyNowItem && empty($selectedIds))) {
            return redirect()->route('pelanggan.checkout.index')
                ->with('error', 'Sesi checkout kadaluarsa. Ulangi dari awal.');
        }

        DB::beginTransaction();
        try {
            preg_match('/(\d+)(?!.*\d)/', $shippingDays, $matches);
            $daysMax = isset($matches[1]) ? (int)$matches[1] : 3;
            $address = Address::findOrFail($addressId);

            $subtotal = 0;
            if ($buyNowItem) {
                $subtotal = $buyNowItem['price'] * $buyNowItem['qty'];
            } else {
                $cartItems = CartItem::whereIn('id', $selectedIds)->with('product')->get();
                $subtotal = $cartItems->sum(fn($i) => $i->product->price * $i->quantity);
            }

            $total = $subtotal + $shippingCost - $voucherDiscount;
            if ($total < 0) $total = 0;

            // BUAT ORDER UTAMA
            $order = Order::create([
                'user_id'              => auth()->id(),
                'order_number'         => Order::generateNumber(),
                'customer_name'        => $address->name,
                'customer_email'       => auth()->user()->email,
                'customer_phone'       => $address->phone ?? auth()->user()->phone,
                'shipping_address'     => $address->street,
                'shipping_city'        => $address->region,  
                'shipping_province'    => '',                 
                'shipping_postal_code' => $address->postal,
                'status'               => 'pending',
                'payment_status'       => 'waiting_confirmation',
                'payment_method'       => $paymentMethod,
                'payment_reference'    => $paymentRef,
                'payment_proof'        => $paymentProof,
                'paid_at'              => now(),
                'courier'              => $shipping,
                'shipping_cost'        => $shippingCost,
                'subtotal'             => $subtotal,
                'ppn'                  => 0, // Dihapus
                'discount'             => $voucherDiscount,
                'total'                => $total,
                'estimated_arrival'    => now()->addDays($daysMax)->toDateString(),
            ]);

            // BUAT ITEM ORDER
            if ($buyNowItem) {
                $product = Product::findOrFail($buyNowItem['product_id']);
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'product_sku'  => $product->sku ?? null,
                    'size'         => $buyNowItem['size'],
                    'quantity'     => $buyNowItem['qty'],
                    'price'        => $buyNowItem['price'],
                    'subtotal'     => $buyNowItem['price'] * $buyNowItem['qty'],
                ]);
            } else {
                foreach ($cartItems as $item) {
                    OrderItem::create([
                        'order_id'     => $order->id,
                        'product_id'   => $item->product_id,
                        'product_name' => $item->product->name,
                        'product_sku'  => $item->product->sku ?? null,
                        'size'         => $item->size,
                        'quantity'     => $item->quantity,
                        'price'        => $item->product->price,
                        'subtotal'     => $item->product->price * $item->quantity,
                    ]);
                }
                // Hapus barang dari keranjang jika belinya via keranjang
                CartItem::whereIn('id', $selectedIds)->delete();
            }

            // Bersihkan semua riwayat pesanan di session browser
            session()->forget([
                'checkout_ids',
                'buy_now_item',
                'checkout_address_id',
                'checkout_shipping',
                'checkout_shipping_cost',
                'checkout_shipping_days',
                'checkout_payment_method',
                'checkout_payment_reference',
                'checkout_payment_proof',
                'tanken_voucher_discount'
            ]);

            DB::commit();

            return redirect()->route('pelanggan.checkout.success')
                ->with('order_number', $order->order_number)
                ->with('shipping_days', $shippingDays);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order gagal: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Coba lagi.');
        }
    }
}