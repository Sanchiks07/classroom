<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg sm:rounded-lg">
                <div class="container mx-auto p-6">

                    @php
                        $colorSets = [
                            ['bg-red-100','text-red-800','dark:bg-red-600','dark:text-red-100'],
                            ['bg-orange-100','text-orange-800','dark:bg-orange-600','dark:text-orange-100'],
                            ['bg-amber-100','text-amber-800','dark:bg-amber-600','dark:text-amber-100'],
                            ['bg-yellow-100','text-yellow-800','dark:bg-yellow-600','dark:text-yellow-100'],
                            ['bg-lime-100','text-lime-800','dark:bg-lime-600','dark:text-lime-100'],
                            ['bg-green-100','text-green-800','dark:bg-green-600','dark:text-green-100'],
                            ['bg-emerald-100','text-emerald-800','dark:bg-emerald-600','dark:text-emerald-100'],
                            ['bg-teal-100','text-teal-800','dark:bg-teal-600','dark:text-teal-100'],
                            ['bg-cyan-100','text-cyan-800','dark:bg-cyan-600','dark:text-cyan-100'],
                            ['bg-sky-100','text-sky-800','dark:bg-sky-600','dark:text-sky-100'],
                            ['bg-blue-100','text-blue-800','dark:bg-blue-600','dark:text-blue-100'],
                            ['bg-indigo-100','text-indigo-800','dark:bg-indigo-600','dark:text-indigo-100'],
                            ['bg-violet-100','text-violet-800','dark:bg-violet-600','dark:text-violet-100'],
                            ['bg-purple-100','text-purple-800','dark:bg-purple-600','dark:text-purple-100'],
                            ['bg-fuchsia-100','text-fuchsia-800','dark:bg-fuchsia-600','dark:text-fuchsia-100'],
                            ['bg-pink-100','text-pink-800','dark:bg-pink-600','dark:text-pink-100'],
                            ['bg-rose-100','text-rose-800','dark:bg-rose-600','dark:text-rose-100'],
                            ['bg-slate-100','text-slate-800','dark:bg-slate-600','dark:text-slate-100'],
                        ];
                    @endphp

                    <!-- Teacher View -->
                    @if(auth()->user()->role === 'teacher')
                        <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-200">Your Classrooms</h1>

                        <form method="POST" action="{{ route('classrooms.store') }}" class="mb-6 flex flex-wrap gap-2 items-start">
                            @csrf
                            <input type="text" name="name" placeholder="Classroom name" 
                                   class="border rounded px-3 py-2 w-full sm:w-64 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:bg-gray-700 dark:text-gray-200" required>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded shadow w-full sm:w-auto">
                                Create Classroom
                            </button>
                        </form>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @forelse($classrooms as $classroom)
                                @php
                                    $colorIndex = ($classroom->id - 1) % count($colorSets);
                                    $cardClasses = implode(' ', $colorSets[$colorIndex]);
                                @endphp

                                <div id="classroom-{{ $classroom->id }}" class="{{ $cardClasses }} shadow-md rounded-lg p-4 flex flex-col items-center break-words">
                                    
                                    <div class="classroom-name text-center w-full">
                                        <a href="{{ route('classrooms.show', $classroom) }}" class="font-bold hover:underline text-lg break-words">
                                            {{ $classroom->name }}
                                        </a>
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

                        <form method="POST" action="{{ route('classrooms.join') }}" class="mb-6 flex flex-wrap gap-2 items-start">
                            @csrf

                            <input type="text" name="code" placeholder="Classroom code" class="border rounded px-3 py-2 w-full sm:w-64 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:bg-gray-700 dark:text-gray-200" required>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded shadow w-full sm:w-auto">
                                Join Classroom
                            </button>
                        </form>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @forelse($classrooms as $classroom)
                                @php
                                    $colorIndex = ($classroom->id - 1) % count($colorSets);
                                    $cardClasses = implode(' ', $colorSets[$colorIndex]);
                                @endphp
                                
                                <div class="{{ $cardClasses }} shadow-md rounded-lg p-4 flex flex-col items-center break-words">
                                    <a href="{{ route('classrooms.show', $classroom) }}" class="font-bold text-lg hover:underline text-center break-words">
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
