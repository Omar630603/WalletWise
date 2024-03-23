<x-app-layout>
    <div class="px-5 sm:pr-10">
        {{-- Alerts --}}
        <div class="pl-5 pr-10 mb-4">
        </div>
        {{-- Search and Filters --}}
        <div class="flex justify-center lg:justify-between items-baseline flex-wrap gap-3">
            <span class="font-bold text-xl text-primaryDark dark:text-primaryLight">
                Wallets
            </span>
            <div
                class="flex items-baseline justify-center flex-column flex-wrap md:flex-row space-y-2 md:space-y-0 pb-4 lg:justify-between gap-3">
                <div>
                    <button id="dropdownActionButton" data-dropdown-toggle="dropdownAction"
                        class="inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                        type="button">
                        <span class="sr-only">Action button</span>
                        Action
                        <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 4 4 4-4" />
                        </svg>
                    </button>
                    <!-- Dropdown menu -->
                    <div id="dropdownAction"
                        class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                        <ul class="py-1 text-sm text-gray-700 dark:text-gray-200"
                            aria-labelledby="dropdownActionButton">
                            <li>
                                <a href="#"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Reward</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Promote</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Activate
                                    account</a>
                            </li>
                        </ul>
                        <div class="py-1">
                            <a href="#"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete
                                User</a>
                        </div>
                    </div>
                </div>
                <label for="table-search" class="sr-only">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                    <input type="text" id="table-search-wallets"
                        class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Search for wallets">
                </div>
            </div>
        </div>
        {{-- Table --}}
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        @if (!empty($wallets))
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Balance
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Minimum Balance
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Actions
                        </th>
                        @else
                        <th scope="col" class="px-6 py-3">
                            Wallets
                        </th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($wallets as $wallet)
                    @php
                    $wallet_type_label = App\Models\Wallet::TYPES[array_search($wallet['type'],
                    array_column(App\Models\Wallet::TYPES, 'value'))]['label'];
                    $wallet_formatted_balance = Symfony\Component\Intl\Currencies::getSymbol($wallet['currency']) . ' '
                    .
                    number_format($wallet['balance'], 2, '.', ',');
                    $wallet_formatted_minimum_balance =
                    Symfony\Component\Intl\Currencies::getSymbol($wallet['currency'])
                    . ' ' . number_format($wallet['minimum_balance'], 2, '.', ',');
                    @endphp
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row"
                            class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                            <div
                                class="w-10 h-10 rounded-full bg-white dark:bg-gray-900 flex justify-center items-center self-end justify-self-end">
                                <i class="fa-solid {{$wallet['icon']}} text-lg"></i>
                            </div>
                            <div class="ps-3">
                                <div class="text-base font-semibold">{{$wallet['name']}}</div>
                                <div class="font-normal text-gray-500">{{$wallet_type_label}}
                                </div>
                            </div>
                        </th>
                        <td class="px-6 py-4">
                            {{$wallet_formatted_balance}}
                        </td>
                        <td class="px-6 py-4">
                            {{$wallet_formatted_minimum_balance}}
                        </td>
                        <td class="px-6 py-4">
                            <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit
                                Wallet</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="pt-5">
                            <x-alert-session-status color=" gray" id="no_wallets"
                                message="No Wallets found, or maybe you haven't created any wallets yet. Create a new wallet from dashboard." />
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if (!empty($wallets))
        <div class="ml-1 mt-4">
            {{ $wallets->links('vendor.pagination.tailwind') }}
        </div>
        @endif
    </div>
</x-app-layout>