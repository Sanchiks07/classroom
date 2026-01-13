<x-app-layout>
    <div class="py-6 sm:py-12 page-enter">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="card-enhanced shadow-xl rounded-2xl p-4 sm:p-6 lg:p-8">

                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold mb-4 sm:mb-6 text-center text-gray-800 dark:text-gray-100">
                    {{ \Carbon\Carbon::create($year, $month)->format('F Y') }}
                </h1>

                <div class="flex justify-between items-center mb-4 sm:mb-6 flex-wrap gap-2 sm:gap-3">
                    <a href="{{ route('calendar.index', ['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}" class="btn-primary px-4 py-2 sm:px-6 sm:py-3 text-sm sm:text-base flex-1 sm:flex-initial min-w-[100px] text-center">
                        ← Previous
                    </a>
                    
                    <a href="{{ route('calendar.index') }}" class="btn-secondary px-4 py-2 sm:px-6 sm:py-3 text-sm sm:text-base hidden sm:inline-block">
                        Today
                    </a>
                    
                    <a href="{{ route('calendar.index', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}" class="btn-primary px-4 py-2 sm:px-6 sm:py-3 text-sm sm:text-base flex-1 sm:flex-initial min-w-[100px] text-center">
                        Next →
                    </a>
                </div>

                <!-- Calendar Grid View -->
                <div class="grid grid-cols-7 gap-1 sm:gap-2 lg:gap-3 border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden">
                    <!-- Weekday Header -->
                    @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $dayName)
                        <div class="text-center font-bold py-2 sm:py-3 border-b border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-900/50 text-[10px] sm:text-sm lg:text-base">
                            <span class="hidden md:inline">{{ $dayName }}</span>
                            <span class="md:hidden">{{ substr($dayName, 0, 1) }}</span>
                        </div>
                    @endforeach

                    <!-- Calendar Days -->
                    @foreach($days as $day)
                        @php
                            $currentDate = $day->format('Y-m-d');
                            $assignmentsForDay = $assignments[$currentDate] ?? [];
                            $isCurrentMonth = $day->month == $month;
                        @endphp

                        <div class="border border-gray-200 dark:border-gray-700 rounded min-h-[60px] sm:min-h-[100px] lg:min-h-[140px] p-1 sm:p-2 lg:p-3 flex flex-col
                                    {{ $isCurrentMonth ? 'bg-white dark:bg-gray-800' : 'bg-gray-100 dark:bg-gray-900/50 text-gray-400 dark:text-gray-500' }}
                                    {{ $currentDate == $today ? 'ring-2 ring-blue-500 dark:ring-blue-400 shadow-lg bg-blue-50 dark:bg-blue-900/20' : '' }}
                                    hover:shadow-md transition-shadow duration-200">
                            <div class="font-semibold mb-1 text-gray-800 dark:text-gray-100 text-xs sm:text-sm lg:text-base">{{ $day->day }}</div>

                            <!-- Assignments -->
                            <div class="flex flex-col gap-0.5 sm:gap-1 overflow-y-auto max-h-[40px] sm:max-h-[100px] lg:max-h-[120px] scrollbar-thin">
                                @foreach($assignmentsForDay as $assignment)
                                    @php
                                        $classroomId = $assignment['classroom_id'];
                                        $colorClass = $colors[$classroomId % count($colors)];
                                    @endphp

                                    <a
                                        href="{{ route('assignments.show', $assignment['id']) }}"
                                        class="{{ $colorClass }} block text-[8px] sm:text-xs lg:text-sm rounded px-1 sm:px-2 py-0.5 sm:py-1 truncate shadow-sm hover:shadow-md transition-shadow cursor-pointer"
                                        title="{{ $assignment['title'] }} - {{ $assignment['classroom_name'] ?? '' }}"
                                    >
                                        <div class="font-medium truncate">
                                            {{ $assignment['title'] }}
                                        </div>

                                        @if(isset($assignment['classroom_name']))
                                            <div class="text-[7px] sm:text-[10px] lg:text-xs opacity-75 truncate hidden sm:block">
                                                {{ $assignment['classroom_name'] }}
                                            </div>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Mobile Assignment List -->
                <div class="sm:hidden mt-6 space-y-3">
                    <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-3">Assignments This Month</h2>
                    @php $mobileHasAssignments = false; @endphp

                    @foreach($days as $day)
                        @php
                            $currentDate = $day->format('Y-m-d');
                            $assignmentsForDay = $assignments[$currentDate] ?? [];
                            $isCurrentMonth = $day->month == $month;
                        @endphp

                        @if($isCurrentMonth && count($assignmentsForDay) > 0)
                            @php $mobileHasAssignments = true; @endphp
                            <div class="card-enhanced rounded-lg p-4 {{ $currentDate == $today ? 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/20' : '' }}">
                                <div class="font-bold text-base mb-3 text-gray-800 dark:text-gray-100">
                                    {{ $day->format('D, M j') }}

                                    @if($currentDate == $today)
                                        <span class="text-xs bg-blue-500 text-white px-2 py-1 rounded-full ml-2">Today</span>
                                    @endif
                                </div>
                                
                                <div class="space-y-2">
                                    @foreach($assignmentsForDay as $assignment)
                                        @php
                                            $classroomId = $assignment['classroom_id'];
                                            $colorClass = $colors[$classroomId % count($colors)];
                                        @endphp

                                        <div class="{{ $colorClass }} rounded-lg p-3 shadow-sm">
                                            <div class="font-semibold text-sm">{{ $assignment['title'] }}</div>

                                            @if(isset($assignment['classroom_name']))
                                                <div class="text-xs opacity-75 mt-1">{{ $assignment['classroom_name'] }}</div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach

                    @php
                        $hasAssignments = false;
                        foreach($days as $day) {
                            if ($day->month == $month && count($assignments[$day->format('Y-m-d')] ?? []) > 0) {
                                $hasAssignments = true;
                                break;
                            }
                        }
                    @endphp

                    @if(!$hasAssignments)
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            No assignments this month
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
