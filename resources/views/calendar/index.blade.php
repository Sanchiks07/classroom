<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-6">

                <h1 class="text-3xl font-extrabold mb-6 text-center text-gray-800 dark:text-gray-100">
                    {{ \Carbon\Carbon::create($year, $month)->format('F Y') }}
                </h1>

                <div class="flex justify-between mb-6 flex-wrap gap-2">
                    <a href="{{ route('calendar.index', ['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}"
                    class="px-5 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600 transition text-center">
                    Prev
                    </a>
                    <a href="{{ route('calendar.index', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}"
                    class="px-5 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600 transition text-center">
                    Next
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-7 gap-2 border border-gray-300 rounded-lg overflow-hidden dark:bg-gray-800">
                    <!-- Weekday Header -->
                    @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $dayName)
                        <div class="text-center font-bold py-2 border-b border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm sm:text-base">
                            {{ $dayName }}
                        </div>
                    @endforeach

                    <!-- Calendar Days -->
                    @foreach($days as $day)
                        @php
                            $currentDate = $day->format('Y-m-d');
                            $assignmentsForDay = $assignments[$currentDate] ?? [];
                            $isCurrentMonth = $day->month == $month;
                        @endphp

                        <div class="border rounded-lg min-h-[120px] p-2 flex flex-col
                                    {{ $isCurrentMonth ? 'bg-white dark:bg-gray-800' : 'bg-gray-100 dark:bg-gray-900/50 text-gray-400 dark:text-gray-500' }}
                                    {{ $currentDate == $today ? 'ring-2 ring-blue-500 dark:ring-blue-500 shadow-md' : '' }}">
                            <div class="font-semibold mb-1 text-gray-800 dark:text-gray-100 text-sm sm:text-base">{{ $day->day }}</div>

                            <!-- Assignments -->
                            <div class="flex flex-col gap-1 overflow-hidden">
                                @foreach($assignmentsForDay as $assignment)
                                    @php
                                        $classroomId = $assignment['classroom_id'];
                                        $colorClass = $colors[$classroomId % count($colors)];
                                    @endphp
                                    <div class="{{ $colorClass }} text-xs sm:text-sm rounded px-1 py-0.5 truncate">
                                        {{ $assignment['title'] }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
