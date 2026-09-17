<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class HrDashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.hr.dashboard');
    }
}