<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function index()
    {
        return view('dashboard.tagihan.index');
    }
    public function tambah()
    {
        return view('dashboard.tagihan.tambah');
    }
}


