<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg sm:rounded-lg">
                <div class="container mx-auto p-6">
                    <!-- BIG SCREEN -->
                    <div class="hidden lg:flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-200">Users</h1>

                        <div class="flex items-center gap-8">
                            <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-2">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..."
                                        class="border border-gray-300 dark:border-gray-600 rounded-md px-3 py-1.5 focus:ring-2 focus:ring-blue-200 focus:outline-none dark:bg-gray-700 dark:text-gray-200 text-sm h-9 w-auto">

                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-md text-sm font-medium shadow h-9 flex items-center justify-center">
                                    <img src="/images/search.png" class="h-5 w-5">
                                </button>
                            </form>

                            <a href="{{ route('admin.users.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md shadow font-semibold h-10 flex items-center justify-center">
                                Add User
                            </a>
                        </div>
                    </div>

                    <!-- SMALL SCREEN -->
                    <div class="flex flex-col lg:hidden mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-200">Users</h1>

                            <a href="{{ route('admin.users.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md shadow font-semibold h-10 flex items-center justify-center">
                                Add User
                            </a>
                        </div>

                        <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-2 w-full">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..."
                                    class="mt-3 border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-200 focus:outline-none dark:bg-gray-700 dark:text-gray-200 text-sm w-full">
                            
                            <button type="submit" class="mt-3 min-w-[3rem] bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-md text-sm font-medium shadow flex items-center justify-center">
                                <img src="/images/search.png" class="h-5 w-5">
                            </button>
                        </form>
                    </div>

                    @if($users->count())
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach($users as $user)
                                <div class="bg-white dark:bg-gray-700 shadow-md rounded-lg p-4 flex flex-col justify-between hover:shadow-lg transition-shadow">
                                    <div>
                                        <h2 class="font-semibold text-lg mb-1 text-gray-900 dark:text-gray-200">{{ $user->username }}</h2>
                                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-1">{{ $user->email }}</p>
                                        <span class="inline-block bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 px-2 py-1 rounded-full text-xs">{{ ucfirst($user->role) }}</span>
                                    </div>

                                    <div class="mt-4 flex gap-2">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-500 hover:underline font-medium">
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="text-red-500 hover:underline font-medium">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-300">No users yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
