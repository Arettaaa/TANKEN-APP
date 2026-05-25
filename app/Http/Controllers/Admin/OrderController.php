<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Tarik data order beserta relasi user dan items
        $query = Order::with('user', 'items')->latest();
        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                ->orWhere('customer_name', 'like', "%{$search}%")
                ->orWhere('customer_email', 'like', "%{$search}%")
                ->orWhere('customer_phone', 'like', "%{$search}%")
                ->orWhere('shipping_city', 'like', "%{$search}%")
                ->orWhere('shipping_province', 'like', "%{$search}%")
                ->orWhere('courier', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%")
                ->orWhere('payment_status', 'like', "%{$search}%")
                ->orWhere('payment_method', 'like', "%{$search}%")
                ->orWhere('voucher_code', 'like', "%{$search}%")
                ->orWhere('tracking_number', 'like', "%{$search}%")
                ->orWhere('total', 'like', "%{$search}%")
                ->orWhere('notes', 'like', "%{$search}%")
                ->orWhereRaw("DATE_FORMAT(created_at, '%d %M %Y') LIKE ?", ["%{$search}%"])
                ->orWhereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') LIKE ?", ["%{$search}%"])
                ->orWhereRaw("DATE_FORMAT(created_at, '%d/%m/%Y') LIKE ?", ["%{$search}%"])
                ->orWhereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                })
                ->orWhereHas('items', function ($i) use ($search) {
                    $i->whereHas('product', function ($p) use ($search) {
                        $p->where('name', 'like', "%{$search}%")
                            ->orWhere('sku', 'like', "%{$search}%");
                    });
                });
            });
        }

        $orders = $query->paginate(15)->withQueryString();

        $totalOrders = Order::count();
        $confirmed   = Order::where('status', 'confirmed')->count();
        $shipped     = Order::where('status', 'shipped')->count();
        $delivered   = Order::where('status', 'delivered')->count();
        $cancelled = Order::where('status', 'cancelled')->count();

        return view('admin.order', compact(
            'orders',
            'totalOrders',
            'confirmed',
            'shipped',
            'delivered',
            'cancelled'
        ));
    }

    public function show(Order $order)
    {
        $order->load('user', 'items.product');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status'          => 'required|in:processing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        // Kalau shipped, resi wajib ada
        if ($request->status === 'shipped' && empty($request->tracking_number)) {
            return response()->json(['success' => false, 'message' => 'Nomor resi wajib diisi.'], 422);
        }

        $old = $order->status;

        $order->update([
            'status'          => $request->status,
            'tracking_number' => $request->tracking_number ?? $order->tracking_number,
        ]);

        ActivityLog::log(
            'Status order diubah',
            "{$order->order_number}: {$old} → {$request->status}" .
                ($request->tracking_number ? " | Resi: {$request->tracking_number}" : ''),
            'info'
        );

        return response()->json([
            'success'    => true,
            'message'    => 'Status order berhasil diupdate.',
            'new_status' => $request->status,
        ]);
    }

    public function export()
    {
        $orders = Order::with('user', 'items')->latest()->get();

        $filename = "Tanken_Orders_" . date('Y-m-d_H-i') . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Order ID', 'Nama Customer', 'Email', 'Tanggal', 'Total (Rp)', 'Status Order', 'Status Bayar', 'Kurir', 'Jumlah Item'];

        $callback = function () use ($orders, $columns) {
            $file = fopen('php://output', 'w');

            // Tambahkan header kolom
            fputcsv($file, $columns);

            // Masukkan data baris per baris
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->customer_name,
                    $order->customer_email,
                    $order->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i'),
                    $order->total,
                    strtoupper($order->status),
                    strtoupper($order->payment_status),
                    $order->courier ?? '-',
                    $order->items->sum('quantity')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


    public function konfirmasi(Request $request, Order $order)
    {
        if ($order->payment_status !== 'waiting_confirmation') {
            return response()->json(['success' => false, 'message' => 'Status tidak valid.'], 422);
        }

        $order->update([
            'payment_status' => 'paid',
            'status'         => 'confirmed',
            'paid_at'        => now(),
        ]);

        ActivityLog::log(
            'Pembayaran dikonfirmasi',
            "Order {$order->order_number} diterima oleh admin.",
            'info'
        );

        return response()->json([
            'success' => true,
            'message' => "Order {$order->order_number} berhasil dikonfirmasi.",
        ]);
    }

    public function tolak(Request $request, Order $order)
    {
        if ($order->payment_status !== 'waiting_confirmation') {
            return response()->json(['success' => false, 'message' => 'Status tidak valid.'], 422);
        }

        $request->validate([
            'reason' => 'nullable|string|max:255',
        ]);

        $order->update([
            'payment_status' => 'unpaid',
            'status'         => 'cancelled',
            'notes'          => $request->reason ?? 'Pembayaran ditolak oleh admin.',
        ]);

        ActivityLog::log(
            'Pembayaran ditolak',
            "Order {$order->order_number} ditolak. Alasan: " . ($request->reason ?? '-'),
            'warning'
        );

        return response()->json([
            'success' => true,
            'message' => "Order {$order->order_number} telah ditolak.",
        ]);
    }
}
