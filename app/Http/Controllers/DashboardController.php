<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DashboardStatsService;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $stats = DashboardStatsService::forUser($request->user());
        return view('dashboard', compact('stats'));
    }
}
