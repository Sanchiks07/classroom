<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignment;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index($month = null, $year = null)
    {
        $month = $month ?? Carbon::now()->month;
        $year = $year ?? Carbon::now()->year;

        $startOfMonth = Carbon::create($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $prevMonth = $startOfMonth->copy()->subMonth();
        $nextMonth = $startOfMonth->copy()->addMonth();
        $today = Carbon::today()->format('Y-m-d');

        // Get assignments grouped by date as array (for Blade indexing)
        $assignments = Assignment::whereBetween('due_date', [$startOfMonth->startOfDay(), $endOfMonth->endOfDay()])
            ->get()
            ->groupBy(function($assignment){
                return $assignment->due_date->format('Y-m-d');
            })
            ->map(function($dayAssignments) {
                return $dayAssignments->all();
            })
            ->toArray();

        // Calendar days (Monday start)
        $firstDay = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);
        $lastDay = $endOfMonth->copy()->endOfWeek();
        $days = [];
        for ($date = $firstDay->copy(); $date <= $lastDay; $date->addDay()) {
            $days[] = $date->copy();
        }

        // Classroom colors
        $colors = [
            1  => 'bg-red-100 text-red-800 dark:bg-red-600 dark:text-red-100',
            2  => 'bg-orange-100 text-orange-800 dark:bg-orange-600 dark:text-orange-100',
            3  => 'bg-amber-100 text-amber-800 dark:bg-amber-600 dark:text-amber-100',
            4  => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-600 dark:text-yellow-100',
            5  => 'bg-lime-100 text-lime-800 dark:bg-lime-600 dark:text-lime-100',
            6  => 'bg-green-100 text-green-800 dark:bg-green-600 dark:text-green-100',
            7  => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-600 dark:text-emerald-100',
            8  => 'bg-teal-100 text-teal-800 dark:bg-teal-600 dark:text-teal-100',
            9  => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-600 dark:text-cyan-100',
            10 => 'bg-sky-100 text-sky-800 dark:bg-sky-600 dark:text-sky-100',
            11 => 'bg-blue-100 text-blue-800 dark:bg-blue-600 dark:text-blue-100',
            12 => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-600 dark:text-indigo-100',
            13 => 'bg-violet-100 text-violet-800 dark:bg-violet-600 dark:text-violet-100',
            14 => 'bg-purple-100 text-purple-800 dark:bg-purple-600 dark:text-purple-100',
            15 => 'bg-fuchsia-100 text-fuchsia-800 dark:bg-fuchsia-600 dark:text-fuchsia-100',
            16 => 'bg-pink-100 text-pink-800 dark:bg-pink-600 dark:text-pink-100',
            17 => 'bg-rose-100 text-rose-800 dark:bg-rose-600 dark:text-rose-100',
            18 => 'bg-slate-100 text-slate-800 dark:bg-slate-600 dark:text-slate-100',
        ];

        return view('calendar.index', compact(
            'days','assignments','month','year','today','prevMonth','nextMonth','colors'
        ));
    }
}
