<x-app-layout>
    <div class="py-12 max-w-7xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">{{ $assignment->title }}</h1>

        <p class="mb-4">
            {{ $assignment->description }}
        </p>

        @if($assignment->file_path)
            <p>
                File: <a href="{{ Storage::url($assignment->file_path) }}" target="_blank">Download</a>
            </p>
        @endif

        <!-- Teacher: Submissions Grading -->
        @if(auth()->user()->role === 'teacher')
            <h2 class="text-xl font-semibold mt-6 mb-2">Student Submissions</h2>

            @forelse($assignment->submissions as $submission)
                <div class="mb-4 p-2 border rounded">
                    <strong>{{ $submission->student->username }}</strong>  
                    <span class="ml-2">| {{ $submission->file_name }}</span>
                    <a href="{{ Storage::url($submission->file_path) }}" target="_blank" class="ml-2">Download</a>

                    <form method="POST" action="{{ route('submissions.grade', $submission) }}" class="mt-2">
                        @csrf

                        <input type="number" min="1" name="grade" placeholder="Grade" value="{{ $submission->grade }}">
                        <textarea name="feedback" placeholder="Feedback">{{ $submission->feedback }}</textarea>

                        <button type="submit">Save</button>
                    </form>
                </div>
            @empty
                <p>No submissions yet.</p>
            @endforelse
        @endif

        <!-- Student: Submit Assignment -->
        @if(auth()->user()->role === 'student')
            @php
                $mySubmission = $assignment->submissions->where('student_id', auth()->id())->first();
            @endphp

            @if($mySubmission)
                <div class="mb-4 p-2 border rounded">
                    <strong>Your Submission:</strong> {{ $mySubmission->file_name }}<br>

                    <span class="block mt-1">Grade: {{ $mySubmission->grade ?? 'Not graded yet' }}</span>
                    <span class="block mt-1">Feedback: {{ $mySubmission->feedback ?? 'No feedback yet' }}</span>

                    <div class="flex items-center space-x-4 mt-2">
                        <a href="{{ Storage::url($mySubmission->file_path) }}" target="_blank" class="ml-2">
                            Download
                        </a>

                        <form method="POST" action="{{ route('submissions.destroy', $mySubmission) }}">
                            @csrf
                            @method('DELETE')

                            <button type="submit" onclick="return confirm('Are you sure you want to delete this submission?')" class="inline-block ml-2">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <form method="POST" action="{{ route('submissions.store', $assignment) }}" enctype="multipart/form-data">
                    @csrf

                    <input type="file" name="file" required>
                    
                    <button type="submit">Submit Assignment</button>
                </form>
            @endif
        @endif
    </div>
</x-app-layout>