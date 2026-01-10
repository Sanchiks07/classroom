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
                <div class="mb-4 p-2 border rounded" id="classroom-{{ $classroom->id }}">
                    <div>
                        <a href="{{ route('classrooms.show', $classroom) }}" class="font-bold classroom-name">
                            {{ $classroom->name }}
                        </a>
                    </div>

                    <!-- Edit Form (hidden by default) -->
                    <form method="POST" action="{{ route('classrooms.update', $classroom) }}" class="mt-2 classroom-edit-form" style="display: none;">
                        @csrf
                        @method('PUT')

                        <input type="text" name="name" value="{{ $classroom->name }}" class="border rounded px-1 py-0.5 w-full">

                        <div class="mt-1 space-x-2">
                            <button type="submit" class="text-green-500 hover:underline">Save</button>
                            <button type="button" onclick="cancelClassroomEdit({{ $classroom->id }})" class="text-gray-500 hover:underline">Cancel</button>
                        </div>
                    </form>

                    <!-- Action Buttons -->
                    <div class="mt-2 classroom-actions space-x-2">
                        <button type="button" onclick="editClassroom({{ $classroom->id }})" class="edit-btn text-blue-500 hover:underline">Edit</button>

                        <form action="{{ route('classrooms.destroy', $classroom) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="text-red-500 hover:underline">Delete</button>
                        </form>
                    </div>
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

    <script>
        function editClassroom(id) {
            const container = document.getElementById(`classroom-${id}`);
            container.querySelector('.classroom-name').style.display = 'none';
            container.querySelector('.classroom-actions').style.display = 'none';
            container.querySelector('.classroom-edit-form').style.display = 'block';
        }

        function cancelClassroomEdit(id) {
            const container = document.getElementById(`classroom-${id}`);
            container.querySelector('.classroom-name').style.display = 'block';
            container.querySelector('.classroom-actions').style.display = 'block';
            container.querySelector('.classroom-edit-form').style.display = 'none';
        }
        </script>
</x-app-layout>
