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
        <script src="https://kit.fontawesome.com/29c8920454.js" crossorigin="anonymous"></script>
        {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="font-sans antialiased overflow-x-hidden">
        <div class="min-h-screen bg-white dark:bg-gray-800">
            @include('layouts.navigation')
            <div class="flex py-5">
                <aside
                    class="h-screen w-fit hidden sm:flex flex-col space-y-10 items-center justify-between relative bg-gray-100 dark:bg-gray-900 mx-10 rounded-full text-white p-5">
                    <div class="shrink-0 flex items-center my-5">
                        <a href="{{ route('dashboard') }}">
                            <x-application-logo
                                class="block h-10 w-auto fill-current text-gray-800 dark:text-gray-200" />
                        </a>
                    </div>
                    <div class="flex flex-col space-y-10 items-center justify-center relative">
                        <!-- Dashboard -->
                        <a href="{{ route('dashboard') }}">
                            <div data-popover-target="icon_dashboard_tooltip" data-popover-placement="right"
                                class="h-10 w-10 flex items-center justify-center rounded-lg cursor-pointer text-primaryDark dark:text-primaryLight hover:text-primaryLight hover:bg-primaryDark focus:bg-primaryDark dark:hover:text-primaryDark dark:hover:bg-primaryLight dark:focus:bg-primaryLight hover:duration-300 hover:ease-linear">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path
                                        d="M12 12C12 11.4477 12.4477 11 13 11H19C19.5523 11 20 11.4477 20 12V19C20 19.5523 19.5523 20 19 20H13C12.4477 20 12 19.5523 12 19V12Z"
                                        stroke-width="1.5" stroke-linecap="round" />
                                    <path
                                        d="M4 5C4 4.44772 4.44772 4 5 4H8C8.55228 4 9 4.44772 9 5V19C9 19.5523 8.55228 20 8 20H5C4.44772 20 4 19.5523 4 19V5Z"
                                        stroke-width="1.5" stroke-linecap="round" />
                                    <path
                                        d="M12 5C12 4.44772 12.4477 4 13 4H19C19.5523 4 20 4.44772 20 5V7C20 7.55228 19.5523 8 19 8H13C12.4477 8 12 7.55228 12 7V5Z"
                                        stroke-width="1.5" stroke-linecap="round" />
                                </svg>
                            </div>
                            <div data-popover id="icon_dashboard_tooltip" role="tooltip"
                                class="absolute z-10 invisible inline-block text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                <div class="p-3 space-y-2">
                                    <p class="font-semibold">Dashboard</p>
                                </div>
                                <div data-popper-arrow></div>
                            </div>
                        </a>
                        <!-- Wallets -->
                        <a href="{{ route('wallets.index') }}">
                            <div data-popover-target="icon_wallets_tooltip" data-popover-placement="right"
                                class="h-10 w-10 flex items-center justify-center rounded-lg cursor-pointer text-primaryDark dark:text-primaryLight hover:text-primaryLight hover:bg-primaryDark focus:bg-primaryDark dark:hover:text-primaryDark dark:hover:bg-primaryLight dark:focus:bg-primaryLight hover:duration-300 hover:ease-linear">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path d="M13 11.1499H7" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M2 11.1501V6.53009C2 4.49009 3.65 2.84009 5.69 2.84009H11.31C13.35 2.84009 15 4.11009 15 6.15009"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M17.48 12.1999C16.98 12.6799 16.74 13.4199 16.94 14.1799C17.19 15.1099 18.11 15.6999 19.07 15.6999H20V17.1499C20 19.3599 18.21 21.1499 16 21.1499H6C3.79 21.1499 2 19.3599 2 17.1499V10.1499C2 7.9399 3.79 6.1499 6 6.1499H16C18.2 6.1499 20 7.9499 20 10.1499V11.5999H18.92C18.36 11.5999 17.85 11.8199 17.48 12.1999Z"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M22 12.6201V14.6801C22 15.2401 21.5399 15.7001 20.9699 15.7001H19.0399C17.9599 15.7001 16.97 14.9101 16.88 13.8301C16.82 13.2001 17.0599 12.6101 17.4799 12.2001C17.8499 11.8201 18.36 11.6001 18.92 11.6001H20.9699C21.5399 11.6001 22 12.0601 22 12.6201Z"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div data-popover id="icon_wallets_tooltip" role="tooltip"
                                class="absolute z-10 invisible inline-block text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                <div class="p-3 space-y-2">
                                    <p class="font-semibold">Wallets</p>
                                </div>
                                <div data-popper-arrow></div>
                            </div>
                        </a>
                        <!-- Categories -->
                        <a href="{{ route('categories.index') }}">
                            <div data-popover-target="icon_categories_tooltip" data-popover-placement="right"
                                class="h-10 w-10 flex items-center justify-center rounded-lg cursor-pointer text-primaryDark dark:text-primaryLight hover:text-primaryLight hover:bg-primaryDark focus:bg-primaryDark dark:hover:text-primaryDark dark:hover:bg-primaryLight dark:focus:bg-primaryLight hover:duration-300 hover:ease-linear">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path d="M5 10H7C9 10 10 9 10 7V5C10 3 9 2 7 2H5C3 2 2 3 2 5V7C2 9 3 10 5 10Z"
                                        stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M17 10H19C21 10 22 9 22 7V5C22 3 21 2 19 2H17C15 2 14 3 14 5V7C14 9 15 10 17 10Z"
                                        stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M17 22H19C21 22 22 21 22 19V17C22 15 21 14 19 14H17C15 14 14 15 14 17V19C14 21 15 22 17 22Z"
                                        stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M5 22H7C9 22 10 21 10 19V17C10 15 9 14 7 14H5C3 14 2 15 2 17V19C2 21 3 22 5 22Z"
                                        stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div data-popover id="icon_categories_tooltip" role="tooltip"
                                class="absolute z-10 invisible inline-block text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                <div class="p-3 space-y-2">
                                    <p class="font-semibold">Categories</p>
                                </div>
                                <div data-popper-arrow></div>
                            </div>
                        </a>
                        <!-- Transactions -->
                        <a href="{{ route('transactions.index') }}">
                            <div data-popover-target="icon_transactions_tooltip" data-popover-placement="right"
                                class="h-10 w-10 flex items-center justify-center rounded-lg cursor-pointer text-primaryDark dark:text-primaryLight hover:text-primaryLight hover:bg-primaryDark focus:bg-primaryDark dark:hover:text-primaryDark dark:hover:bg-primaryLight dark:focus:bg-primaryLight hover:duration-300 hover:ease-linear">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path
                                        d="M6.72827 19.7C7.54827 18.82 8.79828 18.89 9.51828 19.85L10.5283 21.2C11.3383 22.27 12.6483 22.27 13.4583 21.2L14.4683 19.85C15.1883 18.89 16.4383 18.82 17.2583 19.7C19.0383 21.6 20.4883 20.97 20.4883 18.31V7.04C20.4883 3.01 19.5483 2 15.7683 2H8.20828C4.42828 2 3.48828 3.01 3.48828 7.04V18.3C3.49828 20.97 4.95827 21.59 6.72827 19.7Z"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M9.25 10H14.75" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div data-popover id="icon_transactions_tooltip" role="tooltip"
                                class="absolute z-10 invisible inline-block text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                <div class="p-3 space-y-2">
                                    <p class="font-semibold">Transactions</p>
                                </div>
                                <div data-popper-arrow></div>
                            </div>
                        </a>
                        <!-- Settings -->
                        <a href="{{ route('settings.index') }}">
                            <div data-popover-target="icon_settings_tooltip" data-popover-placement="right"
                                class="h-10 w-10 flex items-center justify-center rounded-lg cursor-pointer text-primaryDark dark:text-primaryLight hover:text-primaryLight hover:bg-primaryDark focus:bg-primaryDark dark:hover:text-primaryDark dark:hover:bg-primaryLight dark:focus:bg-primaryLight hover:duration-300 hover:ease-linear">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div data-popover id="icon_settings_tooltip" role="tooltip"
                                class="absolute z-10 invisible inline-block text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                <div class="p-3 space-y-2">
                                    <p class="font-semibold">Settings</p>
                                </div>
                                <div data-popper-arrow></div>
                            </div>
                        </a>
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
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    </body>

</html>