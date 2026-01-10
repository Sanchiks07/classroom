<x-app-layout>
    <div class="py-12 max-w-7xl mx-auto">
        <h1 class="font-bold mb-4">{{ $classroom->name }}</h1>

        <p class="mb-2"><strong>Teacher:</strong> {{ $classroom->teacher->username }}</p>

        <!-- Classroom Code -->
        @if(auth()->user()->role === 'teacher')
            <p class="mb-4">
                Classroom code: 
                <span class="font-allerta">
                    {{ $classroom->code }}
                </span>
            </p>
        @endif

        <hr class="my-8">

        <nav class="mb-6">
            <ul class="flex gap-6 text-sm">
                <li>
                    <a href="{{ route('classrooms.show', [$classroom, 'tab' => 'assignments']) }}"
                        class="pb-2 border-b-2 
                            {{ request('tab', 'assignments') === 'assignments'
                                ? 'border-black font-semibold'
                                : 'border-transparent text-gray-500 hover:text-black' }}"
                    >
                        Assignments
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('classrooms.show', [$classroom, 'tab' => 'students']) }}"
                        class="pb-2 border-b-2 
                            {{ request('tab') === 'students'
                                ? 'border-black font-semibold'
                                : 'border-transparent text-gray-500 hover:text-black' }}"
                    >
                        Students
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Classroom Assignments -->
        @if(request('tab', 'assignments') === 'assignments')
            <h2 class="text-xl font-semibold mt-6 mb-2">Assignments</h2>

            <!-- Teacher: Assignment Create Form -->
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
        @endif
        
        <!-- Classroom Students -->
        @if(request('tab') === 'students')
            <h2 class="text-xl font-semibold mt-6 mb-2">Students</h2>

            @forelse($classroom->students as $student)
                <div>{{ $student->username }}</div>
            @empty
                <p>No students yet.</p>
            @endforelse
        @endif
    </div>
</x-app-layout>
