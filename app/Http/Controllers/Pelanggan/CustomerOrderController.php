<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Carbon;

class CustomerOrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with('items.product')
            ->latest()
            ->get()
            ->map(function ($order) {
                // Tab filter mapping
                $tabStatus = match ($order->status) {
                    'pending', 'waiting_confirmation' => 'menunggu',
                    'confirmed', 'processing'         => 'processing',
                    'shipped'                         => 'shipped',
                    'delivered'                       => 'delivered',
                    'cancelled'                       => 'cancelled',
                    default                           => 'menunggu',
                };

                // Estimasi tiba
                $estDate = $order->estimated_arrival
                    ? Carbon::parse($order->estimated_arrival)
                    : Carbon::parse($order->created_at)->addDays(3);

                Carbon::setLocale('id');

                return [
                    'db_id'      => $order->id,
                    'id'         => $order->order_number,
                    'date'       => Carbon::parse($order->created_at)->translatedFormat('d M Y'),
                    'est_date'   => $estDate->translatedFormat('d M Y'),
                    'items'      => $order->items->count(),
                    'total'      => $order->total,
                    'status'     => $order->status,
                    'tab_status' => $tabStatus,
                    'tracking'   => $order->tracking_number ?? '-',
                    'courier'    => $order->courier ?? '',
                    'products'   => $order->items->map(fn($item) => [
                        'name'  => $item->product_name,
                        'size'  => $item->size,
                        'color' => $item->color ?? '-',
                        'qty'   => $item->quantity,
                        'price' => $item->price,
                        'image' => $item->product?->main_image
                            ? asset('storage/' . $item->product->main_image)
                            : null,
                    ]),
                    'address'  => implode(', ', array_filter([
                        $order->shipping_address,
                        $order->shipping_city,
                        $order->shipping_province,
                        $order->shipping_postal_code,
                    ])),
                    'payment'  => match ($order->payment_method) {
                        'bank_transfer' => 'Transfer Bank (' . strtoupper($order->payment_reference ?? '') . ')',
                        'qris'          => 'QRIS',
                        default         => $order->payment_method ?? '-',
                    },
                ];
            });

        return view('pelanggan.profil-order', compact('orders'));
    }

    public function beliLagi($id)
    {
        $order = \App\Models\Order::with('items')->where('user_id', auth()->id())->findOrFail($id);

        foreach ($order->items as $item) {
            $cartItem = \App\Models\CartItem::where('user_id', auth()->id())
                ->where('product_id', $item->product_id)
                ->where('size', $item->size)
                ->where('color', $item->color)
                ->first();

            if ($cartItem) {
                $cartItem->increment('quantity', $item->quantity);
            } else {
                \App\Models\CartItem::create([
                    'user_id'    => auth()->id(),
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'size'       => $item->size,
                    'color'      => $item->color,
                ]);
            }
        }

        return redirect()->route('pelanggan.keranjang.index')
            ->with('success', 'Produk dari pesanan sebelumnya berhasil ditambahkan ke keranjang!');
    }
}
