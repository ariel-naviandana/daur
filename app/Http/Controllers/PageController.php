<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home() {
        return view('home');
    }

    public function profile() {
        return view('profile');
    }

    public function chat() {
        return view('chat');
    }

    public function recycle() {
        return view('recycle');
    }

    public function riwayat() {
        return view('riwayat-recycle');
    }

    public function saldo() {
        return view('saldo');
    }

    public function artikel() {
        return view('artikel');
    }

    public function login() {
        return view('login');
    }

    public function register() {
        return view('register');
    }
}

