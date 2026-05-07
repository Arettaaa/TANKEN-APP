<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class CheckoutController extends Controller
{
    // GET /checkout
    public function index()
    {
        $selectedIds = session('checkout_ids', []);

        if (empty($selectedIds)) {
            return redirect()->route('pelanggan.keranjang.index')
                ->with('error', 'Pilih minimal 1 produk untuk checkout.');
        }

        $cartItems = CartItem::whereIn('id', $selectedIds)
            ->where('user_id', auth()->id())
            ->with('product')
            ->get()
            ->map(fn($item) => [
                'id'    => $item->id,
                'name'  => $item->product->name,
                'image' => $item->product->main_image
                    ? asset('storage/' . $item->product->main_image)
                    : null,
                'size'  => $item->size,
                'qty'   => $item->quantity,
                'price' => $item->product->price,
            ]);

        $addresses      = auth()->user()->addresses()->orderByDesc('is_default')->get();
        $defaultAddress = $addresses->firstWhere('is_default', true);

        $PPN_RATE     = 0.11;
        $subtotal     = $cartItems->sum(fn($i) => $i['price'] * $i['qty']);
        $ppn          = round($subtotal * $PPN_RATE);

        $shippingOptions = [
            ['id' => 'jne',      'name' => 'JNE Regular',      'days' => '2-3 hari', 'price' => 150000],
            ['id' => 'jnt',      'name' => 'J&T Express',       'days' => '2-4 hari', 'price' => 120000],
            ['id' => 'sicepat',  'name' => 'SiCepat Reguler',   'days' => '2-3 hari', 'price' => 130000, 'default' => true],
            ['id' => 'anteraja', 'name' => 'AnterAja Standard', 'days' => '3-4 hari', 'price' => 110000],
        ];

        $shippingCost = 130000;
        $total        = $subtotal + $ppn + $shippingCost;

        return view('pelanggan.checkout', compact(
            'cartItems',
            'addresses',
            'defaultAddress',
            'subtotal',
            'ppn',
            'shippingCost',
            'total',
            'shippingOptions'
        ));
    }

    // POST /checkout/simpan-item — dipanggil dari keranjang
    public function simpanItem(Request $request)
    {
        $ids = $request->input('selected_ids', []);

        if (empty($ids)) {
            return back()->with('error', 'Pilih minimal 1 produk untuk checkout.');
        }

        session(['checkout_ids' => $ids]);

        return redirect()->route('pelanggan.checkout.index');
    }

    // POST /checkout/proses — lanjut ke step 2
    public function proses(Request $request)
    {
        // Kalau pakai alamat baru (address_id kosong)
        if (!$request->address_id) {
            $request->validate([
                'new_region'  => 'required|string',
                'new_city_id' => 'required|string',
                'address'     => 'required|string',
                'zip_code'    => 'required|string',
                'shipping_method' => 'required|string',
                'shipping_cost'   => 'required|integer',
            ]);

            // Simpan sebagai alamat baru
            $addr = \App\Models\Address::create([
                'user_id'    => auth()->id(),
                'name'       => auth()->user()->name,
                'phone'      => auth()->user()->phone,
                'street'     => $request->address,
                'region'     => $request->new_region,
                'city_id'    => $request->new_city_id,
                'postal'     => $request->zip_code,
                'is_default' => false,
            ]);

            $addressId = $addr->id;
        } else {
            $request->validate([
                'address_id'      => 'required|exists:addresses,id',
                'shipping_method' => 'required|string',
                'shipping_cost'   => 'required|integer',
            ]);
            $addressId = $request->address_id;
        }

        session([
            'checkout_address_id'    => $addressId,
            'checkout_shipping'      => $request->shipping_method,
            'checkout_shipping_cost' => $request->shipping_cost,
            'checkout_shipping_days' => $request->shipping_days ?? '2-3 hari',
        ]);

        return redirect()->route('pelanggan.checkout.payment');
    }
    public function getOngkir(Request $request)
    {
        $distId  = $request->city_id;
        $weight  = 1;
        $apiKey  = env('BINDERBYTE_API_KEY');
        $origin  = 'dist_32.75.02';

        $courierServices = [
            'jne'     => ['REG'],
            'sicepat' => ['REG'],
            'jnt'     => ['EZ'],
        ];

        $results = [];

        foreach ($courierServices as $courier => $allowedServices) {
            try {
                $response = Http::timeout(5)->asForm()->post('https://api.binderbyte.com/v1/cost', [
                    'api_key'     => $apiKey,
                    'courier'     => $courier,
                    'origin'      => $origin,
                    'destination' => $distId,
                    'weight'      => $weight,

                ]);

                $data = $response->json();

                \Log::info("Response $courier", $data);

                if (!isset($data['data']['results'])) continue;

                foreach ($data['data']['results'] as $result) {
                    foreach ($result['costs'] as $cost) {
                        if (!in_array($cost['service'], $allowedServices)) continue;

                        $results[] = [
                            'courier' => $result['name'],
                            'service' => $cost['service'],
                            'days'    => ($cost['estimated'] && $cost['estimated'] !== '- hari')
                                ? $cost['estimated']
                                : '2-3 hari',
                            'price'   => (int) $cost['price'],
                        ];
                    }
                }
            } catch (\Exception $e) {
                \Log::error("Ongkir error $courier: " . $e->getMessage());
            }
        }

        return response()->json($results);
    }
}
