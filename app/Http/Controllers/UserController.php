<?php
// app/Http/Controllers/UserController.php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\UserApprovalStatus;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;



class UserController extends Controller
{
    // Display the settings page
    public function settings()
    {
        // Ensure only the admin (user with id = 1) can access this page
        if (Auth::id() !== 1) {
            abort(403, 'Unauthorized action.');
        }

        // Fetch all users except the admin
        $users = User::where('id', '!=', 1)->get();
        return view('settings', compact('users'));
    }
 // Update the logged-in user's account settings (including password update)
 public function updateAccount(Request $request)
 {
     $request->validate([
         'name' => 'required|string|max:255',
         'email' => 'required|email|max:255',
         'current_password' => 'nullable|string|min:8',
         'new_password' => 'nullable|string|min:8|confirmed',
     ]);

     $user = Auth::user();

     // Check if the current password is correct
     if (!Hash::check($request->current_password, $user->password)) {
         return back()->withErrors(['current_password' => 'The provided password does not match our records.']);
     }
     // Update the user information
     $user->update([
        'name' => $request->name,
        'email' => $request->email,
    ]);

    // Update the password if a new password is provided
    if ($request->filled('new_password')) {
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);
    }
    return back()->with('success', 'votre compte est mettre à jour.');

    }

    // Update a user's information
    public function update(Request $request, User $user)
    {
        // Ensure only the admin can update users
        if (Auth::id() !== 1) {
            abort(403, 'Unauthorized action.');
        }

        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        // Update the user
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('settings')->with('success', 'Utilisateur modifié avec succès.');
    }

    // Delete a user
    public function delete(User $user)
    {
        // Ensure only the admin can delete users
        if (Auth::id() !== 1) {
            abort(403, 'Unauthorized action.');
        }

        // Delete the user
        $user->delete();
        return redirect()->route('settings')->with('success', 'Utilisateur supprime avec succès.');
    }

    // Approve a user
    public function approve( $id)
    {
        // Ensure only the admin can approve users
        if (Auth::id() !== 1) {
            abort(403, 'Unauthorized action.');
        }

        // ✅ Ensure the status is updated
        $user = User::findOrFail($id);
        $user->status = 'Approuvé';
        $user->save();

        // Send approval email
    Mail::to($user->email)->send(new UserApprovalStatus($user, 'approved'));

        // Mark the notification as read
    auth()->user()->unreadNotifications()
    ->where('data->user_id', $id)
    ->delete();

        return back()->with('success', 'Utilisateur approuvé avec succès.');
    }

    // Reject a user
    public function reject( $id)
    {
        // Ensure only the admin can reject users
        if (Auth::id() !== 1) {
            abort(403, 'Unauthorized action.');
        }
        // Find user by ID and update status
    $user = User::findOrFail($id);
    $user->status = 'Rejeté';
    $user->save();

    // Send rejection email
    Mail::to($user->email)->send(new UserApprovalStatus($user, 'rejected'));

        // ✅ Ensure the status is updated
     auth()->user()->unreadNotifications()
        ->where('data->user_id', $id)
        ->delete();
        return redirect()->route('settings')->with('success', 'Utilisateur rejeté avec succès.');
    }
}