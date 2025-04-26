<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Notifications\UserApprovalNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        // Vérifie si c'est le premier utilisateur = admin
    $isAdmin = User::count() === 0;

    $status = $isAdmin ? 'Approuvé' : 'En attente';

        // Create the new user with status 'pending'
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'status' => $status, 
        ]);

        // Seulement notifier l’admin si ce n’est pas lui-même
    if (!$isAdmin) {
        $admin = User::find(1); // Supposé être l'admin
        if ($admin) {
            $admin->notify(new UserApprovalNotification($user));
        }
    }

        // Fire the Registered event (if necessary for other purposes)
        event(new Registered($user));

        // Redirect to a page or show a success message
        return redirect()->route('login')->with('status', 'Inscription réussie. Veuillez attendre l’approbation.');
    }
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    // app/Http/Controllers/Auth/RegisterController.php
   
    
        
    
}