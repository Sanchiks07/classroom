<x-app-layout>
    <div class="py-12 max-w-7xl mx-auto">
        <!-- Teacher View -->
        @if(auth()->user()->role === 'teacher')
            <h2 class="text-xl font-bold mb-4">Your Classrooms</h2>

            <form method="POST" action="{{ route('classrooms.store') }}" class="mb-6">
                @csrf

                <input type="text" name="name" placeholder="Classroom name" required>

                <button type="submit">Create Classroom</button>
            </form>

            @forelse($classrooms as $classroom)
                <div>
                    <a href="{{ route('classrooms.show', $classroom) }}">
                        {{ $classroom->name }}
                    </a>
                </div>
            @empty
                <p>No classrooms yet.</p>
            @endforelse
        @endif

        <!-- Student View -->
        @if(auth()->user()->role === 'student')
            <h2 class="text-xl font-bold mb-4">Your Classrooms</h2>

            <!-- Join Classroom Form -->
            <form method="POST" action="{{ route('classrooms.join') }}" class="mb-6">
                @csrf

                <input type="text" name="code" placeholder="Classroom code" required>
                
                <button type="submit">Join Classroom</button>
            </form>

            @forelse($classrooms as $classroom)
                <div>
                    <a href="{{ route('classrooms.show', $classroom) }}">
                        {{ $classroom->name }}
                    </a>
                </div>
            @empty
                <p>You are not in any classrooms yet.</p>
            @endforelse
        @endif
    </div>
</x-app-layout>
