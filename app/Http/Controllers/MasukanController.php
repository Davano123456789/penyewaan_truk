<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MasukanController extends Controller
{
    public function index()
    {
        return view('dashboard.masukan.index');
    }
}
