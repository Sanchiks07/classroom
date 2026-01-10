<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ActionLog;

class AdminHistoryController extends Controller
{
    public function __construct()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }
    }

    public function index()
    {
        $logs = ActionLog::latest()->get();

        return view('admin.history.index', compact('logs'));
    }
}
