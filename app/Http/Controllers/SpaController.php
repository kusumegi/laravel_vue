<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpaController extends Controller
{
    /**
     * サンプルプロジェクトのルート(コピペ用)
     */
    public function app1()
    {
        // spa/app.blade.phpを返す
        return view('spa/app');
    }

    /**
     * TODOアプリルート
     */
    public function todo()
    {
        // spa/todo.blade.phpを返却
        return view('spa/todo');
    }
}
