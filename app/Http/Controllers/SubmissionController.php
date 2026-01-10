<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
        $filePath = $uploadedFile->store('submissions');
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

        return back();
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

    public function destroy(Submission $submission)
    {
        if ($submission->student_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($submission->file_path && \Storage::exists($submission->file_path)) {
            \Storage::delete($submission->file_path);
        }

        $submission->delete();

        // Action log
        ActionLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'target_type' => 'Submission',
            'target_id' => $submission->id,
            'description' => 'Deleted submission "' . $submission->file_name . '" for assignment "' . $submission->assignment->title . '"',
        ]);

        return back()->with('success', 'Submission deleted successfully.');
    }
}
