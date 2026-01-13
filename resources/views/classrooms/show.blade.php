<x-app-layout>
    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg sm:rounded-lg">
                <div class="container mx-auto p-4 sm:p-6">
                    <!-- Errors -->
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <h1 class="text-2xl font-bold mb-3 assignment-title text-gray-900 dark:text-gray-200 break-words">{{ $classroom->name }}</h1>

                    <p class="mb-2 sm:text-base text-gray-900 dark:text-gray-200">
                        <strong>Teacher:</strong> {{ $classroom->teacher->username }}
                    </p>

                    @if(auth()->user()->role === 'teacher')
                        <p class="mb-4 sm:text-base break-all text-gray-900 dark:text-gray-200">
                            <strong>Classroom code:</strong> 

                            <span class="font-allerta">
                                {{ $classroom->code }}
                            </span>
                        </p>
                    @endif

                    <hr class="my-6 sm:my-8 border-gray-300 dark:border-gray-600">

                    <!-- Navigation -->
                    <nav class="mb-6">
                        <ul class="flex gap-6 text-sm whitespace-nowrap">
                            <li>
                                <a href="{{ route('classrooms.show', [$classroom, 'tab' => 'assignments']) }}"
                                   class="pb-2 border-b-2 
                                   {{ request('tab', 'assignments') === 'assignments'
                                        ? 'border-black font-semibold text-gray-900 dark:text-gray-100'
                                        : 'border-transparent text-gray-500 hover:text-black dark:text-gray-400 dark:hover:text-gray-100' }}">
                                    Assignments
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('classrooms.show', [$classroom, 'tab' => 'students']) }}"
                                   class="pb-2 border-b-2 
                                   {{ request('tab') === 'students'
                                        ? 'border-black font-semibold text-gray-900 dark:text-gray-100'
                                        : 'border-transparent text-gray-500 hover:text-black dark:text-gray-400 dark:hover:text-gray-100' }}">
                                    Students
                                </a>
                            </li>
                        </ul>
                    </nav>

                    <!-- Assignments -->
                    @if(request('tab', 'assignments') === 'assignments')
                        <h2 class="text-lg mb-4 sm:text-xl font-semibold text-gray-900 dark:text-gray-200">Assignments</h2>

                        @if(auth()->user()->role === 'teacher')
                            <form method="POST"
                                  action="{{ route('assignments.store', $classroom) }}"
                                  enctype="multipart/form-data"
                                  class="mb-5 mt-5 rounded-lg space-y-3 w-full sm:max-w-md">
                                @csrf

                                <h3 class="text-base sm:text-lg font-semibold text-gray-800 dark:text-gray-100 mb-1">
                                    Create Assignment
                                </h3>

                                <input type="text"
                                       name="title"
                                       placeholder="Enter assignment title"
                                       class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:bg-gray-700 dark:text-gray-200"
                                       required>

                                <textarea name="description"
                                          placeholder="Enter assignment description"
                                          class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200 resize-none h-20 dark:bg-gray-700 dark:text-gray-200"
                                          required></textarea>

                                <input type="date"
                                       name="due_date"
                                       class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:bg-gray-700 dark:text-gray-200">

                                <input type="file"
                                       name="file_path"
                                       class="w-full text-sm text-gray-600 dark:text-gray-300">

                                <button type="submit"
                                        class="w-full sm:w-auto bg-blue-500 hover:bg-blue-600 text-white font-semibold px-3 py-1.5 rounded-md shadow">
                                    Create Assignment
                                </button>
                            </form>
                        @endif

                        @forelse($classroom->assignments as $assignment)
                            <div class="bg-white dark:bg-gray-700 shadow-md rounded-lg p-4 mb-4 hover:shadow-lg transition-shadow">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-1">
                                    <a href="{{ route('assignments.show', $assignment) }}"
                                       class="font-semibold text-base sm:text-lg hover:underline break-words text-gray-900 dark:text-gray-100">
                                        {{ $assignment->title }}
                                    </a>

                                    @if($assignment->due_date)
                                        <span class="text-xs sm:text-sm text-gray-500 dark:text-gray-300">
                                            Due: {{ $assignment->due_date->format('F j, Y') }}
                                        </span>
                                    @endif
                                </div>

                                @if($assignment->description)
                                    <p class="text-gray-700 dark:text-gray-200 mt-2 text-sm sm:text-base break-words">
                                        {{ Str::limit($assignment->description, 120) }}
                                    </p>
                                @endif
                            </div>
                        @empty
                            <p class="text-gray-900 dark:text-gray-200">No assignments yet.</p>
                        @endforelse
                    @endif

                    <!-- Students -->
                    @if(request('tab') === 'students')
                        <h2 class="text-lg sm:text-xl font-semibold mt-6 mb-4 text-gray-900 dark:text-gray-200">Students</h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            @forelse($classroom->students as $student)
                                <div class="bg-white dark:bg-gray-700 shadow-sm rounded-lg p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
                                    <img src="{{ $student->profile_photo_path ? asset('storage/' . $student->profile_photo_path) : asset('images/default-avatar.png') }}"
                                         alt="{{ $student->username }}"
                                         class="w-9 h-9 sm:w-10 sm:h-10 rounded-full object-cover flex-shrink-0 bg-white p-[2px]">

                                    <div class="text-gray-800 dark:text-gray-100 font-medium break-words">
                                        {{ $student->username }}
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-900 dark:text-gray-200">No students yet.</p>
                            @endforelse
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
