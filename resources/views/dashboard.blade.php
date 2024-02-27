<x-app-layout>
    <div class="pl-5 pr-10 mb-4">
        @if (session('status') == 'wallet-created')
        <x-alert-session-status color="green" message="{{ session('message') }}"
            id="alert-session-status-wallet-created" />
        @elseif (session('status') == 'wallet-not-created')
        <x-alert-session-status color="red" message="{{ session('message') }}"
            id="alert-session-status-wallet-not-created" />
        @elseif (session('status') == 'transaction-created')
        <x-alert-session-status color="green" message="{{ session('message') }}"
            id="alert-session-status-transaction-created" />
        @elseif (session('status') == 'transaction-not-created')
        <x-alert-session-status color="red" message="{{ session('message') }}"
            id="alert-session-status-transaction-not-created" />
        @endif
    </div>
    <div class="flex justify-center lg:justify-between items-center px-5 sm:pr-10 flex-wrap gap-3">
        <span class="font-bold text-xl text-primaryDark dark:text-primaryLight">
            <span class="greetings text-xl"></span>{{ Auth::user()->name }}
        </span>
        @if (Auth::user()->wallets->count() >= 1)
        <div class="flex justify-center items-center gap-2 flex-wrap">
            <div>
                {{-- Go to current --}}
                @if ($current_month != date('M') || $current_year != date('Y'))
                <x-primary-button id="go_to_current_month" class="h-full" type="button"
                    data-tooltip-target="tooltip_go_to_current_month">
                    <i class="fa-solid fa-calendar text-sm"></i>
                </x-primary-button>
                <div id="tooltip_go_to_current_month" role="tooltip"
                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-primaryDark rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Go to current month
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
                @endif
                <a id="prev_month" class="cursor-pointer bg-primaryLight dark:bg-primaryDark p-1 rounded-md">
                    <i class="fa-solid fa-chevron-left text-gray-700 dark:text-gray-200 text-sm"></i>
                </a>
                <select id="current_month"
                    class="w-20 p-2 rounded-lg bg-primaryLight dark:bg-primaryDark text-md text-primaryDark dark:text-primaryLight">
                    @for ($i = 1; $i <= 12; $i++) @php $monthName=date('M', mktime(0, 0, 0, $i, 10)); @endphp <option
                        value="{{ $monthName }}" {{ $monthName==$current_month ? 'selected' : '' }}>
                        {{ $monthName }}
                        </option>
                        @endfor
                </select>
                <input type="number" id="current_year"
                    class="w-20 p-2 rounded-lg bg-primaryLight dark:bg-primaryDark text-md text-primaryDark dark:text-primaryLight"
                    value="{{ $current_year }}" min="2000" max="2100">
                <a id="next_month" class="cursor-pointer bg-primaryLight dark:bg-primaryDark p-1 rounded-md">
                    <i class="fa-solid fa-chevron-right text-gray-700 dark:text-gray-200 text-sm"></i>
                </a>
            </div>
            <select id="currencies_filter"
                class="w-20 p-2 rounded-lg bg-primaryLight dark:bg-primaryDark text-md text-primaryDark dark:text-primaryLight">
                @foreach ($userCurrencies as $currency)
                <option value="{{$currency}}" @if($currency==request()->query('currency') || $currency ==
                    $defaultCurrency) selected
                    @endif>
                    {{$currency}}
                </option>
                @endforeach
            </select>
        </div>
        @endif
    </div>
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-10 px-5 mt-10 sm:pr-10">
        {{-- Left Side --}}
        <div class="space-y-8 col-span-2 xl:col-span-1">
            {{-- Upper Side --}}
            <div class="space-y-5">
                <div class="flex justify-between gap-2 items-start">
                    <span class="text-primaryDark dark:text-primaryLight text-xl font-semibold">My Wallets</span>
                    @if (Auth::user()->wallets->count() >= 1)
                    <div>
                        <x-primary-button data-tooltip-target="tooltip-add_wallet" type="button"
                            data-tooltip-placement="bottom" class="h-full " data-modal-target="create-wallet-modal"
                            data-modal-toggle="create-wallet-modal">
                            <i class="fa-solid fa-plus text-white dark:text-black my-1.5"></i>
                        </x-primary-button>
                        <div id="tooltip-add_wallet" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-primaryDark rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Add new wallet
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </div>
                    @endif
                </div>
                @if (Auth::user()->wallets->count() == 0)
                <div
                    class="flex flex-col justify-center items-center shadow rounded-xl px-2 py-4 text-primaryDark dark:text-primaryLight space-y-8 bg-gray-100 dark:bg-gray-900">
                    <span class="text-center p-2">You don't have any wallets yet.</span>
                    <x-primary-button data-modal-target="create-wallet-modal" data-modal-toggle="create-wallet-modal"
                        type="button">
                        Create Wallet
                    </x-primary-button>
                </div>
                @else
                <div class="shadow rounded-xl p-4 text-primaryDark dark:text-primaryLight bg-gray-100 dark:bg-gray-900">
                    @if($defaultWallet != null)
                    <div class="cursor-pointer justify-between gap-2 items-center">
                        <div data-tooltip-target="tooltip_wallet_summary" data-tooltip-placement="top"
                            class="grid grid-cols-2 items-start bg-primaryDark dark:bg-gray-800 rounded-xl px-6 py-4 text-primaryLight relative">
                            <div class="absolute w-10 h-10 bg-primaryLight rounded-full top-1 right-1 opacity-40">
                            </div>
                            <div class="absolute w-16 h-16 bg-primaryLight rounded-full top-3 right-5 opacity-60">
                            </div>
                            <div class="absolute w-10 h-10 bg-primaryLight rounded-full bottom-6 left-1 opacity-10">
                            </div>
                            <div
                                class="absolute w-20 h-10 bg-primaryLight rounded-tl-full rounded-tr-full bottom-0 left-3 opacity-20">
                            </div>
                            <div class="flex flex-col justify-between items-start mt-2">
                                <span class="text-lg font-semibold">{{$defaultWallet_formatted_balance}}</span>
                                <span class="text-md font-semibold my-5">{{$defaultWallet->name}}</span>
                                <span class="text-md font-semibold mt-8">{{$defaultWallet_type_label}}</span>
                            </div>
                            <div
                                class="w-16 h-16 rounded-full bg-white dark:bg-gray-900 flex justify-center items-center self-end justify-self-end">
                                <i class="fa-solid {{$defaultWallet->icon}} text-2xl"></i>
                            </div>
                        </div>
                        <div class="p-2 invisible z-10 ransition-opacity duration-300 bg-primaryDark rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700"
                            id="tooltip_wallet_summary" role="tooltip">
                            <span class="text-primaryLight text-sm lg:text-md w-full text-center">
                                {{$defaultWallet->name}} wallet summary
                            </span>
                            <div class="flex gap-4 ">
                                <div class="col-span-3 flex flex-col justify-between">
                                    <span class="text-primaryLight text-sm lg:text-md">
                                        Expenses</span>
                                    <div class="flex gap-2 items-end mt-2">
                                        <div class="flex flex-col justify-between ">
                                            <span class="text-gray-500 text-xs">Last month</span>
                                            <span
                                                class="text-primaryLight text-xs">{{$summary_formatted['lastMonthWalletExpense']}}</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-gray-500 text-xs">This month</span>
                                            <span class="text-primaryLight text-xs">
                                                {{$summary_formatted['currentMonthWalletExpense']}}</span>
                                        </div>
                                        <div class="text-xs flex gap-1">
                                            <i
                                                class="fa-solid fa-chevron-up text-primaryLight bg-purple-700 p-0.5 rounded-md"></i>
                                            <span class="text-primaryLight ">
                                                {{$summary_formatted['walletExpenseDifferencePercentage']}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-3 flex flex-col justify-between">
                                    <span class="text-primaryLight text-sm lg:text-md">
                                        Incomes</span>
                                    <div class="flex gap-2 items-end mt-2">
                                        <div class="flex flex-col justify-between ">
                                            <span class="text-gray-500 text-xs">Last month</span>
                                            <span
                                                class="text-primaryLight text-xs">{{$summary_formatted['lastMonthWalletIncome']}}</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-gray-500 text-xs">This month</span>
                                            <span class="text-primaryLight text-xs">
                                                {{$summary_formatted['currentMonthWalletIncome']}}</span>
                                        </div>
                                        <div class="text-xs flex gap-1">
                                            <i
                                                class="fa-solid fa-chevron-up text-primaryLight bg-purple-700 p-0.5 rounded-md"></i>
                                            <span class="text-primaryLight ">
                                                {{$summary_formatted['walletIncomeDifferencePercentage']}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </div>
                    @else
                    <x-alert-session-status color="red" message="Oops! There seems to be an error. Please contact
                    the developer for assistance" />
                    @endif
                    @if (Auth::user()->wallets->count() > 1)
                    <div class="flex justify-between mt-5 items-center">
                        <button type="button" class="w-8 h-8 rounded-full text-sm bg-primaryDark dark:bg-gray-800"
                            id="prev_wallet" type="button">
                            <i class=" fa-solid fa-chevron-left text-white"></i>
                        </button>
                        <span
                            class="text-primaryDark dark:text-primaryLight text-sm font-semibold">{{$defaultWallet_number}}
                            of
                            {{Auth::user()->wallets->where('currency', $defaultCurrency)->count()}}</span>
                        <button type="button" class="w-8 h-8 rounded-full text-sm bg-primaryDark dark:bg-gray-800"
                            id="next_wallet" type="button">
                            <i class=" fa-solid fa-chevron-right text-white"></i>
                        </button>
                    </div>
                    @endif
                </div>
                @endif
                <div id="create-wallet-modal" tabindex="-1" aria-hidden="true"
                    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative p-4 w-full max-w-md max-h-full">
                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                            <div
                                class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    This your {{ $numberNewWallet }}
                                    wallet
                                </h3>
                                <button type="button"
                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                    data-modal-toggle="create-wallet-modal">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>
                            <!-- Modal body -->
                            <form class="p-4 md:p-5" action="{{ route('wallets.store') }}" method="POST">
                                @csrf
                                <div class="grid gap-4 mb-4 grid-cols-2">
                                    {{-- Name --}}
                                    <div class="col-span-2">
                                        <label for="name"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Wallet
                                            Name</label>
                                        <input type="text" name="name" id="name"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                            placeholder="What is your wallet name?" required="">
                                    </div>
                                    {{-- Type --}}
                                    <div class="col-span-2">
                                        <label for="type"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Wallet
                                            Type</label>
                                        <select id="type" name="type"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                            <option selected disabled>Select wallet type</option>
                                            @forelse ($walletTypes as $type)
                                            <option value="{{ $type['value'] }}">{{ $type['label'] }}</option>
                                            @empty
                                            <option value="0" disabled>No wallet type available</option>
                                            @endforelse
                                        </select>
                                    </div>
                                    {{-- Currency --}}
                                    <div class="col-span-2 sm:col-span-1">
                                        <label for="currency"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Currency</label>
                                        <select id="currency" name="currency"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                            <option selected disabled>Select currency</option>
                                            @forelse ($currencies as $code => $name)
                                            <option value="{{ $code }}">{{ $code }} - {{ $name }} ({{
                                                $symbols[$code] }})</option>
                                            @empty
                                            <option value="0" disabled>No currency available</option>
                                            @endforelse
                                        </select>
                                    </div>
                                    {{-- Initial Balance --}}
                                    <div class="col-span-2 sm:col-span-1">
                                        <label for="initial_balance"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Initial
                                            Balance</label>
                                        <input type="text" id="initial_balance_display" min="0"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                            placeholder="0" required="">
                                        <input type="hidden" name="initial_balance" id="initial_balance">
                                    </div>
                                    {{-- Icons --}}
                                    <div class="col-span-2">
                                        <label for="icon"
                                            class="block mt-2 text-sm font-medium text-gray-900 dark:text-white">
                                            Choose an icon and color for your wallet
                                        </label>
                                        <div class="grid grid-cols-3 justify-between items-center">
                                            <div id="icon-container" class="col-span-2 flex justify-between">
                                                <div class="icon cursor-pointer" data-icon="fa-wallet">
                                                    <i class="fa-solid fa-wallet text-2xl text-gray-500"></i>
                                                </div>
                                                <div class="icon cursor-pointer" data-icon="fa-money-bill">
                                                    <i class="fa-solid fa-money-bill text-2xl text-gray-500"></i>
                                                </div>
                                                <div class="icon cursor-pointer" data-icon="fa-piggy-bank">
                                                    <i class="fa-solid fa-piggy-bank text-2xl text-gray-500"></i>
                                                </div>
                                                <div class="icon cursor-pointer" data-icon="fa-building-columns">
                                                    <i class="fa-solid fa-building-columns text-2xl text-gray-500"></i>
                                                </div>
                                                <div class="icon cursor-pointer" data-icon="fa-credit-card">
                                                    <i class="fa-regular fa-credit-card text-2xl text-gray-500"></i>
                                                </div>
                                            </div>
                                            <input type="hidden" id="selected-icon" name="icon" value="fa-wallet">
                                            <div id="color-container"
                                                class="flex flex-wrap p-5 items-center justify-center gap-2">
                                                <button class="color w-5 h-5 bg-gray-500 rounded-full"
                                                    data-color="gray-500" type="button"></button>
                                                <button class="color w-5 h-5 bg-red-500 rounded-full"
                                                    data-color="red-500" type="button"></button>
                                                <button class="color color w-5 h-5 bg-blue-500 rounded-full"
                                                    data-color="blue-500" type="button"></button>
                                                <button class="color w-5 h-5 bg-green-500 rounded-full"
                                                    data-color="green-500" type="button"></button>
                                                <button class="color w-5 h-5 bg-yellow-500 rounded-full"
                                                    data-color="yellow-500" type="button"></button>
                                            </div>
                                            <input type="hidden" id="selected-color" name="color" value="text-gray-500">
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <x-primary-button>
                                        <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Add new wallet
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Lower Side --}}
            <div class="space-y-5">
                <span class="text-primaryDark dark:text-primaryLight text-xl font-semibold">Statistics</span>
                @if ($chartData)
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4 md:p-6">
                    <div class="flex justify-between mb-5">
                        <div class="grid gap-1 grid-cols-2">
                            <div>
                                <h5
                                    class="inline-flex items-center text-gray-500 dark:text-gray-400 leading-none font-normal mb-2">
                                    Expenses
                                    <svg data-popover-target="clicks-info" data-popover-placement="bottom"
                                        class="w-3 h-3 text-gray-400 hover:text-gray-900 dark:hover:text-white cursor-pointer ms-1"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                                    </svg>
                                    <div data-popover id="clicks-info" role="tooltip"
                                        class="absolute z-10 invisible inline-block text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 w-72 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                        <div class="p-3 space-y-2">
                                            <h3 class="font-semibold text-gray-900 dark:text-white">
                                                {{ $current_month }} - {{ $current_year }} Expenses
                                            </h3>
                                            <p>
                                                This chart shows the total amount of expenses you have made this month
                                                from all wallets with {{ $defaultCurrency }} currency.
                                            </p>
                                        </div>
                                        <div data-popper-arrow></div>
                                    </div>
                                </h5>
                                <p class="text-gray-900 dark:text-white text-sm leading-none font-bold">
                                    {{$chartData['total_expenses']}}
                                </p>
                            </div>
                            <div>
                                <h5
                                    class="inline-flex items-center text-gray-500 dark:text-gray-400 leading-none font-normal mb-2">
                                    Incomes
                                    <svg data-popover-target="cpc-info" data-popover-placement="bottom"
                                        class="w-3 h-3 text-gray-400 hover:text-gray-900 dark:hover:text-white cursor-pointer ms-1"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                                    </svg>
                                    <div data-popover id="cpc-info" role="tooltip"
                                        class="absolute z-10 invisible inline-block text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 w-72 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                        <div class="p-3 space-y-2">
                                            <h3 class="font-semibold text-gray-900 dark:text-white">{{$current_month}} -
                                                {{$current_year}} Incomes
                                            </h3>
                                            <p>
                                                This chart shows the total amount of incomes you have made this month
                                                from all wallets with {{ $defaultCurrency }} currency.
                                            </p>
                                        </div>
                                        <div data-popper-arrow></div>
                                    </div>
                                </h5>
                                <p class="text-gray-900 dark:text-white text-sm leading-none font-bold">
                                    {{$chartData['total_incomes']}}
                                </p>
                            </div>
                        </div>
                        <div>
                            <button id="dropdownDefaultButton" data-dropdown-toggle="lastDaysdropdown"
                                data-dropdown-placement="bottom" type="button"
                                class="p-2 inline-flex items-center text-xs font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                {{$chartData['chart_option']}}
                                <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg>
                            </button>
                            <div id="lastDaysdropdown"
                                class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                                <ul class="py-2 text-xs text-gray-700 dark:text-gray-200"
                                    aria-labelledby="dropdownDefaultButton">
                                    <li>
                                        <a data-chart-option="01"
                                            class="cursor-pointer block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white chartOption">1st
                                            Week</a>
                                    </li>
                                    <li>
                                        <a data-chart-option="02"
                                            class="cursor-pointer block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white chartOption">2nd
                                            Week</a>
                                    </li>
                                    <li>
                                        <a data-chart-option="03"
                                            class="cursor-pointer block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white chartOption">3rd
                                            Week</a>
                                    </li>
                                    <li>
                                        <a data-chart-option="04"
                                            class="cursor-pointer block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white chartOption">4th
                                            Week</a>
                                    </li>
                                    <li>
                                        <a data-chart-option="all-month"
                                            class="cursor-pointer block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white chartOption">
                                            This Month</a>
                                    </li>
                                    <li>
                                        <a data-chart-option="all-year"
                                            class="cursor-pointer block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white chartOption">
                                            This Year</a>
                                    </li>
                                </ul>
                            </div>
                            <input type="text" id="chart-option" name="chart-option"
                                value="{{request()->query('chart-option')}}" hidden>
                        </div>
                    </div>
                    <div id="line-chart"></div>
                </div>
                <div id="chartData" data-total-expenses-array=@json($chartData['total_expenses_array'])
                    data-total-incomes-array=@json($chartData['total_incomes_array'])
                    data-periods="{{ json_encode($chartData['periods'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}"
                    data-chart-option=@json(request()->query('chart-option'))
                    data-default-currency={{$defaultCurrency}} style="display: none;">
                </div>
                @else
                <x-alert-session-status color="gray" id="no_statistics_alert"
                    message="No statistics found yet, or you don't have any wallets yet" />
                @endif
            </div>
        </div>
        {{-- Right Side --}}
        <div class="col-span-2 space-y-8">
            {{-- Upper Side --}}
            <div class="space-y-5">
                <div class="flex justify-between">
                    <span class="text-primaryDark dark:text-primaryLight text-xl font-semibold">Wallet Summary</span>
                    @if (Auth::user()->wallets->count() > 1)
                    <div class="flex flex-col items-end">
                        <h6
                            class="inline-flex items-center text-gray-500 dark:text-gray-400 leading-none font-normal mb-2">
                            Total Amount
                            <svg data-popover-target="total-amount-info" data-popover-placement="bottom"
                                class="w-3 h-3 text-gray-400 hover:text-gray-900 dark:hover:text-white cursor-pointer ms-1"
                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                            </svg>
                            <div data-popover id="total-amount-info" role="tooltip"
                                class="absolute z-10 invisible inline-block text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 w-72 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                <div class="p-3 space-y-2">
                                    <h3 class="font-semibold text-gray-900 dark:text-white"> Total Amount Left
                                        From All Wallets
                                    </h3>
                                    <p>
                                        This shows the total amount left from all the wallets of the same
                                        currency selected above.
                                    </p>
                                </div>
                                <div data-popper-arrow></div>
                            </div>
                        </h6>
                        <p class="text-gray-900 dark:text-white text-lg leading-none font-bold">
                            {{$total_amount}}
                        </p>
                    </div>
                    @endif
                </div>
                @if ($summary_formatted)
                <div class="grid grid-cols-1 lg:grid-cols-2 justify-items-stretch gap-2">
                    <div class="grid grid-cols-5 justify-between gap-2 items-center bg-primaryDark dark:bg-gray-900 rounded-xl p-2"
                        data-tooltip-target="tooltip-wallet_expanses_percentage">
                        <div class="relative w-16 h-16 lg:w-20 lg:h-20">
                            <svg class="absolute top-0 left-0 w-full h-full"
                                style="transform: rotate(-90deg); transform-origin: 50% 50%;">
                                <circle class="text-gray-200 stroke-current" stroke-width="4" fill="transparent" r="40%"
                                    cx="50%" cy="50%"></circle>
                                <circle class="progress text-red-500 stroke-current" stroke-width="4" fill="transparent"
                                    r="30%" cx="50%" cy="50%"></circle>
                            </svg>
                            <span
                                class="progress-text absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-primaryLight text-sm lg:text-md">
                                {{$summary_formatted['walletExpensePercentage']}}</span>
                        </div>
                        <div id="tooltip-wallet_expanses_percentage" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-primaryDark rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            {{$summary_formatted['walletExpensePercentage']}} of your total expenses are from
                            {{$defaultWallet->name}} wallet
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                        <div class="col-span-3">
                            <span class="text-primaryLight text-md lg:text-lg">Total
                                Expenses</span>
                            <div class="flex gap-2 items-end mt-2">
                                <div class="flex flex-col justify-between ">
                                    <span class="text-gray-500 text-sm">Last month</span>
                                    <span
                                        class="text-primaryLight text-sm">{{$summary_formatted['lastMonthExpense']}}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-gray-500 text-sm">This month</span>
                                    <span class="text-primaryLight text-sm">
                                        {{$summary_formatted['currentMonthExpense']}}</span>
                                </div>
                                <div class="text-xs flex gap-1">
                                    <i
                                        class="fa-solid 
                                        @if((int)str_replace('%', '', $summary_formatted['expenseDifferencePercentage']) < 0) fa-chevron-down @else fa-chevron-up @endif text-primaryLight bg-purple-700 p-0.5 rounded-md">
                                    </i>
                                    <span class="text-primaryLight ">
                                        {{$summary_formatted['expenseDifferencePercentage']}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="h-full flex justify-end">
                            <div
                                class="h-full w-1/2 bg-primaryLight dark:bg-primaryDark rounded-xl p-2 flex justify-center items-center">
                                <i class="fa-solid fa-chevron-right text-primaryDark dark:text-primaryLight"></i>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-5 justify-between gap-2 items-center bg-primaryDark dark:bg-gray-900 rounded-xl p-2"
                        data-tooltip-target="tooltip-wallet_income_percentage">
                        <div class="relative w-16 h-16 lg:w-20 lg:h-20">
                            <svg class="absolute top-0 left-0 w-full h-full"
                                style="transform: rotate(-90deg); transform-origin: 50% 50%;">
                                <circle class="text-gray-200 stroke-current" stroke-width="4" fill="transparent" r="40%"
                                    cx="50%" cy="50%"></circle>
                                <circle class="progress text-green-500 stroke-current" stroke-width="4"
                                    fill="transparent" r="30%" cx="50%" cy="50%"></circle>
                            </svg>
                            <span
                                class="progress-text absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-primaryLight text-md lg:text-md">
                                {{$summary_formatted['walletIncomePercentage']}}</span>
                            </span>
                        </div>
                        <div id="tooltip-wallet_income_percentage" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-primaryDark rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            {{$summary_formatted['walletIncomePercentage']}} of your total incomes are from
                            {{$defaultWallet->name}} wallet
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                        <div class="col-span-3">
                            <span class="text-primaryLight text-md lg:text-lg">Total
                                Income</span>
                            <div class="flex gap-2 items-end mt-2">
                                <div class="flex flex-col justify-between ">
                                    <span class="text-gray-500 text-sm">Last month</span>
                                    <span
                                        class="text-primaryLight text-sm">{{$summary_formatted['lastMonthIncome']}}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-gray-500 text-sm">This month</span>
                                    <span class="text-primaryLight text-sm">
                                        {{$summary_formatted['currentMonthIncome']}}</span>
                                </div>
                                <div class="text-xs flex gap-1">
                                    <i
                                        class="fa-solid 
                                        @if((int)str_replace('%', '', $summary_formatted['incomeDifferencePercentage']) < 0) fa-chevron-down @else fa-chevron-up @endif text-primaryLight bg-purple-700 p-0.5 rounded-md">
                                    </i>
                                    <span class="text-primaryLight ">
                                        {{$summary_formatted['incomeDifferencePercentage']}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="h-full flex justify-end">
                            <div
                                class="h-full w-1/2 bg-primaryLight dark:bg-primaryDark rounded-xl p-2 flex justify-center items-center">
                                <i class="fa-solid fa-chevron-right text-primaryDark dark:text-primaryLight"></i>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <x-alert-session-status color="gray" id="no_summary_alert"
                    message="No summary found yet, or you don't have any wallets yet" />
                @endif
            </div>
            {{-- Lower Side --}}
            <div class="space-y-5">
                <div class="flex justify-between items-start">
                    <span class="text-primaryDark dark:text-primaryLight text-xl font-semibold">Transactions
                        History</span>
                    @if(Auth::user()->wallets->where('currency', $defaultCurrency)->count() >= 1)
                    <div class="flex gap-2">
                        <select id="transaction_wallet_filter"
                            class="p-2 rounded-lg bg-primaryLight dark:bg-primaryDark text-md text-primaryDark dark:text-primaryLight">
                            <option value="all" @if(request()->query('wallet') == 'all') selected @endif
                                >All Wallets</option>
                            @foreach (Auth::user()->wallets->where('currency', $defaultCurrency) as $wallet)
                            <option value="{{$wallet->id}}" @if($wallet->id == request()->query('wallet')) selected
                                @endif>
                                {{$wallet->name}}
                            </option>
                            @endforeach
                        </select>
                        <div>
                            <x-primary-button data-tooltip-target="tooltip-add_transaction" type="button"
                                data-tooltip-placement="bottom" class="h-full"
                                data-modal-target="create-transaction-modal"
                                data-modal-toggle="create-transaction-modal">
                                <i class="fa-solid fa-plus text-white dark:text-black"></i>
                            </x-primary-button>
                            <div id="tooltip-add_transaction" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-primaryDark rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                Add new transaction
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </div>
                        <div id="create-transaction-modal" tabindex="-1" aria-hidden="true"
                            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative p-4 w-full max-w-md max-h-full">
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                                    <div
                                        class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            Add new transaction
                                        </h3>
                                        <button type="button"
                                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                            data-modal-toggle="create-transaction-modal">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>
                                    <!-- Modal body -->
                                    <form class="p-4 md:p-5" action="{{ route('transactions.store') }}" method="POST">
                                        @csrf
                                        <div class="mb-5">
                                            <div
                                                class="text-gray-500 dark:text-gray-400 text-sm font-semibold p-2 text-center bg-gray-200 dark:bg-gray-700 rounded-lg w-full">
                                                You are adding a new transaction to
                                                {{$defaultWallet->name}}.
                                            </div>
                                        </div>
                                        <div class="mb-5">
                                            <div class="mb-5">
                                                <label for="transaction_type"
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Transaction
                                                    Type</label>
                                                <select id="transaction_type" name="transaction_type" required
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                    <option selected disabled>Select transaction type</option>
                                                    @forelse ($transactionTypes as $type)
                                                    <option value="{{ $type['value'] }}">{{ $type['label'] }}</option>
                                                    @empty
                                                    <option value="0" disabled>No transaction type available</option>
                                                    @endforelse
                                                </select>
                                            </div>
                                            <div class="mb-5 hidden" id="expense_income_category_input">
                                                <label for="category"
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
                                                <div class="relative w-full">
                                                    <div
                                                        class="flex items-center border bg-gray-50 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 cursor-pointer">
                                                        <div id="selectedCategory" class="flex-grow pr-2">Select a
                                                            category
                                                        </div>
                                                        <i id="categoryIcon"
                                                            class="fa-solid text-lg text-primaryDark dark:text-primaryLight"></i>
                                                    </div>
                                                    <div id="categoryDropdown"
                                                        class="absolute hidden z-10 mt-2 pt-2 bg-white border border-gray-300 text-gray-900 text-sm rounded-md shadow-lg w-full dark:bg-gray-800 dark:border-gray-500 dark:text-white">
                                                        @foreach ($categories as $key => $category)
                                                        <span class="font-semibold my-2 p-2">{{$key}}</span>
                                                        @if (!empty($category))
                                                        <div class="ml-2 p-2">
                                                            <div class="mt-1">
                                                                @forelse ($category as $item)
                                                                <div class="flex items-center py-1 w-full">
                                                                    <div class="mr-2">
                                                                        <i
                                                                            class="fa-solid {{$item->icon}} text-lg text-primaryDark dark:text-primaryLight"></i>
                                                                    </div>
                                                                    <div class="cursor-pointer flex-grow"
                                                                        onclick="selectCategory('{{ $item->name }}', '{{$item->icon}}', '{{$item->id}}')">
                                                                        {{ $item->name }}
                                                                    </div>
                                                                </div>
                                                                @empty
                                                                <span class="text-gray-500">No category available</span>
                                                                @endforelse
                                                            </div>
                                                        </div>
                                                        @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <input type="text" id="category" hidden name="category">
                                            </div>
                                            <div class="mb-5 hidden" id="borrow_lend_input">
                                                <div class="flex items-center ps-4 border border-gray-200 rounded dark:border-gray-700"
                                                    data-tooltip-target="tooltip-borrow_lend">
                                                    <input id="bordered-checkbox-1" type="checkbox"
                                                        name="borrow_lend_return"
                                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                    <label for="bordered-checkbox-1"
                                                        class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Return</label>
                                                </div>
                                                <div id="tooltip-borrow_lend" role="tooltip"
                                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-primaryDark rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700 w-3/4">
                                                    Check this box if the amount is a return of a borrowed or lent
                                                    amount
                                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                                </div>
                                            </div>
                                            <div class="mb-5 hidden" id="internal_transfer_input">
                                                <div class="mb-5">
                                                    <label for="wallet"
                                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">To
                                                        Wallet</label>
                                                    <select id="wallet" name="to_wallet" required
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                        <option selected disabled>Select wallet</option>
                                                        @foreach (Auth::user()->wallets->where('currency',
                                                        $defaultCurrency) as $wallet)
                                                        @if ($wallet->id != $defaultWallet->id)
                                                        <option value="{{$wallet->id}}">{{$wallet->name}}</option>
                                                        @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-5">
                                                    <label for="fee"
                                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Transfer
                                                        Fee</label>
                                                    <div class="relative w-full">
                                                        <div
                                                            class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                                            <span
                                                                class="text-gray-900 dark:text-white text-sm">{{$defaultCurrency}}</span>
                                                        </div>
                                                        <input type="text" id="fee_display" min="0"
                                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                            placeholder="Input transaction fee, it's optional">
                                                    </div>
                                                    <input type="hidden" name="fee" id="fee">
                                                </div>
                                            </div>
                                            <div class="mb-5">
                                                <label for="amount"
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Amount</label>
                                                <div class="relative w-full">
                                                    <div
                                                        class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                                        <span
                                                            class="text-gray-900 dark:text-white text-sm">{{$defaultCurrency}}</span>
                                                    </div>
                                                    <input type="text" id="amount_display" min="0"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                        placeholder="Input amount" required="">
                                                    <input type="hidden" name="amount" id="amount" required>
                                                </div>
                                            </div>
                                            <div class="mb-5">
                                                <label for="date"
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date</label>
                                                <div class="relative max-w-sm">
                                                    <div
                                                        class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                            fill="currentColor" viewBox="0 0 20 20">
                                                            <path
                                                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                                        </svg>
                                                    </div>
                                                    <input datepicker datepicker-autohide type="text"
                                                        datepicker-format="dd MM yyyy" id="date" name="date" required
                                                        value="{{ date('d F Y') }}"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                        placeholder="Select date">
                                                </div>
                                            </div>
                                            <div class="mb-10">
                                                <label for="description"
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                                                <textarea id="description" name="description" rows="4"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                    placeholder="What is this transaction for?"></textarea>
                                            </div>
                                        </div>
                                        <input type="text" value="{{$defaultWallet->id}}" name="wallet_id" hidden>
                                        <div class="flex justify-end">
                                            <x-primary-button>
                                                <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Save
                                            </x-primary-button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div>
                    @forelse ($defaultWallet_transactions as $transaction)
                    <div class="flex justify-between bg-gray-100 dark:bg-gray-900 p-4 rounded-xl gap-2 mb-2">
                        <div class="flex gap-3 items-start">
                            <div
                                class="w-12 h-full rounded-xl bg-white dark:bg-gray-800  justify-center items-center hidden md:flex">
                                <i class="fa-solid {{$transaction['category']['icon']}} text-xl text-primaryDark
                                dark:text-primaryLight"></i>
                            </div>
                            <div class="flex flex-col justify-evenly h-full">
                                <div class="flex gap-2">
                                    <span
                                        class="font-semibold text-primaryDark dark:text-primaryLight">{{$transaction['category']['name']}}
                                    </span>
                                    <div
                                        class="p-1 rounded-xl bg-white dark:bg-gray-800 justify-center items-center flex md:hidden">
                                        <i class="fa-solid {{$transaction['category']['icon']}} text-xs text-primaryDark
                                        dark:text-primaryLight"></i>
                                    </div>
                                </div>
                                <span>
                                    @if ($transaction['to_wallet_id'] != null)
                                    <span class="text-green-500">To</span><span
                                        class="text-primaryDark dark:text-primaryLight">
                                        {{$transaction['to_wallet']['name']}}</span>
                                    @endif
                                    @if ($transaction['from_wallet_id'] != null)
                                    <span class="text-red-500">From</span><span
                                        class="text-primaryDark dark:text-primaryLight">
                                        {{$transaction['from_wallet']['name']}}</span>
                                    @endif
                                </span>
                                <span class="text-md text-gray-500">{{$transaction['description']}}</span>
                                @if ($transaction['child_id'] != null)
                                @php
                                $child_transaction = App\Models\Transaction::find($transaction['child_id']);
                                @endphp
                                @if ($child_transaction)
                                <div class="text-mdflex flex-col gap-1">
                                    <span class="text-gray-500">This transaction has:
                                        {{$child_transaction->description}}</span>
                                    @if($child_transaction->amountIn != null && $child_transaction->amountOut == null)
                                    <span class="text-green-500">+{{
                                        Symfony\Component\Intl\Currencies::getSymbol($child_transaction->toWallet->currency).
                                        ' '
                                        .
                                        number_format($child_transaction->amountIn, 2, '.', ',') }}</span>
                                    @elseif($child_transaction->amountIn == null && $child_transaction->amountOut !=
                                    null)
                                    <span class="text-red-500">-{{
                                        Symfony\Component\Intl\Currencies::getSymbol($child_transaction->fromWallet->currency).
                                        '
                                        ' .
                                        number_format($child_transaction->amountOut, 2, '.', ',') }}</span>
                                    @else
                                    <div class="flex gap-1 justify-end items-end flex-wrap">
                                        <span class="text-green-500">+{{
                                            Symfony\Component\Intl\Currencies::getSymbol($child_transaction->toWallet->currency).
                                            ' ' .
                                            number_format($child_transaction->amountIn, 2, '.', ',') }}</span>
                                        <span class="text-red-500">-{{
                                            Symfony\Component\Intl\Currencies::getSymbol($child_transaction->fromWallet->currency).
                                            ' ' .
                                            number_format($child_transaction->amountOut, 2, '.', ',') }}</span>
                                    </div>
                                    @endif
                                </div>
                                @endif
                                @endif
                            </div>
                        </div>
                        <div class="space-y-5 flex flex-col items-end">
                            @if($transaction['amountIn'] != null && $transaction['amountOut'] == null)
                            <span class="text-green-500">+{{
                                Symfony\Component\Intl\Currencies::getSymbol($transaction['to_wallet']['currency']). ' '
                                .
                                number_format($transaction['amountIn'], 2, '.', ',') }}</span>
                            @elseif($transaction['amountIn'] == null && $transaction['amountOut'] != null)
                            <span class="text-red-500">-{{
                                Symfony\Component\Intl\Currencies::getSymbol($transaction['from_wallet']['currency']). '
                                ' .
                                number_format($transaction['amountOut'], 2, '.', ',') }}</span>
                            @else
                            <div class="flex gap-1 justify-end items-end flex-wrap">
                                <span class="text-green-500">+{{
                                    Symfony\Component\Intl\Currencies::getSymbol($transaction['to_wallet']['currency']).
                                    ' ' .
                                    number_format($transaction['amountIn'], 2, '.', ',') }}</span>
                                <span class="text-red-500">-{{
                                    Symfony\Component\Intl\Currencies::getSymbol($transaction['from_wallet']['currency']).
                                    ' ' .
                                    number_format($transaction['amountOut'], 2, '.', ',') }}</span>
                            </div>
                            @endif
                            <div class="text-gray-500 flex flex-col items-end">
                                <span>{{Carbon\Carbon::parse($transaction['date'])->diffForHumans()}}</span>
                                <span>{{Carbon\Carbon::parse($transaction['date'])->format('d M Y')}}</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <x-alert-session-status color="gray" id="no_transactions_alert"
                        message="No transactions found, or maybe you haven't created any transactions yet" />
                    @endforelse
                    @if (!empty($defaultWallet_transactions))
                    <div class="mt-4">
                        {{ $defaultWallet_transactions->links('vendor.pagination.tailwind') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/dashboard.js') }}">
    </script>
</x-app-layout>