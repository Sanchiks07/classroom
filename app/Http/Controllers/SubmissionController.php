<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Submission;
use App\Models\Assignment;
use App\Models\ActionLog;

class SubmissionController extends Controller
{
    public function store(Request $request, Assignment $assignment)
    {
        $request->validate([
            'file' => 'required|file',
        ]);

        $uploadedFile = $request->file('file');
        $filePath = $uploadedFile->store('submissions', 'public');
        $fileName = $uploadedFile->getClientOriginalName();

        $submission = $assignment->submissions()->create([
            'student_id' => auth()->id(),
            'file_path' => $filePath,
            'file_name' => $fileName,
        ]);

        // Action log
        ActionLog::create([
            'user_id' => auth()->id(),
            'action' => 'submitted',
            'target_type' => 'Submission',
            'target_id' => $submission->id,
            'description' => 'Submitted file "' . $fileName . '" for assignment "' . $assignment->title . '"',
        ]);

        return back()->with('success', 'Submission created successfully.');
    }

    public function grade(Request $request, Submission $submission)
    {
        $request->validate([
            'grade' => 'nullable|string|max:10',
            'feedback' => 'nullable|string',
        ]);

        $submission->update([
            'grade' => $request->grade,
            'feedback' => $request->feedback,
        ]);

        // Action log
        ActionLog::create([
            'user_id' => auth()->id(),
            'action' => 'graded',
            'target_type' => 'Submission',
            'target_id' => $submission->id,
            'description' => 'Graded submission "' . $submission->file_name . '" for assignment "' . $submission->assignment->title . '" with grade "' . $request->grade . '"',
        ]);

        return back();
    }

    public function update(Request $request, Submission $submission)
    {
        // Only the owner (student) or a teacher can update a submission
        if ($submission->student_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'file' => 'nullable|file',
        ]);

        if ($request->hasFile('file')) {
            // Delete old file if exists on the public disk
            if ($submission->file_path && Storage::disk('public')->exists($submission->file_path)) {
                Storage::disk('public')->delete($submission->file_path);
            }

            $uploadedFile = $request->file('file');
            $submission->file_path = $uploadedFile->store('submissions', 'public');
            $submission->file_name = $uploadedFile->getClientOriginalName();
        }

        $submission->save();

        // Action log
        ActionLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'target_type' => 'Submission',
            'target_id' => $submission->id,
            'description' => 'Updated submission "' . $submission->file_name . '" for assignment "' . $submission->assignment->title . '"',
        ]);

        return back()->with('success', 'Submission updated successfully.');
    }

    public function download(Submission $submission)
    {
        // Optional: permission checks (teacher or owner)
        if ($submission->student_id !== auth()->id() && auth()->user()->role !== 'teacher') {
            abort(403);
        }

        if (!$submission->file_path || !Storage::disk('public')->exists($submission->file_path)) {
            abort(404, 'File not found.');
        }

        // Action log
        ActionLog::create([
            'user_id' => Auth::id(),
            'action' => 'downloaded',
            'target_type' => 'Submission',
            'target_id' => $submission->id,
            'description' => 'Downloaded submission file "' . $submission->file_name . '"',
        ]);

        return Storage::disk('public')->download(
            $submission->file_path,
            $submission->file_name
        );
    }
}
