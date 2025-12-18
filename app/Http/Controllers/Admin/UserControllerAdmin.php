<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserControllerAdmin extends Controller
{
    /**
     * Display a listing of the users.
     */
public function index()
{
    // Gunakan withCount bukan with untuk menghitung saja
    $users = User::withCount(['orders', 'reviews'])
        ->latest()
        ->paginate(15);

    // Statistics
    $totalUsers = User::count();
    $adminUsers = User::where('is_admin', true)->count();
    $usersWithOrders = User::has('orders')->count();

    return view('admin.users.index', compact(
        'users',
        'totalUsers',
        'adminUsers',
        'usersWithOrders'
    ));
}

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',

        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'is_admin' => $request->is_admin ?? false,
            'is_active' => $request->is_active ?? true,

        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Display the specified user.
     */
public function show(User $user)
{
    $user->load(['orders', 'reviews.product', 'addresses']);

    // Statistics for this user
    $totalOrders = $user->orders()->count();

    // ==== PERBAIKAN DI SINI ====
    // Gunakan order_status, bukan status
    $totalSpent = $user->orders()
        ->where('order_status', 'completed') // ← INI YANG BENAR
        ->sum('total');
    // ============================

    $totalReviews = $user->reviews()->count();

    return view('admin.users.show', compact(
        'user',
        'totalOrders',
        'totalSpent',
        'totalReviews'
    ));
}

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_admin' => $request->is_admin ?? $user->is_admin,
            'is_active' => $request->is_active ?? $user->is_active,
        ];

        // Update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting own account
        if ($user->id === auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        // Check if user has orders
        if ($user->orders()->count() > 0) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Tidak dapat menghapus user yang memiliki pesanan.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    /**
     * Toggle admin status
     */
public function toggleAdmin(User $user)
{
    // Prevent removing admin from own account
    if ($user->id === Auth::id() && $user->is_admin) {
        return redirect()->back()
            ->with('error', 'Tidak dapat menghapus status admin dari akun sendiri.');
    }

    $user->update([
        'is_admin' => !$user->is_admin,
    ]);

    return redirect()->back()
        ->with('success', 'Status admin berhasil diubah.');
}

public function toggleActive(User $user)
{
    $user->update([
        'is_active' => !$user->is_active,
    ]);

    return redirect()->back()
        ->with('success', 'Status aktif berhasil diubah.');
}

    /**
     * Show user statistics
     */
    public function statistics()
    {
        // User registration statistics for last 30 days
        $dailyRegistrations = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Monthly registrations
        $monthlyRegistrations = User::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count')
            ->where('created_at', '>=', now()->subYear())
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // User activity (last login)
        $activeUsers = User::whereNotNull('last_login_at')
            ->where('last_login_at', '>=', now()->subDays(7))
            ->count();

        $inactiveUsers = User::where(function($query) {
            $query->whereNull('last_login_at')
                ->orWhere('last_login_at', '<', now()->subDays(30));
        })->count();

        return view('admin.users.statistics', compact(
            'dailyRegistrations',
            'monthlyRegistrations',
            'activeUsers',
            'inactiveUsers'
        ));
    }

    /**
     * Impersonate user (login as user)
     */
    public function impersonate(User $user)
    {
        // Store original admin ID in session
        session()->put('impersonator_id', auth::id());

        // Login as the user
        auth::login($user);

        return redirect()->route('home')
            ->with('success', 'Berhasil login sebagai ' . $user->name);
    }

    /**
     * Stop impersonating
     */
    public function stopImpersonate()
    {
        if (!session()->has('impersonator_id')) {
            return redirect()->route('home');
        }

        // Get original admin
        $admin = User::find(session()->get('impersonator_id'));

        if ($admin) {
            // Login back as admin
            auth::login($admin);
            session()->forget('impersonator_id');

            return redirect()->route('admin.users.index')
                ->with('success', 'Berhasil kembali ke akun admin.');
        }

        return redirect()->route('home');
    }
}
