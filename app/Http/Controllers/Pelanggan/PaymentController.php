<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    // GET /checkout/payment — Step 2
    public function index()
    {
        // Ambil data dari session (diisi di CheckoutController@proses)
        $addressId    = session('checkout_address_id');
        $shipping     = session('checkout_shipping');
        $shippingCost = session('checkout_shipping_cost');
        $selectedIds  = session('checkout_ids', []);

        if (!$addressId || !$shipping || empty($selectedIds)) {
            return redirect()->route('pelanggan.checkout.index')
                ->with('error', 'Sesi checkout tidak valid. Ulangi dari awal.');
        }

        $cartItems = CartItem::whereIn('id', $selectedIds)
            ->where('user_id', auth()->id())
            ->with('product')
            ->get()
            ->map(fn($item) => [
                'id'    => $item->id,
                'name'  => $item->product->name,
                'sku'   => $item->product->sku ?? null,
                'image' => $item->product->main_image
                    ? asset('storage/' . $item->product->main_image)
                    : null,
                'size'  => $item->size,
                'qty'   => $item->quantity,
                'price' => $item->product->price,
            ]);

        $address = Address::findOrFail($addressId);

        $PPN_RATE     = 0.11;
        $subtotal     = $cartItems->sum(fn($i) => $i['price'] * $i['qty']);
        $ppn          = round($subtotal * $PPN_RATE);
        $total        = $subtotal + $ppn + $shippingCost;

        // Info bank (hardcode, bisa dipindah ke config/settings nanti)
        $banks = [
            ['value' => 'bca',     'label' => 'BCA',     'number' => '1234567890', 'name' => 'TANKEN ID'],
            ['value' => 'mandiri', 'label' => 'Mandiri', 'number' => '0987654321', 'name' => 'TANKEN ID'],
            ['value' => 'bri',     'label' => 'BRI',     'number' => '1122334455', 'name' => 'TANKEN ID'],
        ];

        return view('pelanggan.payment', compact(
            'cartItems',
            'address',
            'shipping',
            'shippingCost',
            'subtotal',
            'ppn',
            'total',
            'banks'
        ));
    }

    // POST /checkout/payment/simpan — Simpan order + upload bukti
    public function simpan(Request $request)
    {
        $request->validate([
            'payment_method'   => 'required|in:bank_transfer,qris',
            'payment_proof'    => 'required|file|mimes:jpg,jpeg,png|max:3072',
            'bank_provider'    => 'required_if:payment_method,bank_transfer|nullable|string',
        ]);

        $addressId    = session('checkout_address_id');
        $shipping     = session('checkout_shipping');
        $shippingCost = (int) session('checkout_shipping_cost');
        $selectedIds  = session('checkout_ids', []);
        $shippingDays = session('checkout_shipping_days', '2-3 hari');


        if (!$addressId || !$shipping || empty($selectedIds)) {
            return redirect()->route('pelanggan.checkout.index')
                ->with('error', 'Sesi checkout kadaluarsa. Ulangi dari awal.');
        }

        $cartItems = CartItem::whereIn('id', $selectedIds)
            ->where('user_id', auth()->id())
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('pelanggan.keranjang.index')
                ->with('error', 'Item keranjang tidak ditemukan.')
                ->with('shipping_days', $shippingDays);
        }

        $PPN_RATE = 0.11;
        $subtotal = $cartItems->sum(fn($i) => $i->product->price * $i->quantity);
        $ppn      = round($subtotal * $PPN_RATE);
        $total    = $subtotal + $ppn + $shippingCost;

        // Upload bukti
        $proofPath = $request->file('payment_proof')
            ->store('payment-proofs', 'public');

        DB::beginTransaction();
        try {
            preg_match('/(\d+)(?!.*\d)/', $shippingDays, $matches);
            $daysMax = isset($matches[1]) ? (int)$matches[1] : 3;
            $address = Address::findOrFail($addressId);
            $order = Order::create([
                'user_id'           => auth()->id(),
                'order_number'      => Order::generateNumber(),
                'customer_name'     => $address->name,
                'customer_email'    => auth()->user()->email,
                'customer_phone'    => $address->phone ?? auth()->user()->phone,
                'shipping_address'     => $address->street,
                'shipping_city'        => $address->region,  // region = "Kel, Kec, Kota, Prov"
                'shipping_province'    => '',                 // kosongkan, sudah ada di region
                'shipping_postal_code' => $address->postal,
                'status'            => 'pending',
                'payment_status'    => 'waiting_confirmation',
                'payment_method'    => $request->payment_method,
                'payment_reference' => $request->bank_provider ?? 'qris',
                'payment_proof'     => $proofPath,
                'paid_at'           => now(),
                'courier'           => $shipping,
                'shipping_cost'     => $shippingCost,
                'subtotal'          => $subtotal,
                'ppn'               => $ppn,
                'total'             => $total,
                'discount'          => 0,
                'estimated_arrival' => now()->addDays($daysMax)->toDateString(),
            ]);

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

            // Hapus cart items yang sudah diorder
            CartItem::whereIn('id', $selectedIds)->delete();

            // Bersihkan session checkout
            session()->forget([
                'checkout_ids',
                'checkout_address_id',
                'checkout_shipping',
                'checkout_shipping_cost',
            ]);

            DB::commit();

            return redirect()->route('checkout.success')
                ->with('order_number', $order->order_number)
                ->with('shipping_days', session('checkout_shipping_days', '2-3 hari'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order gagal: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Coba lagi.');
        }
    }
}
