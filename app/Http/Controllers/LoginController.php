<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('login');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(
            ['username' => 'required|string', 'password' => 'required|string'],
            ['required' => ':attribute wajib diisi.'],
            ['username' => 'Username', 'password' => 'Password'],
        );

        // kolom email di tabel users dipakai untuk menyimpan username
        if (! Auth::attempt(['email' => $data['username'], 'password' => $data['password']])) {
            return back()->withErrors(['username' => 'Username atau password salah.'])->onlyInput('username');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('bookings.index'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
