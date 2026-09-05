<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email', 'max:255'],
            'topic'      => ['nullable', 'string', 'max:255'],
            'subject'    => ['required', 'string', 'max:255'],
            'message'    => ['required', 'string', 'max:5000'],
        ]);

        Mail::raw(
            "Name: {$data['first_name']} {$data['last_name']}\n" .
            "Email: {$data['email']}\n" .
            "Topic: " . ($data['topic'] ?? '-') . "\n" .
            "Subject: {$data['subject']}\n\n" .
            $data['message'],
            function ($mail) use ($data) {
                $mail->to('support@dancepair.ca')
                    ->replyTo($data['email'])
                    ->subject('DancePair Contact: ' . $data['subject']);
            }
        );

        return back()->with('success', 'Your message has been sent successfully.');
    }
}