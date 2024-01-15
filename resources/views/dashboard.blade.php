<x-app-layout>
    <div class="px-8">
        @if (session('status') == 'wallet-created')
        <x-alert-session-status class="mb-4" color="green" message="Wallet has been created successfully" />
        @elseif (session('status') == 'wallet-not-created')
        <x-alert-session-status class="mb-4" color="red" message="Wallet has not been created for some reason" />
        @endif
    </div>
    <span class="font-bold text-xl px-8 text-primaryDark dark:text-primaryLight">
        <span class="greetings text-xl"></span>{{ Auth::user()->name }}
    </span>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 px-10 mt-10 sm:pr-10">
        {{-- Left Side --}}
        <div class="space-y-8">
            {{-- Upper Side --}}
            <div class="space-y-5">
                <span class="text-primaryDark dark:text-primaryLight text-xl font-semibold">My Wallets</span>
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
                <div
                    class="shadow rounded-xl p-4 text-primaryDark dark:text-primaryLight bg-gray-100 dark:bg-gray-900 ">
                    <div
                        class="cursor-pointer justify-between gap-2 items-center @if(Auth::user()->wallets->count() > 1) flex @endif">
                        @if($defaultWallet != null)
                        <button type="button"
                            class="w-8 h-8 rounded-full bg-primaryDark dark:bg-gray-800 @if(Auth::user()->wallets->count() <= 1) hidden @endif">
                            <i class="fa-solid fa-chevron-left text-white"></i>
                        </button>
                        <div
                            class="grid grid-cols-2 items-start bg-primaryDark dark:bg-gray-800 rounded-xl px-6 py-4 text-primaryLight relative">
                            <div class="absolute w-10 h-10 bg-primaryLight rounded-full top-1 right-1 opacity-40"></div>
                            <div class="absolute w-16 h-16 bg-primaryLight rounded-full top-3 right-5 opacity-60"></div>
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
                        <button type="button"
                            class="w-8 h-8 rounded-full bg-primaryDark dark:bg-gray-800 @if(Auth::user()->wallets->count() <= 1) hidden @endif">
                            <i class="fa-solid fa-chevron-right text-white"></i>
                        </button>
                        @else
                        <div
                            class="grid grid-cols-2 items-start bg-primaryDark dark:bg-gray-800 rounded-xl px-6 py-4 text-primaryLight relative">
                            <div class="absolute w-10 h-10 bg-primaryLight rounded-full top-1 right-1 opacity-40"></div>
                            <div class="absolute w-16 h-16 bg-primaryLight rounded-full top-3 right-5 opacity-60"></div>
                            <div class="absolute w-10 h-10 bg-primaryLight rounded-full bottom-6 left-1 opacity-10">
                            </div>
                            <div
                                class="absolute w-20 h-10 bg-primaryLight rounded-tl-full rounded-tr-full bottom-0 left-3 opacity-20">
                            </div>
                            <div class="flex flex-col justify-between items-start mt-2">
                                <span class="text-lg font-semibold">0.00</span>
                                <span class="text-md font-semibold my-5">No wallet type</span>
                                <span class="text-md font-semibold mt-8">No wallet name</span>
                            </div>
                            <div
                                class="w-16 h-16 rounded-full bg-white dark:bg-gray-900 flex justify-center items-center self-end justify-self-end">
                                <i class="fa-solid fa-wallet text-2xl text-gray-900 dark:text-white"></i>
                            </div>
                        </div>
                        @endif
                    </div>
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
                                            <option selected="">Select wallet type</option>
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
                                            <option selected="">Select currency</option>
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
                                        <input type="number" name="initial_balance" id="initial_balance" min="0"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                            placeholder="0" required="">
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
                                            <input type="hidden" id="selected-color" name="color" value="gray-500">
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
                <div>
                    <span>Wallet 1</span>
                    <span>Wallet 2</span>
                    <span>Wallet 3</span>
                </div>
            </div>
        </div>
        {{-- Right Side --}}
        <div class="sm:col-span-2 space-y-8">
            {{-- Upper Side --}}
            <div class="space-y-5">
                <span class="text-primaryDark dark:text-primaryLight text-xl font-semibold">Wallet Summary</span>
                <div>
                    <span>Expanses</span>
                    <span>Income</span>
                </div>
            </div>
            <div class="space-y-5">
                <div>
                    <span class="text-primaryDark dark:text-primaryLight text-xl font-semibold">Transactions
                        History</span>
                </div>
                {{-- Lower Side --}}
                <div>
                    @forelse ($defaultWallet_transactions as $transaction)
                    <div class="flex justify-between bg-gray-100 dark:bg-gray-900 p-4 rounded-xl gap-2">
                        <div class="flex gap-3 items-start">
                            <div
                                class="w-14 h-full rounded-xl bg-white dark:bg-gray-800 flex justify-center items-center">
                                <i class="fa-solid {{$transaction->category->icon}} text-2xl text-gray-500"></i>
                            </div>
                            <div class="flex flex-col justify-evenly h-full">
                                <span
                                    class="font-semibold text-primaryDark dark:text-primaryLight">{{$transaction->category->name}}</span>
                                <span class="text-md text-gray-500">{{$transaction->description}}</span>
                            </div>
                        </div>
                        <div class="space-y-5">
                            @if($transaction->amountIn != null && $transaction->amountOut == null)
                            <span class="text-green-500">+{{
                                Symfony\Component\Intl\Currencies::getSymbol($defaultWallet->currency). ' ' .
                                number_format($transaction->amountIn, 2, '.', ',') }}</span>
                            @elseif($transaction->amountIn == null && $transaction->amountOut != null)
                            <span class="text-red-500">-{{
                                Symfony\Component\Intl\Currencies::getSymbol($defaultWallet->currency). ' ' .
                                number_format($transaction->amountOut, 2, '.', ',') }}</span>
                            @else
                            <div class="flex flex-col gap-1">
                                <span class="text-green-500">+{{
                                    Symfony\Component\Intl\Currencies::getSymbol($defaultWallet->currency). ' ' .
                                    number_format($transaction->amountIn, 2, '.', ',') }}</span>
                                <span class="text-red-500">-{{
                                    Symfony\Component\Intl\Currencies::getSymbol($defaultWallet->currency). ' ' .
                                    number_format($transaction->amountOut, 2, '.', ',') }}</span>
                            </div>
                            @endif
                            <div class="text-gray-500 flex flex-col items-end">
                                <span>{{Carbon\Carbon::parse($transaction->date)->diffForHumans()}}</span>
                                <span>{{Carbon\Carbon::parse($transaction->date)->format('d M Y')}}</span>
                            </div>
                        </div>
                    </div>
                    @empty

                    @endforelse
                    <div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var today = new Date();
        var curHr = today.getHours();
        var greetings = document.querySelector('.greetings');
        if (curHr < 12) {
            greetings.innerHTML = 'Good Morning, ';
        } else if (curHr < 18) {
            greetings.innerHTML = 'Good Afternoon, ';
        } else {
            greetings.innerHTML = 'Good Evening, ';
        }
    </script>
    <script>
        document.querySelectorAll('.icon').forEach(icon => {
            icon.addEventListener('click', function() {
                document.querySelector('.icon.selected')?.classList.remove('selected');
                this.classList.add('selected');
                document.getElementById('selected-icon').value = this.dataset.icon;
                document.querySelectorAll('.icon').forEach(icon => {
                    icon.classList.remove('border-2');
                    icon.classList.remove('p-2');
                    icon.classList.remove('rounded-lg');
                    icon.classList.remove('border-primaryDark');
                    icon.classList.remove('dark:border-primaryLight');
                });
                this.classList.add('border-2');
                this.classList.add('p-2');
                this.classList.add('rounded-lg');
                this.classList.add('border-primaryDark');
                this.classList.add('dark:border-primaryLight');
            });
        });
        document.querySelectorAll('.color').forEach(color => {
            color.addEventListener('click', function() {
                document.querySelector('.color.selected')?.classList.remove('selected');
                this.classList.add('selected');
                document.getElementById('selected-color').value = 'text-' + this.dataset.color;
                document.querySelectorAll('.icon i').forEach(icon => {
                    icon.className = icon.className.replace(/text-\w+-500/, 'text-' + this.dataset.color);
                });
            });
        });
    </script>
</x-app-layout>