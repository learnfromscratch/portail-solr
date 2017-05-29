<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Mail;
use App\Mail\Newsletter;
use App\Notifications\Test;

class TestController extends Controller
{
    public function index()
    {
    	Auth::user()->notify(new Test());
    	return view('test');
    }
    
}
