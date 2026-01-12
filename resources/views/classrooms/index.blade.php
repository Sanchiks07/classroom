<x-app-layout>
    <div class="py-12 page-enter">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card-enhanced overflow-hidden sm:rounded-xl p-8">
                <div class="container mx-auto p-6">

                    @php
                        $colorSets = [
                            ['bg-red-400','text-red-900','dark:bg-red-500','dark:text-white'],
                            ['bg-orange-400','text-orange-900','dark:bg-orange-500','dark:text-white'],
                            ['bg-amber-400','text-amber-900','dark:bg-amber-500','dark:text-white'],
                            ['bg-yellow-400','text-yellow-900','dark:bg-yellow-500','dark:text-white'],
                            ['bg-lime-400','text-lime-900','dark:bg-lime-500','dark:text-white'],
                            ['bg-green-400','text-green-900','dark:bg-green-500','dark:text-white'],
                            ['bg-emerald-400','text-emerald-900','dark:bg-emerald-500','dark:text-white'],
                            ['bg-teal-400','text-teal-900','dark:bg-teal-500','dark:text-white'],
                            ['bg-cyan-400','text-cyan-900','dark:bg-cyan-500','dark:text-white'],
                            ['bg-sky-400','text-sky-900','dark:bg-sky-500','dark:text-white'],
                            ['bg-blue-400','text-blue-900','dark:bg-blue-500','dark:text-white'],
                            ['bg-indigo-400','text-indigo-900','dark:bg-indigo-500','dark:text-white'],
                            ['bg-violet-400','text-violet-900','dark:bg-violet-500','dark:text-white'],
                            ['bg-purple-400','text-purple-900','dark:bg-purple-500','dark:text-white'],
                            ['bg-fuchsia-400','text-fuchsia-900','dark:bg-fuchsia-500','dark:text-white'],
                            ['bg-pink-400','text-pink-900','dark:bg-pink-500','dark:text-white'],
                            ['bg-rose-400','text-rose-900','dark:bg-rose-500','dark:text-white'],
                            ['bg-slate-400','text-slate-900','dark:bg-slate-500','dark:text-white'],
                        ];
                    @endphp

                    <!-- Teacher View -->
                    @if(auth()->user()->role === 'teacher')
                        <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-200">Your Classrooms</h1>

                        <form method="POST" action="{{ route('classrooms.store') }}" class="mb-8 flex flex-wrap gap-3 items-start">
                            @csrf
                            <input type="text" name="name" placeholder="Classroom name" 
                                   class="input-enhanced w-full sm:w-64" required>
                            <button type="submit" class="btn-primary w-full sm:w-auto">
                                Create Classroom
                            </button>
                        </form>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @forelse($classrooms as $classroom)
                                @php
                                    $colorIndex = ($classroom->id - 1) % count($colorSets);
                                    $cardClasses = implode(' ', $colorSets[$colorIndex]);
                                @endphp

                                <div id="classroom-{{ $classroom->id }}" class="classroom-card {{ $cardClasses }} shadow-md rounded-lg p-6 flex flex-col items-center break-words relative">
                                    <div class="corner-accent"></div>
                                    
                                    <div class="classroom-name text-center w-full mb-3">
                                        <a href="{{ route('classrooms.show', $classroom) }}" class="font-bold text-xl hover:underline break-words">
                                            {{ $classroom->name }}
                                        </a>
                                    </div>

                                    <div class="text-sm opacity-75 mb-2">
                                        <span class="font-medium">Teacher:</span> {{ $classroom->teacher->username }}
                                    </div>

                                    <div class="text-sm opacity-75 mb-4">
                                        <span class="font-medium">Assignments:</span> {{ $classroom->assignments_count }}
                                    </div>

                                    <form method="POST" action="{{ route('classrooms.update', $classroom) }}" class="mt-4 classroom-edit-form hidden flex flex-col gap-2 w-full">
                                        @csrf
                                        @method('PUT')

                                        <input type="text" name="name" value="{{ $classroom->name }}" class="border rounded px-2 py-1 w-full sm:w-full focus:outline-none focus:ring-2 focus:ring-blue-400 dark:bg-gray-700 dark:text-gray-200">
                                        <div class="flex justify-center flex-wrap gap-2 mt-2">
                                            <button type="submit" class="bg-white/80 dark:bg-gray-800/80 px-2 py-1 rounded text-green-600 hover:underline font-semibold">
                                                Save
                                            </button>

                                            <button type="button" onclick="cancelClassroomEdit({{ $classroom->id }})" class="bg-white/80 dark:bg-gray-800/80 px-2 py-1 rounded text-gray-500 hover:underline dark:text-gray-300">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>

                                    <div class="mt-4 flex justify-center flex-wrap gap-2 classroom-actions">
                                        <button type="button" onclick="editClassroom({{ $classroom->id }})" 
                                                class="bg-white/80 dark:bg-gray-800/80 px-2 py-1 rounded text-blue-500 hover:underline font-semibold">
                                            Edit
                                        </button>

                                        <form action="{{ route('classrooms.destroy', $classroom) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this classroom?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="bg-white/80 dark:bg-gray-800/80 px-2 py-1 rounded text-red-500 hover:underline font-semibold">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-900 dark:text-gray-200">No classrooms yet.</p>
                            @endforelse
                        </div>
                    @endif

                    <!-- Student View -->
                    @if(auth()->user()->role === 'student')
                        <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-200">Your Classrooms</h1>

                        <form method="POST" action="{{ route('classrooms.join') }}" class="mb-8 flex flex-wrap gap-3 items-start">
                            @csrf

                            <input type="text" name="code" placeholder="Classroom code" class="input-enhanced w-full sm:w-64" required>
                            <button type="submit" class="btn-primary w-full sm:w-auto">
                                Join Classroom
                            </button>
                        </form>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @forelse($classrooms as $classroom)
                                @php
                                    $colorIndex = ($classroom->id - 1) % count($colorSets);
                                    $cardClasses = implode(' ', $colorSets[$colorIndex]);
                                @endphp
                                
                                <div class="classroom-card {{ $cardClasses }} shadow-md rounded-lg p-6 flex flex-col items-center break-words relative">
                                    <div class="corner-accent"></div>
                                    <a href="{{ route('classrooms.show', $classroom) }}" class="font-bold text-xl hover:underline text-center break-words mb-3">
                                        {{ $classroom->name }}
                                    </a>

                                    <div class="text-sm opacity-75 mb-2">
                                        <span class="font-medium">Teacher:</span> {{ $classroom->teacher->username }}
                                    </div>

                                    <div class="text-sm opacity-75">
                                        <span class="font-medium">Assignments:</span> {{ $classroom->assignments_count }}
                                    </div>
                                        {{ $classroom->name }}
                                    </a>
                                </div>
                            @empty
                                <p class="text-gray-900 dark:text-gray-200">You are not in any classrooms yet.</p>
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
            container.querySelector('.classroom-edit-form').style.display = 'flex';
        }

        function cancelClassroomEdit(id) {
            const container = document.getElementById(`classroom-${id}`);
            container.querySelector('.classroom-name').style.display = 'block';
            container.querySelector('.classroom-actions').style.display = 'flex';
            container.querySelector('.classroom-edit-form').style.display = 'none';
        }
    </script>
</x-app-layout>
