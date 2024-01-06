<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'WalletWise') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:400,500,600&display=swap" rel="stylesheet" />

        <!-- Icons -->
        <link rel="icon" href="{{ asset('/images/icons/icon.png') }}" type="image/x-icon" />

        <!-- Scripts -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
            integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="font-sans antialiased">
        <div class="min-h-screen bg-white dark:bg-gray-800">
            @include('layouts.navigation')
            <div class="flex">
                <aside
                    class="h-screen w-fit hidden sm:flex flex-col space-y-10 items-center justify-between relative bg-gray-100 dark:bg-gray-900 mx-10 rounded-full text-white p-5">
                    <div class="shrink-0 flex items-center my-5">
                        <a href="{{ route('dashboard') }}">
                            <x-application-logo
                                class="block h-10 w-auto fill-current text-gray-800 dark:text-gray-200" />
                        </a>
                    </div>
                    <div class="flex flex-col space-y-10 items-center justify-center relative">
                        <!-- Profile -->
                        <div
                            class="h-10 w-10 flex items-center justify-center rounded-lg cursor-pointer text-primaryDark dark:text-primaryLight hover:text-primaryLight hover:bg-primaryDark focus:bg-primaryDark dark:hover:text-primaryDark dark:hover:bg-primaryLight dark:focus:bg-primaryLight hover:duration-300 hover:ease-linear">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <!-- Courses -->
                        <div
                            class="h-10 w-10 flex items-center justify-center rounded-lg cursor-pointer text-primaryDark dark:text-primaryLight hover:text-primaryLight hover:bg-primaryDark focus:bg-primaryDark dark:hover:text-primaryDark dark:hover:bg-primaryLight dark:focus:bg-primaryLight hover:duration-300 hover:ease-linear">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <!-- Theme -->
                        <div
                            class="h-10 w-10 flex items-center justify-center rounded-lg cursor-pointer text-primaryDark dark:text-primaryLight hover:text-primaryLight hover:bg-primaryDark focus:bg-primaryDark dark:hover:text-primaryDark dark:hover:bg-primaryLight dark:focus:bg-primaryLight hover:duration-300 hover:ease-linear">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <!-- Configuration -->
                        <div
                            class="h-10 w-10 flex items-center justify-center rounded-lg cursor-pointer text-primaryDark dark:text-primaryLight hover:text-primaryLight hover:bg-primaryDark focus:bg-primaryDark dark:hover:text-primaryDark dark:hover:bg-primaryLight dark:focus:bg-primaryLight hover:duration-300 hover:ease-linear">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex py-5 items-center gap-1">
                        <label class="switch">
                            <input type="checkbox" id="theme-toggle" onchange="toggleTheme()">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </aside>
                <main class="w-full">
                    {{ $slot }}
                </main>
            </div>
        </div>
        <script src="{{ asset('js/darkMode.js') }}"></script>
    </body>

</html>