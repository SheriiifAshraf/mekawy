<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('home');
    }

    public function managerDashboard()
    {
        return view('back.pages.home');
    }

    public function superAdminDashboard()
    {
        return view('super_admin_dashboard');
    }
}
