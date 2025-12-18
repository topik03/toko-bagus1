<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\Address;


class ProfileController extends Controller
{
    /**
     * Display profile dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $recentOrders = $user->orders()->latest()->limit(5)->get();

        return view('profile.dashboard', [
            'user' => $user,
            'recentOrders' => $recentOrders,
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display user's addresses.
     */
    public function addresses()
    {
        $user = Auth::user();

        return view('profile.addresses', [
            'user' => $user,
            'addresses' => $user->addresses ?? collect(),
        ]);
    }

public function createAddress()
{
    return view('profile.addresses.create');
}

    /**
     * Store new address.
     */

public function storeAddress(Request $request)
{
    // Cara 1: Via Auth facade (RECOMMENDED)
    $user = Auth::user();

    // Cara 2: Via request object
    // $user = $request->user();

    if (!$user) {
        return redirect()->route('login')->with('error', 'Silakan login!');
    }

    // Sekarang bisa akses $user->id
    $userId = $user->id;

    // Debug
    // dd([
    //     'user_id' => $userId,
    //     'user_name' => $user->name,
    //     'user_object' => $user
    // ]);

    // Validasi
    $validated = $request->validate([
        'label' => 'required|string|max:50',
        'recipient_name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'address' => 'required|string',
        'city' => 'required|string|max:100',
        'postal_code' => 'required|string|max:10',
        'is_default' => 'sometimes|boolean',
    ]);

    // Reset default addresses jika perlu
    if ($request->has('is_default') && $request->boolean('is_default')) {
        \DB::table('addresses')
            ->where('user_id', $userId)
            ->update(['is_default' => false]);
    }

    // Create address dengan user_id yang valid
    $address = Address::create([
        'user_id' => $userId, // <-- INI SUDAH PASTI ADA
        'label' => $validated['label'],
        'recipient_name' => $validated['recipient_name'],
        'phone' => $validated['phone'],
        'address' => $validated['address'],
        'city' => $validated['city'],
        'postal_code' => $validated['postal_code'],
        'is_default' => $request->has('is_default') ? 1 : 0,
    ]);

    return redirect()->route('profile.addresses')
        ->with('success', 'Alamat berhasil ditambahkan! ID: ' . $address->id);
}



    /**
     * Display chat with sellers.
     */
    public function chats()
    {
        return view('profile.chats');
    }

    /**
     * Display account settings.
     */
    public function settings()
    {
        return view('profile.settings');
    }

    /**
     * Update user password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = $request->user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diperbarui!');
    }

    /**
     * Update user email.
     */
    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
        ]);

        $user = $request->user();
        $user->update([
            'email' => $request->email,
        ]);

        return back()->with('success', 'Email berhasil diperbarui!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
} // ← Kurung penutup class dipindahkan ke sini
