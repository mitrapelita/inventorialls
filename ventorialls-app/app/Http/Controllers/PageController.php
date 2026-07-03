<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function master()
    {
        return view('master');
    }

    public function karyawan()
    {
        return view('karyawan');
    }

    public function dashboard()
    {
        return view('dashboard');
    }

    public function validasi()
    {
        return view('validasi');
    }

    public function transaksi()
    {
        return view('transaksi');
    }

    public function mutasi()
    {
        return view('mutasi');
    }

    public function laporan()
    {
        return view('laporan');
    }

    public function log()
    {
        return view('log');
    }
}
