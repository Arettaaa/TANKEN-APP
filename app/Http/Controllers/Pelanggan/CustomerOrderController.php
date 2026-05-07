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
                $tabStatus = match($order->status) {
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
                    'payment'  => match($order->payment_method) {
                        'bank_transfer' => 'Transfer Bank (' . strtoupper($order->payment_reference ?? '') . ')',
                        'qris'          => 'QRIS',
                        default         => $order->payment_method ?? '-',
                    },
                ];
            });

        return view('pelanggan.profil-order', compact('orders'));
    }
}