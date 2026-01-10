<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Classroom;
use App\Models\ActionLog;

class ClassroomController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $classrooms = $user->role === 'teacher'
            ? $user->classroomsTeaching
            : $user->classrooms;

        return view('classrooms.index', compact('classrooms'));
    }

    public function store(Request $request)
    {
        $classroom = Classroom::create([
            'name' => $request->name,
            'teacher_id' => auth()->id(),
            'code' => Str::upper(Str::random(6)),
        ]);

        // Action log
        ActionLog::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'target_type' => 'Classroom',
            'target_id' => $classroom->id,
            'description' => 'Created classroom "' . $classroom->name . '" with code ' . $classroom->code,
        ]);

        return redirect()->route('classrooms.index');
    }

    public function show(Classroom $classroom)
    {
        return view('classrooms.show', compact('classroom'));
    }

    public function join(Request $request)
    {
        $classroom = Classroom::where('code', $request->code)->firstOrFail();
        $classroom->students()->syncWithoutDetaching(auth()->id());

        // Action log
        ActionLog::create([
            'user_id' => auth()->id(),
            'action' => 'joined',
            'target_type' => 'Classroom',
            'target_id' => $classroom->id,
            'description' => 'Joined classroom "' . $classroom->name . '" using code ' . $classroom->code,
        ]);

        return redirect()->route('classrooms.index');
    }
}
