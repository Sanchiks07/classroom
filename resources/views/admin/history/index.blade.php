<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container mx-auto p-6">
                    <h1 class="text-2xl font-bold mb-8 text-center text-gray-800 dark:text-gray-200">Action Timeline</h1>

                    <div class="max-w-3xl mx-auto space-y-6">
                        @forelse($logs as $log)
                            <div class="flex flex-col sm:flex-row items-start sm:items-start space-y-2 sm:space-y-0 sm:space-x-4 break-words">
                                <!-- Timeline marker -->
                                <div class="flex-shrink-0 mt-1">
                                    <div class="w-4 h-4 bg-blue-400 rounded-full border-2 border-white shadow"></div>
                                </div>

                                <!-- Timeline content -->
                                <div class="flex-1 bg-gray-50 dark:bg-gray-700 rounded-lg p-4 shadow-sm border-l-4 border-blue-400 break-words break-all">
                                    <div class="flex flex-col sm:flex-row sm:justify-between items-start sm:items-center text-gray-700 dark:text-gray-200 text-sm mb-1 break-words break-all">
                                        <span class="font-semibold break-words break-all">{{ $log->user ? $log->user->username : 'Unknown' }}</span>
                                        <span class="text-gray-400 dark:text-gray-400 text-xs mt-1 sm:mt-0 break-words break-all">{{ $log->created_at->format('H:i, d M Y') }}</span>
                                    </div>
                                    
                                    <p class="text-gray-800 dark:text-gray-100 leading-relaxed break-words break-all">{{ $log->description }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 dark:text-gray-400">No actions yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
