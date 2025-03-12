<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);
    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        // ✅ Allow the admin to log in, regardless of status
        if ($user->id == 1) {
            return redirect()->route('home');
        }

         // ❌ Block responsables if their status is pending
        if ($user->status === 'pending') {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Votre compte est en attente d\'approbation.',
            ]);
        }
        if ($user->status === 'rejected') {
            Auth::logout();
            return redirect()->back()->withErrors(['email' => 'Votre compte a été rejeté. Contactez l\'administrateur.']);
        }
        return redirect()->route('home'); // Redirect to dashboard after successful login
    }
    return back()->withErrors(['email' => 'Les informations de connexion sont incorrectes.']);
}
}
