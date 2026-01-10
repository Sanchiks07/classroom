<x-app-layout>
    <div class="py-12 max-w-7xl mx-auto">
        <!-- Assignment Header & Info -->
        <div class="border p-4 rounded mb-6" id="assignment-{{ $assignment->id }}">
            <!-- Title -->
            <h1 class="text-2xl font-bold mb-2 assignment-title">{{ $assignment->title }}</h1>

            <!-- Description -->
            <p class="mb-2 assignment-description">{{ $assignment->description }}</p>

            <!-- File (if exists) -->
            @if($assignment->file_path)
                <p class="assignment-file">
                    {{ $assignment->file_name ?? 'Download' }} - 
                    <a href="{{ route('assignments.download', $assignment) }}" class="text-blue-600 underline">
                        Download
                    </a>
                </p>
            @endif

            <!-- Edit Form (hidden by default, only teachers can see/edit) -->
            @if(auth()->user()->role === 'teacher')
                <form method="POST" action="{{ route('assignments.update', $assignment) }}" class="assignment-edit-form mt-2" style="display: none;" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <input type="text" name="title" value="{{ $assignment->title }}" class="border rounded px-1 py-0.5 w-full mb-2" placeholder="Title">

                    <textarea name="description" rows="3" class="border rounded px-1 py-0.5 w-full mb-2" placeholder="Description">{{ $assignment->description }}</textarea>

                    <input type="file" name="file_path" class="mb-2">

                    <!-- If a file already exists, show download link in the edit form as well -->
                    @if($assignment->file_path)
                        <p class="text-sm mb-2">
                            Current file: {{ $assignment->file_name ?? 'Download' }} - 
                            <a href="{{ route('assignments.download', $assignment) }}" class="text-blue-600 underline">
                                Download
                            </a>
                        </p>
                    @endif

                    <div class="space-x-2">
                        <button type="submit" class="text-green-500 hover:underline">Save</button>
                        <button type="button" onclick="cancelAssignmentEdit({{ $assignment->id }})" class="text-gray-500 hover:underline">Cancel</button>
                    </div>
                </form>

                <!-- Action Buttons (only for teachers) -->
                <div class="mt-2 assignment-actions space-x-2">
                    <button type="button" onclick="editAssignment({{ $assignment->id }})" class="text-blue-500 hover:underline">Edit</button>

                    <form action="{{ route('assignments.destroy', $assignment) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this assignment?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline">Delete</button>
                    </form>
                </div>
            @endif
        </div>

        <!-- Teacher: Submissions Grading -->
        @if(auth()->user()->role === 'teacher')
            <h2 class="text-xl font-semibold mt-6 mb-2">Student Submissions</h2>

            @forelse($assignment->submissions as $submission)
                <div class="mb-4 p-2 border rounded">
                    <strong>{{ $submission->student->username }}</strong>  
                    <span class="ml-2">{{ $submission->file_name }}</span>
                    <a href="{{ route('submissions.download', $submission) }}" class="text-blue-600 underline">
                        Download
                    </a>

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
                        <a href="{{ route('submissions.download', $mySubmission) }}" class="text-blue-600 underline">
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

    <!-- Assignment Edit -->
    <script>
        function editAssignment(id) {
            const container = document.getElementById(`assignment-${id}`);
            container.querySelector('.assignment-title').style.display = 'none';
            container.querySelector('.assignment-description').style.display = 'none';
            const fileEl = container.querySelector('.assignment-file');
            if(fileEl) fileEl.style.display = 'none';
            container.querySelector('.assignment-actions').style.display = 'none';
            container.querySelector('.assignment-edit-form').style.display = 'block';
        }

        function cancelAssignmentEdit(id) {
            const container = document.getElementById(`assignment-${id}`);
            container.querySelector('.assignment-title').style.display = 'block';
            container.querySelector('.assignment-description').style.display = 'block';
            const fileEl = container.querySelector('.assignment-file');
            if(fileEl) fileEl.style.display = 'block';
            container.querySelector('.assignment-actions').style.display = 'flex';
            container.querySelector('.assignment-edit-form').style.display = 'none';
        }
    </script>

    <!-- Comment Edit -->
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