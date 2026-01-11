<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg sm:rounded-lg">
                <div class="container mx-auto p-6 sm:px-4">
                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-4 sm:max-w-md lg:max-w-full sm:mx-auto">
                        @csrf
                        @method('PUT')

                        <!-- Username -->
                        <div class="mb-4">
                            <label for="username" class="block text-gray-700 dark:text-gray-200 font-medium">{{ __('Username') }}</label>
                            <input id="username" class="block mt-1 w-full lg:w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-200 dark:bg-gray-700 dark:text-gray-200" type="text" name="username" value="{{ old('username', $user->username) }}" required>
                            
                            @error('username')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 dark:text-gray-200 font-medium">{{ __('Email') }}</label>
                            <input id="email" class="block mt-1 w-full lg:w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-200 dark:bg-gray-700 dark:text-gray-200" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                            
                            @error('email')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="block text-gray-700 dark:text-gray-200 font-medium">{{ __('Password (leave blank to keep)') }}</label>
                            <input id="password" class="block mt-1 w-full lg:w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-200 dark:bg-gray-700 dark:text-gray-200" type="password" name="password">
                            
                            @error('password')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-gray-700 dark:text-gray-200 font-medium">{{ __('Confirm Password') }}</label>
                            <input id="password_confirmation" class="block mt-1 w-full lg:w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-200 dark:bg-gray-700 dark:text-gray-200" type="password" name="password_confirmation">

                            @error('password_confirmation')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div class="mb-4">
                            <label for="role" class="block text-gray-700 dark:text-gray-200 font-medium">{{ __('Role') }}</label>
                            <select id="role" name="role" class="block mt-1 w-full lg:w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-200 dark:bg-gray-700 dark:text-gray-200">
                                <option value="student" {{ old('role', $user->role) === 'student' ? 'selected' : '' }}>Student</option>
                                <option value="teacher" {{ old('role', $user->role) === 'teacher' ? 'selected' : '' }}>Teacher</option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>

                            @error('role')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Submit Changes or Cancel -->
                        <div class="mt-6 flex flex-col sm:flex-row sm:items-center gap-2 justify-start">
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 w-full sm:w-auto">
                                {{ __('Update User') }}
                            </button>

                            <a href="{{ route('admin.users.index') }}" class="text-gray-600 dark:text-gray-300 hover:underline text-center w-full sm:w-auto">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
