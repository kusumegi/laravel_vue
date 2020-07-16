<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpaController extends Controller
{
    //
    // spa/app.blade.phpを返す
    public function app1()
    {
        return view('spa/app');
    }
}
