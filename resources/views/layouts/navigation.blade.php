<nav
    x-data="{
        open: false,
        dark: localStorage.getItem('dark') === 'true'
    }"
    x-init="$watch('dark', val => {
        localStorage.setItem('dark', val);
        document.documentElement.classList.toggle('dark', val);
    })"
    class="border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50"
>
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-gray-700 hover:text-black dark:text-gray-200 dark:hover:text-white">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if(auth()->user()->role === 'teacher' || auth()->user()->role === 'student')
                        <x-nav-link :href="route('classrooms.index')" :active="request()->routeIs('classrooms.*')" class="text-gray-700 hover:text-black dark:text-gray-200 dark:hover:text-white">
                            {{ __('Classrooms') }}
                        </x-nav-link>
                        <x-nav-link :href="route('calendar.index')" :active="request()->routeIs('calendar.*')" class="text-gray-700 hover:text-black dark:text-gray-200 dark:hover:text-white">
                            {{ __('Calendar') }}
                        </x-nav-link>
                    @endif

                    @if(auth()->user()->role === 'admin')
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" class="text-gray-700 hover:text-black dark:text-gray-200 dark:hover:text-white">
                            {{ __('Users') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.history.index')" :active="request()->routeIs('admin.history.*')" class="text-gray-700 hover:text-black dark:text-gray-200 dark:hover:text-white">
                            {{ __('History') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown + Dark Mode -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">

                <!-- Dark mode toggle -->
                <button @click="dark = !dark" class="relative h-5 w-5">
                    <img src="{{ asset('images/sun.png') }}"
                        :class="{'opacity-0': dark, 'opacity-100': !dark}"
                        class="absolute top-0 left-0 h-5 w-5 transition-opacity duration-200" x-cloak>
                    <img src="{{ asset('images/moon.png') }}"
                        :class="{'opacity-0': !dark, 'opacity-100': dark}"
                        class="absolute top-0 left-0 h-5 w-5 transition-opacity duration-200" x-cloak>
                </button>

                <!-- User dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md
                                text-gray-500 bg-white hover:text-gray-700
                                dark:bg-gray-800 dark:text-gray-300 dark:hover:text-white
                                focus:outline-none transition"
                        >
                            <div class="w-8 h-8 rounded-full bg-white p-0.5 me-2 flex items-center justify-center">
                                <img
                                    src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset('images/default-avatar.png') }}"
                                    alt="{{ Auth::user()->username }}" class="w-full h-full rounded-full object-cover"
                                >
                            </div>

                            <div>{{ Auth::user()->username }}</div>

                            <svg class="ms-1 h-4 w-4 fill-current" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content" class="dark:bg-gray-800">
                        <x-dropdown-link :href="route('profile.edit')" class="text-gray-700 hover:text-black dark:text-gray-200 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();"
                                class="text-gray-700 hover:text-black dark:text-gray-200 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                            >
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 dark:text-gray-300 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-gray-600 dark:text-gray-200 dark:hover:text-white dark:hover:bg-gray-800">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if(auth()->user()->role === 'teacher' || auth()->user()->role === 'student')
                <x-responsive-nav-link :href="route('classrooms.index')" :active="request()->routeIs('classrooms.*')" class="text-gray-600 dark:text-gray-200 dark:hover:text-white dark:hover:bg-gray-800">
                    {{ __('Classrooms') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('calendar.index')" :active="request()->routeIs('calendar.*')" class="text-gray-600 dark:text-gray-200 dark:hover:text-white dark:hover:bg-gray-800">
                    {{ __('Calendar') }}
                </x-responsive-nav-link>
            @endif

            @if(auth()->user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" class="text-gray-600 dark:text-gray-200 dark:hover:text-white dark:hover:bg-gray-800">
                    {{ __('Users') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link 
                    :href="route('admin.history.index')" :active="request()->routeIs('admin.history.*')" class="text-gray-600 dark:text-gray-200 dark:hover:text-white dark:hover:bg-gray-800">
                    {{ __('History') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-700">
            <div class="px-4 flex items-center">
                <div class="w-8 h-8 rounded-full bg-white p-0.5 me-2 flex items-center justify-center">
                    <img
                        src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset('images/default-avatar.png') }}"
                        alt="{{ Auth::user()->username }}" class="w-full h-full rounded-full object-cover"
                    >
                </div>
                
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->username }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-gray-600 dark:text-gray-200 dark:hover:text-white dark:hover:bg-gray-800">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-gray-600 dark:text-gray-200 dark:hover:text-white dark:hover:bg-gray-800">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>

                <button @click="dark = !dark" class="w-full text-left px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800 transition">
                    Toggle Dark Mode
                </button>
            </div>
        </div>
    </div>
</nav>
