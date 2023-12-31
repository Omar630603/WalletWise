<nav class="relative px-8 py-4 flex justify-between items-center bg-gray-100 dark:bg-gray-900 m-5 rounded-full">
    <div class="flex items-center">
        <a href="/">
            <x-application-logo class="w-8 h-8 fill-current text-gray-500" />
        </a>
        <span
            class="ml-2 text-xl font-bold text-primaryDark dark:text-primaryLight">{{ config('app.name', 'WalletWise') }}</span>
    </div>
    <div class="lg:hidden">
        <button class="navbar-burger flex items-center text-blue-600 dark:text-white p-3">
            <svg class="block h-4 w-4 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <title>Mobile menu</title>
                <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path>
            </svg>
        </button>
    </div>
    <ul
        class="hidden absolute top-1/2 left-1/2 transform -translate-y-1/2 -translate-x-1/2 lg:mx-auto lg:flex lg:items-center lg:w-auto lg:space-x-6">
        @php
        $currentRoute = Route::current()->getName();
        $active = 'text-blue-600 font-bold';
        $inactive = 'text-gray-400 hover:text-gray-500';
        @endphp
        <li><a class="text-sm {{ $currentRoute == 'welcome' ? $active : $inactive }}"
                href="{{route('welcome')}}">Home</a></li>
        <li class="text-gray-300">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" class="w-4 h-4 current-fill"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 5v0m0 7v0m0 7v0m0-13a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
            </svg>
        </li>
        <li><a class="text-sm {{ $currentRoute == 'about' ? $active : $inactive }}" href="{{route('about')}}">About
                Us</a></li>
        <li class="text-gray-300">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" class="w-4 h-4 current-fill"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 5v0m0 7v0m0 7v0m0-13a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
            </svg>
        </li>
        <li><a class="text-sm {{ $currentRoute == 'contact' ? $active : $inactive }}"
                href="{{route('contact')}}">Contact Us</a></li>
    </ul>
    @if(Route::current()->getName() != 'login' && Route::current()->getName() != 'register')
    @if (Route::has('login'))
    @auth
    <a class="hidden lg:inline-block lg:ml-auto lg:mr-3 py-2 px-6 bg-primaryDark hover:bg-gray-900 text-sm text-white font-bold rounded-xl transition duration-200"
        href="{{ url('/dashboard') }}">Dashboard</a>
    @else
    <a class="hidden lg:inline-block lg:ml-auto lg:mr-3 py-2 px-6 bg-gray-50 hover:bg-gray-100 text-sm text-gray-900 font-bold  rounded-xl transition duration-200"
        href="{{ route('login') }}">Sign In</a>
    @if (Route::has('register'))

    <a class="hidden lg:inline-block py-2 px-6 bg-primaryDark hover:bg-gray-900 text-sm text-white font-bold rounded-xl transition duration-200"
        href="{{ route('register') }}">Sign Up</a>
    @endif
    @endauth
    @endif
    @endif
    <div class="hidden lg:flex pl-4 items-center gap-1">
        <label class="switch">
            <input type="checkbox" id="theme-toggle" onchange="toggleTheme()">
            <span class="slider round"></span>
        </label>
    </div>
</nav>
<div class="navbar-menu relative z-50 hidden">
    <div class="navbar-backdrop fixed inset-0 bg-gray-800 opacity-25"></div>
    <nav
        class="fixed top-0 left-0 bottom-0 flex flex-col w-5/6 max-w-sm py-6 px-6 bg-gray-100 dark:bg-gray-900 border-r overflow-y-auto">
        <div class="flex items-center mb-8">
            <a class="mr-auto text-3xl font-bold leading-none" href="/">
                <x-application-logo class="w-8 h-8 fill-current text-gray-500" />
            </a>
            <button class="navbar-close">
                <svg class="h-6 w-6 text-gray-400 cursor-pointer hover:text-gray-500" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        <div>
            <ul>
                <li class="mb-1">
                    <a class="block p-4 text-sm font-semibold text-gray-400 hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-950 dark:hover:text-primaryLight rounded"
                        href="{{route('welcome')}}">Home</a>
                </li>
                <li class="mb-1">
                    <a class="block p-4 text-sm font-semibold text-gray-400 hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-950 dark:hover:text-primaryLight rounded"
                        href="{{route('about')}}">About Us</a>
                </li>
                <li class="mb-1">
                    <a class="block p-4 text-sm font-semibold text-gray-400 hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-950 dark:hover:text-primaryLight rounded"
                        href="{{route('contact')}}">Contact Us</a>
                </li>
            </ul>
        </div>
        <div class="mt-auto">
            @if(Route::current()->getName() != 'login' && Route::current()->getName() != 'register')
            @if (Route::has('login'))
            <div class="pt-6">
                @auth
                <a class="block px-4 py-3 mb-2 leading-loose text-xs text-center text-white font-semibold bg-primaryDark hover:bg-gray-900  rounded-xl"
                    href="{{ url('/dashboard') }}">Dashboard</a>
                @else

                <a class="block px-4 py-3 mb-3 leading-loose text-xs text-center font-semibold bg-gray-50 hover:bg-gray-100 rounded-xl"
                    href="{{ route('login') }}">Sign in</a>
                @if (Route::has('register'))

                <a class="block px-4 py-3 mb-2 leading-loose text-xs text-center text-white font-semibold bg-primaryDark hover:bg-gray-900  rounded-xl"
                    href="{{ route('register') }}">Sign Up</a>
                @endif
                @endauth
            </div>
            @endif
            @endif
            <div class="flex justify-center mt-4">
                <label class="switch">
                    <input type="checkbox" id="theme-toggle-side-menu" onchange="toggleTheme()">
                    <span class="slider round"></span>
                </label>
            </div>
            <p class="my-4 text-xs text-center text-gray-400">
                <span>Copyright ©
                    <script>
                        document.write(new Date().getFullYear());
                    </script>
                </span>
            </p>
        </div>
    </nav>
</div>