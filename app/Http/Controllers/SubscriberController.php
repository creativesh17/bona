<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class SubscriberController extends Controller
{
    public function store(Request $request) {
        $this->validate($request, [
            'email' => 'required|email|unique:subscribers'
        ]);

        $subscriber = new Subscriber();
        $subscriber->email = $request->email;
        $subscriber->save();

        if($subscriber) {
            Session::flash('success', 'Subscriber Added Successfully');
            return redirect()->back();
        }else {
            Session::flash('error', 'An Error Occurred');
            return redirect()->back();
        }

    }
}
