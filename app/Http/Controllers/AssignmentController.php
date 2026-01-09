<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Classroom;

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

        $classroom->assignments()->create($data);

        return redirect()->route('classrooms.show', $classroom);
    }

    public function show(Assignment $assignment)
    {
        return view('assignments.show', compact('assignment'));
    }
}
