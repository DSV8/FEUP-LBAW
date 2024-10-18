<?php

namespace App\Http\Controllers;

use Mail;
use Illuminate\Http\Request;
use App\Mail\MailModel;
use TransportException;
use Exception;

class MailController extends Controller
{
    function send(Request $request) {
        // Call the create method of PasswordRecoveryController
        PasswordRecoveryController::create($request);

        $mailData = [
            'email' => $request->email,
            'token' => $request->token,
        ];

        Mail::to($request->email)->send(new MailModel($mailData));
        return redirect()->route('login')->with('success', 'Password recovery email sent successfully.');
    }

    
    function showRecoverPassForm()
    {
        return view('pages.recover_password');
    }
}