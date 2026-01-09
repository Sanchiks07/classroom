<x-app-layout>
    <div class="py-12 max-w-7xl mx-auto">
        <h1 class="mb-4">{{ $classroom->name }}</h1>

        <p class="mb-2">Teacher: {{ $classroom->teacher->username }}</p>

        @if(auth()->user()->role === 'teacher')
            <p class="mb-4">
                Classroom code: 
                <span class="font-allerta">
                    {{ $classroom->code }}
                </span>
            </p>
        @endif

        <h2 class="text-xl font-semibold mt-6 mb-2">Students</h2>

        @forelse($classroom->students as $student)
            <div>
                {{ $student->username }}
            </div>
        @empty
            <p>No students yet.</p>
        @endforelse

        <h2 class="text-xl font-semibold mt-6 mb-2">Assignments</h2>

        <!-- Teacher: Create New Assignment (with file upload) -->
        @if(auth()->user()->role === 'teacher')
            <form method="POST" action="{{ route('assignments.store', $classroom) }}" enctype="multipart/form-data" class="mb-4">
                @csrf
                
                <input type="text" name="title" placeholder="Assignment Title" required>
                <textarea name="description" placeholder="Description" required></textarea>
                <input type="file" name="file">
                
                <button type="submit">Create Assignment</button>
            </form>
        @endif

        <!-- List All Assignments -->
        @forelse($classroom->assignments as $assignment)
            <div>
                <a href="{{ route('assignments.show', $assignment) }}">
                    {{ $assignment->title }}
                </a>
            </div>
        @empty
            <p>No assignments yet.</p>
        @endforelse
    </div>
</x-app-layout>
