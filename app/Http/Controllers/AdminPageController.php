<?php

namespace App\Http\Controllers;

class AdminPageController extends Controller
{
    public function home() {
        return view('home-admin');
    }

    public function recycleManagement() {
        return view('manajemen-recycle');
    }

    public function userManagement() {
        return view('admin-users');
    }

    public function articleManagement() {
        return view('manajemen-artikel');
    }

    public function wasteItemManagement() {
        return view('manajemen-sampah');
    }

    public function saldoManagement() {
        return view('manajemen-saldo');
    }
}

