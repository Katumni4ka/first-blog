<?php

namespace App\Http\Controllers;

use App\Mail\SubscribeEmail;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubsController extends Controller
{
    public function subscribe(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:subscriptions'
        ]);

        $subscription = Subscription::add($request->get('email'));
        $subscription->generateToken();

        \Mail::to($subscription)->send(new SubscribeEmail($subscription));

        return redirect()->back()->with('status', 'Проверьте Вашу почту!');
    }

    public function verify($token)
    {
        $subs = Subscription::where('token', $token)->FirstOrFail();
        $subs->token = null;
        $subs->save();
        return redirect('/')->with('status', 'Ваша почта подтверждена! Спасибо!');
    }
}
