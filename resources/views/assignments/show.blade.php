<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container mx-auto p-6 space-y-8">

                    <!-- Assignment Card -->
                    <div class="rounded-lg" id="assignment-{{ $assignment->id }}">
                        <h1 class="text-2xl font-bold mb-3 assignment-title text-gray-900 dark:text-gray-200">{{ $assignment->title }}</h1>
                        <p class="text-gray-700 dark:text-gray-300 mb-2 assignment-description">{{ $assignment->description }}</p>

                        @if($assignment->due_date)
                            <p class="text-gray-500 dark:text-gray-400 mb-2 assignment-due-date"><strong>Due:</strong> {{ $assignment->due_date->format('F j, Y') }}</p>
                        @endif

                        @if($assignment->file_path)
                            <p class="text-blue-600 dark:text-blue-400 underline mb-2 assignment-file">
                                <a href="{{ route('assignments.download', $assignment) }}">
                                    {{ $assignment->file_name ?? 'Download File' }}
                                </a>
                            </p>
                        @endif

                        <!-- Edit Form (Teachers Only) -->
                        @if(auth()->user()->role === 'teacher')
                            <form method="POST" action="{{ route('assignments.update', $assignment) }}" class="assignment-edit-form rounded-lg space-y-3 max-w-2xl hidden" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <input type="text" name="title" value="{{ $assignment->title }}" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-200 focus:outline-none dark:bg-gray-700 dark:text-gray-200" placeholder="Title">

                                <textarea name="description" rows="3" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-200 focus:outline-none resize-none dark:bg-gray-700 dark:text-gray-200" placeholder="Description">{{ $assignment->description }}</textarea>

                                <input type="date" name="due_date" value="{{ $assignment->due_date?->format('Y-m-d') }}" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-200 focus:outline-none dark:bg-gray-700 dark:text-gray-200">

                                <input type="file" name="file_path" class="w-full text-sm text-gray-600 dark:text-gray-300">

                                @if($assignment->file_path)
                                    <p class="text-sm dark:text-gray-200">
                                        Current file: 
                                        <a href="{{ route('assignments.download', $assignment) }}" class="text-blue-600 dark:text-blue-400 underline">
                                            {{ $assignment->file_name ?? 'Download' }}
                                        </a>
                                    </p>
                                @endif

                                <div class="flex gap-2 mt-1">
                                    <button type="submit" class="text-green-600 hover:underline font-medium">
                                        Save
                                    </button>

                                    <button type="button" onclick="cancelAssignmentEdit({{ $assignment->id }})" class="text-gray-500 hover:underline font-medium">
                                        Cancel
                                    </button>
                                </div>
                            </form>

                            <!-- Action Buttons -->
                            <div class="flex gap-2 mt-3 assignment-actions">
                                <button type="button" onclick="editAssignment({{ $assignment->id }})" class="text-blue-500 hover:underline font-medium">
                                    Edit
                                </button>

                                <form action="{{ route('assignments.destroy', $assignment) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this assignment?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="text-red-500 hover:underline font-medium">Delete</button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <!-- Teacher: Submissions Grading -->
                    @if(auth()->user()->role === 'teacher')
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-200">Student Submissions</h2>
                        <div class="space-y-4">
                            @forelse($assignment->submissions as $submission)
                                <div class="rounded-lg">
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium text-gray-900 dark:text-gray-200">{{ $submission->student->username }}</span>
                                        <span class="text-gray-500 dark:text-gray-400 text-sm">{{ $submission->file_name }}</span>
                                    </div>

                                    <a href="{{ route('submissions.download', $submission) }}" class="text-blue-600 dark:text-blue-400 underline mt-1 inline-block">
                                        Download
                                    </a>

                                    <form method="POST" action="{{ route('submissions.grade', $submission) }}" class="mt-3 space-y-2">
                                        @csrf

                                        <input type="number" min="1" max="10" name="grade" placeholder="Grade" value="{{ $submission->grade }}" class="w-full sm:w-24 border rounded-md px-2 py-1 focus:ring-2 focus:ring-blue-200 focus:outline-none dark:bg-gray-700 dark:text-gray-200"><br>
                                        <textarea name="feedback" placeholder="Feedback" rows="3" class="w-full sm:w-1/2 border rounded-md px-2 py-1 focus:ring-2 focus:ring-blue-200 focus:outline-none dark:bg-gray-700 dark:text-gray-200">{{ $submission->feedback }}</textarea><br>
                                        
                                        <button type="submit" class="text-green-600 hover:underline font-medium">
                                            Save
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <p class="text-gray-900 dark:text-gray-200">No submissions yet.</p>
                            @endforelse
                        </div>
                    @endif

                    <!-- Student: Submit Assignment -->
                    @if(auth()->user()->role === 'student')
                        @php
                            $mySubmission = $assignment->submissions->where('student_id', auth()->id())->first();
                        @endphp

                        @if($mySubmission)
                            <div class="rounded-lg text-gray-900 dark:text-gray-200">
                                <strong>Your Submission:</strong> {{ $mySubmission->file_name ?? 'N/A' }}

                                <div class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    Grade: {{ $mySubmission->grade ?? 'Not graded yet' }}<br>
                                    Feedback: <span class="whitespace-pre-line">{{ $mySubmission->feedback ?? 'No feedback yet' }}</span>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-2 mt-2">
                                    <a href="{{ route('submissions.download', $mySubmission) }}" class="text-blue-600 dark:text-blue-400 underline">
                                        Download
                                    </a>

                                    <form method="POST" action="{{ route('submissions.destroy', $mySubmission) }}" onsubmit="return confirm('Are you sure you want to delete this submission?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="text-red-500 hover:underline">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <form method="POST" action="{{ route('submissions.store', $assignment) }}" enctype="multipart/form-data" class="p-4 rounded-lg flex flex-col gap-3 max-w-md dark:text-gray-200">
                                @csrf

                                <input type="file" name="file" required class="border rounded-md px-2 py-1 focus:ring-2 focus:ring-blue-200 focus:outline-none dark:bg-gray-700 dark:text-gray-200">
                                
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-md text-sm shadow">
                                    Submit Assignment
                                </button>
                            </form>
                        @endif
                    @endif

                    <!-- Comments Section -->
                    <hr class="my-8 border-gray-300 dark:border-gray-600">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-200">Comments</h2>

                    <div class="space-y-4">
                        @foreach($assignment->comments as $comment)
                            <div class="rounded-lg" id="comment-{{ $comment->id }}">
                                <div class="flex items-center gap-2">
                                    <strong class="text-gray-900 dark:text-gray-200">{{ $comment->user->username }}</strong>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>

                                <p class="mt-2 comment-text text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $comment->body }}</p>

                                @if(auth()->id() === $comment->user_id)
                                    <form method="POST" action="{{ route('comments.update', $comment) }}" class="comment-edit-form hidden">
                                        @csrf
                                        @method('PATCH')
                                        
                                        <textarea name="body" rows="3" class="w-full sm:w-1/2 border rounded-md px-2 py-1 focus:ring-2 focus:ring-blue-200 focus:outline-none dark:bg-gray-700 dark:text-gray-200 max-h-40 resize-y overflow-auto">{{ $comment->body }}</textarea>
                                        
                                        <div class="flex gap-2 mt-1">
                                            <button type="submit" class="text-green-600 hover:underline font-medium">
                                                Save
                                            </button>

                                            <button type="button" onclick="cancelEdit({{ $comment->id }})" class="text-gray-500 hover:underline font-medium">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>

                                    <div class="flex gap-2 mt-2 comment-actions">
                                        <button type="button" onclick="editComment({{ $comment->id }})" class="text-blue-500 hover:underline font-medium">
                                            Edit
                                        </button>
                                        
                                        <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this comment?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="text-red-500 hover:underline font-medium">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Student Comment Form -->
                    @if(auth()->user()->role === 'student')
                        <form method="POST" action="{{ route('comments.store', $assignment) }}" class="rounded-lg flex flex-col gap-2 max-w-md dark:text-gray-200">
                            @csrf

                            <textarea name="body" rows="3" required placeholder="Write a comment..." class="w-full border rounded-md px-2 py-1 focus:ring-2 focus:ring-blue-200 focus:outline-none dark:bg-gray-700 dark:text-gray-200"></textarea>
                            
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-md text-sm shadow">
                                Post Comment
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Assignment edit toggle
        function editAssignment(id) {
            const container = document.getElementById(`assignment-${id}`);
            container.querySelector('.assignment-title').style.display = 'none';
            container.querySelector('.assignment-description').style.display = 'none';
            const dueEl = container.querySelector('.assignment-due-date');
            if(dueEl) dueEl.style.display = 'none';
            const fileEl = container.querySelector('.assignment-file');
            if(fileEl) fileEl.style.display = 'none';
            container.querySelector('.assignment-actions').style.display = 'none';
            container.querySelector('.assignment-edit-form').classList.remove('hidden');
        }

        function cancelAssignmentEdit(id) {
            const container = document.getElementById(`assignment-${id}`);
            container.querySelector('.assignment-title').style.display = 'block';
            container.querySelector('.assignment-description').style.display = 'block';
            const dueEl = container.querySelector('.assignment-due-date');
            if(dueEl) dueEl.style.display = 'block';
            const fileEl = container.querySelector('.assignment-file');
            if(fileEl) fileEl.style.display = 'block';
            container.querySelector('.assignment-actions').style.display = 'flex';
            container.querySelector('.assignment-edit-form').classList.add('hidden');
        }

        // Comment edit toggle
        function editComment(id) {
            const container = document.getElementById(`comment-${id}`);
            container.querySelector('.comment-text').style.display = 'none';
            container.querySelector('.comment-actions').style.display = 'none';
            container.querySelector('.comment-edit-form').classList.remove('hidden');
        }

        function cancelEdit(id) {
            const container = document.getElementById(`comment-${id}`);
            container.querySelector('.comment-text').style.display = 'block';
            container.querySelector('.comment-actions').style.display = 'flex';
            container.querySelector('.comment-edit-form').classList.add('hidden');
        }
    </script>
</x-app-layout>
