<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    /**
     * Menampilkan halaman pengaturan admin.
     */
    public function index()
    {
        return view('pengaturan.index');
    }
}