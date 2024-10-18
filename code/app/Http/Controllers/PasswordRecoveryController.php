<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PasswordRecovery;
use App\Models\User;


class PasswordRecoveryController extends Controller
{
    public static function create(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'token' => 'required',
        ]);

        // Create a new user instance
        $user = User::where('email', $request->input('email'))->first();

        $passwordRecovery = new PasswordRecovery([
            'user_id' => $user->id,
            'token' => $request->input('token'),
            'email' => $request->input('email'),
            'expiration_date' => now()->addMinutes(10),
        ]);

        // Save the user to the database
        $passwordRecovery->save();

        // Redirect to a success page or return a response
        return redirect()->route('login')->with('success', 'Password recovery request sent successfully!');
    }
}
