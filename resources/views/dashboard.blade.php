<x-app-layout>
    <span class="font-bold text-xl px-8 text-primaryDark dark:text-primaryLight">
        <span class="greetings text-xl"></span>{{ Auth::user()->name }}
    </span>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 px-10 mt-10 sm:pr-10">
        {{-- Left Side --}}
        <div class="space-y-8">
            {{-- Upper Side --}}
            <div class="space-y-5">
                <span class="text-primaryDark dark:text-primaryLight text-lg font-semibold">My Wallets</span>
                <div
                    class="flex flex-col justify-center items-center shadow rounded-xl px-2 py-4 text-primaryDark dark:text-primaryLight space-y-8 bg-gray-100 dark:bg-gray-900">
                    @if (Auth::user()->wallets->count() == 0)
                    <span class="text-center p-2">You don't have any wallets yet.</span>
                    <x-primary-button data-modal-target="create-wallet-modal" data-modal-toggle="create-wallet-modal"
                        type="button">
                        Create Wallet
                    </x-primary-button>
                    @else
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
                                                        <i
                                                            class="fa-solid fa-building-columns text-2xl text-gray-500"></i>
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
            </div>
            {{-- Lower Side --}}
            <div class="space-y-5">
                <span class="text-primaryDark dark:text-primaryLight text-lg font-semibold">Statistics</span>
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
                <span class="text-primaryDark dark:text-primaryLight text-lg font-semibold">Wallet Summary</span>
                <div>
                    <span>Expanses</span>
                    <span>Income</span>
                </div>
            </div>
            <div class="space-y-5">
                <span class="text-primaryDark dark:text-primaryLight text-lg font-semibold">Transactions History</span>
                {{-- Lower Side --}}
                <div>
                    <span>Wallet 1</span>
                    <span>Wallet 2</span>
                    <span>Wallet 3</span>
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