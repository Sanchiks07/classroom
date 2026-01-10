<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container mx-auto p-6">
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf

                        <!-- Username -->
                        <div class="mb-4">
                            <label for="username" class="block text-gray-700 font-medium">{{ __('Username') }}</label>
                            <input id="username" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="text" name="username" value="{{ old('username') }}" required autofocus>
                            
                            @error('username')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 font-medium">{{ __('Email') }}</label>
                            <input id="email" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="email" name="email" value="{{ old('email') }}" required>
                            
                            @error('email')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="block text-gray-700 font-medium">{{ __('Password') }}</label>
                            <input id="password" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="password" name="password" required>

                            @error('password')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div class="mb-4">
                            <label for="role" class="block text-gray-700 font-medium">{{ __('Role') }}</label>
                            <select id="role" name="role" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Student</option>
                                <option value="teacher" {{ old('role') === 'teacher' ? 'selected' : '' }}>Teacher</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>

                            @error('role')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Submit -->
                        <div class="mt-6">
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                {{ __('Create User') }}
                            </button>

                            <a href="{{ route('admin.users.index') }}" class="ml-4 text-gray-600 hover:text-gray-900">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
