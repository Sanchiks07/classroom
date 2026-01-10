<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container mx-auto p-6">
                    <!-- Teacher View -->
                    @if(auth()->user()->role === 'teacher')
                        <h1 class="text-2xl font-bold mb-6">Your Classrooms</h1>

                        <!-- Teacher Create Classroom Form -->
                        <form method="POST" action="{{ route('classrooms.store') }}" class="mb-6 flex flex-wrap gap-2 items-start">
                            @csrf

                            <input type="text" name="name" placeholder="Classroom name" class="border rounded px-3 py-2 w-full sm:w-64 focus:outline-none focus:ring-2 focus:ring-blue-200" required>

                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded shadow w-full sm:w-auto">
                                Create Classroom
                            </button>
                        </form>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @forelse($classrooms as $classroom)
                                <div id="classroom-{{ $classroom->id }}" class="bg-white shadow-md rounded-lg p-4 flex flex-col items-center break-words">
                                    <!-- Classroom Name -->
                                    <div class="classroom-name text-center">
                                        <a href="{{ route('classrooms.show', $classroom) }}" class="font-bold hover:underline text-lg break-words">
                                            {{ $classroom->name }}
                                        </a>
                                    </div>

                                    <!-- Teacher: Edit Form (hidden by default) -->
                                    <form method="POST" action="{{ route('classrooms.update', $classroom) }}" class="mt-4 classroom-edit-form hidden flex flex-col gap-2 w-full">
                                        @csrf
                                        @method('PUT')

                                        <input type="text" name="name" value="{{ $classroom->name }}" class="border rounded px-2 py-1 w-full focus:outline-none focus:ring-2 focus:ring-blue-400">

                                        <div class="flex justify-center flex-wrap gap-2 mt-4">
                                            <button type="submit" class="text-green-600 hover:underline font-semibold">
                                                Save
                                            </button>

                                            <button type="button" onclick="cancelClassroomEdit({{ $classroom->id }})" class="text-gray-500 hover:underline">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>

                                    <!-- Action Buttons-->
                                    <div class="mt-4 flex justify-center flex-wrap gap-2 classroom-actions">
                                        <button type="button" onclick="editClassroom({{ $classroom->id }})" class="edit-btn text-blue-500 hover:underline font-semibold">
                                            Edit
                                        </button>

                                        <form action="{{ route('classrooms.destroy', $classroom) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this classroom?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="text-red-500 hover:underline font-semibold">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <p>No classrooms yet.</p>
                            @endforelse
                        </div>
                    @endif

                    <!-- Student View -->
                    @if(auth()->user()->role === 'student')
                        <h1 class="text-2xl font-bold mb-6">Your Classrooms</h1>

                        <!-- Join Classroom Form -->
                        <form method="POST" action="{{ route('classrooms.join') }}" class="mb-6 flex flex-wrap gap-2 items-start">
                            @csrf

                            <input type="text" name="code" placeholder="Classroom code" class="border rounded px-3 py-2 w-full sm:w-64 focus:outline-none focus:ring-2 focus:ring-blue-200" required>

                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded shadow w-full sm:w-auto">
                                Join Classroom
                            </button>
                        </form>

                        <!-- Classroom Cards -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @forelse($classrooms as $classroom)
                                <div class="bg-white shadow-md rounded-lg p-4 flex flex-col items-center break-words">
                                    <a href="{{ route('classrooms.show', $classroom) }}" class="font-bold text-lg hover:underline text-center break-words">
                                        {{ $classroom->name }}
                                    </a>
                                </div>
                            @empty
                                <p>You are not in any classrooms yet.</p>
                            @endforelse
                        </div>
                    @endif
                </div>
            </div>
        </div>
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
