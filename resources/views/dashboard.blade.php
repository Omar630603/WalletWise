<x-app-layout>
    <span class="font-bold text-xl px-8 text-primaryDark dark:text-primaryLight">
        <span class="greetings text-xl"></span>{{ Auth::user()->name }}
    </span>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 px-10 mt-10 sm:pr-10">
        {{-- Left Side --}}
        <div>
            <span>My Wallets</span>
            {{-- Upper Side --}}
            <div>
                <span>Wallet 1</span>
                <span>Wallet 2</span>
                <span>Wallet 3</span>
            </div>
            {{-- Lower Side --}}
            <span>Statistics</span>
            <div>
                <span>Wallet 1</span>
                <span>Wallet 2</span>
                <span>Wallet 3</span>
            </div>
        </div>
        {{-- Right Side --}}
        <div class="sm:col-span-2">
            {{-- Upper Side --}}
            <span>Wallet Summary</span>
            <div>
                <span>Expanses</span>
                <span>Income</span>
            </div>
            <span>Transactions History</span>
            {{-- Lower Side --}}
            <div>
                <span>Wallet 1</span>
                <span>Wallet 2</span>
                <span>Wallet 3</span>
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
</x-app-layout>