<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ExportHelper;


class UserController extends Controller
{
    public function index(Request $request)
    {
        // Tarik data user beserta jumlah order dan total belanjanya
        $query = User::withCount('orders')->withSum('orders', 'total');

        // 1. Search
        if ($search = $request->search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }
        // 2. Filter Role & Status
        if ($role = $request->role) $query->where('role', $role);
        if ($status = $request->status) $query->where('is_active', $status === 'active');
        
        // 3. Filter Total Orders Range
        if ($orders = $request->orders) {
            if ($orders === '0') $query->having('orders_count', 0);
            elseif ($orders === '1-5') $query->havingBetween('orders_count', [1, 5]);
            elseif ($orders === '6-10') $query->havingBetween('orders_count', [6, 10]);
            elseif ($orders === '11+') $query->having('orders_count', '>=', 11);
        }

        // 4. Sorting
        if ($sort = $request->sort) {
            if ($sort === 'az') $query->orderBy('name', 'asc');
            elseif ($sort === 'orders_desc') $query->orderBy('orders_count', 'desc');
            elseif ($sort === 'spent_desc') $query->orderBy('orders_sum_total', 'desc');
            elseif ($sort === 'newest') $query->latest();
        } else {
            $query->latest();
        }

        $users = $query->paginate(15)->withQueryString();

        // Data KPI Stats
        $totalUsers  = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $customers   = User::where('role', 'customer')->count();
        // Cek admin dan super_admin
        $admins      = User::whereIn('role', ['admin_gudang', 'super_admin'])->count();

        return view('admin.users', compact('users', 'totalUsers', 'activeUsers', 'customers', 'admins'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:customer,super_admin,admin_gudang',
            'password' => 'required|min:8',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return response()->json(['success' => true]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|in:customer,super_admin,admin_gudang',
        ]);

        $user->update($validated);
        return response()->json(['success' => true]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['success' => true]);
    }

    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return response()->json(['success' => true, 'is_active' => $user->is_active]);
    }

    public function exportExcel(Request $request)
{
    $users = \App\Models\User::withCount('orders')->withSum('orders', 'total')->get();
 
    $columns = ['ID', 'Nama', 'Email', 'Role', 'Total Order', 'Total Belanja', 'Status', 'Bergabung'];
 
    $rows = $users->map(fn($u) => [
        $u->id,
        $u->name,
        $u->email,
        ucfirst(str_replace('_', ' ', $u->role)),
        $u->orders_count,
        'Rp ' . number_format($u->orders_sum_total ?? 0, 0, ',', '.'),
        $u->is_active ? 'Aktif' : 'Nonaktif',
        $u->created_at->format('Y-m-d'),
    ]);
 
    return ExportHelper::excel('Tanken_Users', 'Laporan Pengguna', $columns, $rows);
}
 
}