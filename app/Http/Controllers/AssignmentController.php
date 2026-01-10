<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\ActionLog;

class AssignmentController extends Controller
{
    public function store(Request $request, Classroom $classroom)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'file' => 'nullable|file',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
        ];

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('assignments');
        }

        $assignment = $classroom->assignments()->create($data);

        // Action log
        ActionLog::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'target_type' => 'Assignment',
            'target_id' => $assignment->id,
            'description' => 'Created assignment "' . $assignment->title . '" in classroom "' . $classroom->name . '"',
        ]);

        return redirect()->route('classrooms.show', $classroom);
    }

    public function show(Assignment $assignment)
    {
        $assignment->load([
            'submissions.student',
            'comments.user',
        ]);

        return view('assignments.show', compact('assignment'));
    }
}
