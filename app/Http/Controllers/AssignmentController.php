<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\ActionLog;

class AssignmentController extends Controller
{
    public function store(Request $request, Classroom $classroom)
    {
        $request->validate(
            [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'due_date' => 'nullable|date|after_or_equal:today',
                'file_path' => 'nullable|file',
            ],
            [
                'due_date.after_or_equal' => 'The due date cannot be in the past.',
            ]
        );

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
        ];

        if ($request->hasFile('file_path')) {
            $uploadedFile = $request->file('file_path');
            $data['file_path'] = $uploadedFile->store('assignments', 'public');
            $data['file_name'] = $uploadedFile->getClientOriginalName();
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

        return redirect()->route('classrooms.show', $classroom)->with('success', 'Assignment created successfully.');
    }

    public function show(Assignment $assignment)
    {
        $assignment->load([
            'submissions.student',
            'comments.user',
        ]);

        return view('assignments.show', compact('assignment'));
    }

    public function update(Request $request, Assignment $assignment)
    {
        $request->validate(
            [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'due_date' => 'nullable|date|after_or_equal:today',
                'file_path' => 'nullable|file',
            ],
            [
                'due_date.after_or_equal' => 'The due date cannot be in the past.',
            ]
        );

        $assignment->title = $request->title;
        $assignment->description = $request->description;
        $assignment->due_date = $request->due_date;

        if ($request->hasFile('file_path')) {
            // Delete old file if exists
            if ($assignment->file_path && \Storage::exists($assignment->file_path)) {
                \Storage::delete($assignment->file_path);
            }

            $uploadedFile = $request->file('file_path');
            $assignment->file_path = $uploadedFile->store('assignments', 'public');
            $assignment->file_name = $uploadedFile->getClientOriginalName();
        }

        $assignment->save();

        // Acion log
        ActionLog::create([
            'user_id' => Auth::id(),
            'action' => 'Updated',
            'target_type' => 'Assignment',
            'target_id' => $assignment->id,
            'description' => 'Updated assignment to: ' . $assignment->title,
        ]);

        return back()->with('success', 'Assignment updated successfully.');
    }

    public function destroy(Assignment $assignment)
    {
        $classroom = $assignment->classroom;
        
        // Delete file if exists
        if ($assignment->file_path && \Storage::exists($assignment->file_path)) {
            \Storage::delete($assignment->file_path);
        }

        $title = $assignment->title;
        $assignment->delete();

        // Action log
        ActionLog::create([
            'user_id' => Auth::id(),
            'action' => 'Deleted',
            'target_type' => 'Assignment',
            'target_id' => $assignment->id,
            'description' => 'Deleted assignment: ' . $title,
        ]);

        return redirect()->route('classrooms.show', $classroom)->with('success', 'Assignment deleted successfully.');
    }

    public function download(Assignment $assignment)
    {
        if (!$assignment->file_path || !Storage::disk('public')->exists($assignment->file_path)) {
            abort(404, 'File not found.');
        }

        // Action log
        ActionLog::create([
            'user_id' => Auth::id(),
            'action' => 'downloaded',
            'target_type' => 'Assignment',
            'target_id' => $assignment->id,
            'description' => 'Downloaded assignment file "' . $assignment->file_name . '"',
        ]);

        return Storage::disk('public')->download(
            $assignment->file_path,
            $assignment->file_name
        );
    }
}
