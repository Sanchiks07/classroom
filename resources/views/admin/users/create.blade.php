<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container mx-auto p-6 sm:px-4">
                    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4 sm:max-w-md sm:mx-auto">
                        @csrf

                        <!-- Username -->
                        <div class="mb-4">
                            <label for="username" class="block text-gray-700 font-medium">{{ __('Username') }}</label>
                            <input id="username" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-200" type="text" name="username" value="{{ old('username') }}" required>
                            
                            @error('username')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 font-medium">{{ __('Email') }}</label>
                            <input id="email" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-200" type="email" name="email" value="{{ old('email') }}" required>
                            
                            @error('email')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="block text-gray-700 font-medium">{{ __('Password') }}</label>
                            <input id="password" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-200" type="password" name="password" required>

                            @error('password')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-gray-700 font-medium">{{ __('Confirm Password') }}</label>
                            <input id="password_confirmation" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-200" type="password" name="password_confirmation" required>

                            @error('password_confirmation')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div class="mb-4">
                            <label for="role" class="block text-gray-700 font-medium">{{ __('Role') }}</label>
                            <select id="role" name="role" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-200">
                                <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Student</option>
                                <option value="teacher" {{ old('role') === 'teacher' ? 'selected' : '' }}>Teacher</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>

                            @error('role')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Submit -->
                        <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-center gap-2">
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 w-full sm:w-auto">
                                {{ __('Create User') }}
                            </button>

                            <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:underline text-center w-full sm:w-auto">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
