<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container mx-auto p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold">Users</h1>
                        <a href="{{ route('admin.users.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md shadow font-semibold">
                            Add User
                        </a>
                    </div>

                    @if($users->count())
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach($users as $user)
                                <div class="bg-white shadow-md rounded-lg p-4 flex flex-col justify-between hover:shadow-lg transition-shadow">
                                    <div>
                                        <h2 class="font-semibold text-lg mb-1">{{ $user->username }}</h2>
                                        <p class="text-gray-600 text-sm mb-1">{{ $user->email }}</p>
                                        <span class="inline-block bg-gray-200 text-gray-700 px-2 py-1 rounded-full text-xs">{{ ucfirst($user->role) }}</span>
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
                        <p class="text-gray-500">No users yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
