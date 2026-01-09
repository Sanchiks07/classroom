<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Submission;
use App\Models\Assignment;

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

        $assignment->submissions()->create([
            'student_id' => auth()->id(),
            'file_path' => $filePath,
            'file_name' => $fileName,
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

        return back()->with('success', 'Submission deleted successfully.');
    }
}
