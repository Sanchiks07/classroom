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
            ? $user->classroomsTeaching()->withCount('assignments')->with('teacher')->get()
            : $user->classrooms()->withCount('assignments')->with('teacher')->get();

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

    public function update(Request $request, Classroom $classroom)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $classroom->update([
            'name' => $request->name,
        ]);

        // Action log
        ActionLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'target_type' => 'Classroom',
            'target_id' => $classroom->id,
            'description' => 'Updated classroom name to "' . $classroom->name . '"',
        ]);

        return back();
    }

    public function destroy(Classroom $classroom)
    {
        $name = $classroom->name;
        $classroom->delete();

        // Action log
        ActionLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'target_type' => 'Classroom',
            'target_id' => $classroom->id,
            'description' => 'Deleted classroom "' . $name . '"',
        ]);

        return back();
    }

    public function join(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $classroom = Classroom::where('code', $request->code)->first();

        if (!$classroom) {
            return back()
                ->withErrors(['code' => 'Invalid classroom code.'])
                ->withInput();
        }

        // Prevent duplicate joins
        if ($classroom->students()->where('user_id', auth()->id())->exists()) {
            return back()
                ->withErrors(['code' => 'You are already in this classroom.']);
        }

        $classroom->students()->attach(auth()->id());

        return back()->with('success', 'Successfully joined the classroom.');
    }
}
