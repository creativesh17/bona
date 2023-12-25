<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class SubscriberController extends Controller
{
    public function index() {
        $subscribers = Subscriber::latest()->get();
        return view('admin.subscriber', compact('subscribers'));
    }

    public function destroy($subscriber) {
        $sub = Subscriber::findOrFail($subscriber);
        $sub->delete();

        if($sub) {
            Session::flash('success', 'Subscriber Deleted Successfully');
            return redirect()->back();
        }else {
            Session::flash('error', 'An Error Occurred');
            return redirect()->back();
        }
    }

}
