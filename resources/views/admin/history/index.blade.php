<x-app-layout>
    <div class="py-12 max-w-7xl mx-auto">
        <h1 class="text-2xl font-bold mb-6 text-center">Action Timeline</h1>

        <div class="max-w-2xl mx-auto space-y-6">
            @forelse($logs as $log)
                <div class="flex items-start space-x-4">
                    <!-- Timeline marker -->
                    <div class="flex-shrink-0">
                        <span class="block w-3 h-3 bg-blue-500 rounded-full mt-1"></span>
                    </div>

                    <!-- Timeline content -->
                    <div class="flex-1 border-l-2 border-blue-500 pl-4">
                        <div class="flex items-center space-x-2 text-gray-700 text-sm">
                            <span class="font-semibold">{{ $log->user ? $log->user->username : 'Unknown' }}</span>
                            <span class="text-gray-500">at {{ $log->created_at->format('H:i, d M Y') }}</span>
                        </div>
                        <p class="mt-1 text-gray-800">{{ $log->description }}</p>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500">No actions yet.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
