<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailUpdateRequest;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }

    public function edit()
    {
        $user = Auth::user();
        $user_email = $user->email;
        
        return view('auth.edit-email', compact('user_email'));
    }

    public function update(EmailUpdateRequest $request) {
        /** @var User $user */
        $user = Auth::user();
        $user->update($request->validated());

        event(new Registered($user));

        return to_route('verification.notice')->with('status', 'email-updated');
    }
}
