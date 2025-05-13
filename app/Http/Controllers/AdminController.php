<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    public function loginpage()
    {
        return view('admin.login');
    }

}
