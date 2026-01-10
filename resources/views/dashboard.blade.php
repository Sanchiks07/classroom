<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container mx-auto p-6">
                    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                        @foreach($stats as $label => $value)
                            <div class="bg-white rounded-2xl shadow p-4 flex flex-col justify-between hover:shadow-md transition">
                                <div class="text-gray-500 text-sm">{{ $label }}</div>
                                <div class="text-2xl font-semibold mt-2">{{ $value ?? 0 }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
