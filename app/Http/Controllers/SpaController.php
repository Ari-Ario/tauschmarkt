<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpaController extends Controller
{
    //
    public function index()
    {
        return view('index'); // Ensure 'app.blade.php' exists and is the entry point for your Vue SPA
    }
}
