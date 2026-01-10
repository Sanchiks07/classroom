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

        <!-- Comments Section -->
        <hr class="my-8">

        <h2 class="text-xl font-semibold mb-4">Comments</h2>

        @foreach($assignment->comments as $comment)
            <div class="border p-3 mb-3 rounded" id="comment-{{ $comment->id }}">
                <strong>{{ $comment->user->username }}</strong>
                <span class="text-sm text-gray-500">
                    {{ $comment->created_at->diffForHumans() }}
                </span>

                <!-- Comment text -->
                <p class="mt-2 comment-text">
                    {{ $comment->body }}
                </p>

                @if(auth()->id() === $comment->user_id)
                    <!-- Edit form (hidden by default) -->
                    <form method="POST" action="{{ route('comments.update', $comment) }}" class="mt-2 comment-edit-form" style="display: none;">
                        @csrf
                        @method('PATCH')

                        <textarea name="body" rows="2">{{ $comment->body }}</textarea>

                        <div class="mt-1">
                            <button type="submit">Save</button>
                            <button type="button" onclick="cancelEdit({{ $comment->id }})">
                                Cancel
                            </button>
                        </div>
                    </form>

                    <!-- Action buttons -->
                    <div class="mt-2 comment-actions">
                        <button type="button" onclick="editComment({{ $comment->id }})">
                            Edit
                        </button>

                        <form method="POST" action="{{ route('comments.destroy', $comment) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button type="submit" onclick="return confirm('Delete this comment?')">
                                Delete
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @endforeach

        <!-- Student Comment Form -->
        @if(auth()->user()->role === 'student') 
            <form method="POST" action="{{ route('comments.store', $assignment) }}">
                @csrf
                <textarea name="body" rows="3" required placeholder="Write a comment..."></textarea>
                <button type="submit" class="mt-2">
                    Post Comment
                </button>
            </form>
        @endif
    </div>

    <script>
        function editComment(id) {
            const container = document.getElementById(`comment-${id}`);
            container.querySelector('.comment-text').style.display = 'none';
            container.querySelector('.comment-actions').style.display = 'none';
            container.querySelector('.comment-edit-form').style.display = 'block';
        }

        function cancelEdit(id) {
            const container = document.getElementById(`comment-${id}`);
            container.querySelector('.comment-text').style.display = 'block';
            container.querySelector('.comment-actions').style.display = 'block';
            container.querySelector('.comment-edit-form').style.display = 'none';
        }
    </script>
</x-app-layout>