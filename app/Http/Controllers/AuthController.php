<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller {
    public function showRegister() { return view('auth.register'); }
    public function register(Request $request) {
        $validated = $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'semester' => 'required|integer|between:1,14',
            'faculty' => 'required|string',
            'major' => 'required|string',
        ]);
        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'student';
        $user = User::create($validated);
        Auth::login($user);
        return redirect()->route('dashboard');
    }
    public function showLogin() { return view('auth.login'); }
    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }
        return back()->withErrors(['email' => 'Kredensial tidak valid.']);
    }
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
