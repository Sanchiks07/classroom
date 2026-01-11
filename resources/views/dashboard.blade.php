<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg sm:rounded-lg">
                <div class="container mx-auto p-6">
                    <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-200">Dashboard</h1>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-6">
                        @foreach($stats as $label => $value)
                            <div class="bg-white dark:bg-gray-700 rounded-2xl shadow p-4 flex flex-col justify-between hover:shadow-md transition break-words">
                                <div class="text-gray-500 dark:text-gray-300 text-sm">{{ $label }}</div>
                                <div class="text-2xl font-semibold mt-2 text-gray-900 dark:text-gray-100">{{ $value ?? 0 }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
