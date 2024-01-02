<x-guest-layout>
    <div class="min-h-screen grid grid-flow-row grid-cols-1 md:grid-cols-3 grid-rows-1 gap-0 mb-4">
        <div
            class="hidden md:flex flex-col justify-center items-center col-span-2 relative bg-gray-100 dark:bg-gray-900 mx-5 rounded-3xl">
            <div class="w-5 h-5 bg-primaryDark dark:bg-primaryLight rounded-full mt-4 animate-pulse m-5"></div>
            <x-application-logo class="w-28 h-28 lg:w-32 lg:h-32 fill-current text-gray-500" />
            <span
                class="font-semibold text-primaryDark dark:text-primaryLight text-2xl my-4">{{ config('app.name') }}</span>
            <span class="text-gray-900 dark:text-gray-100 text-xl mx-4 text-center">
                {{config('app.description.0')}}
            </span>
            <div
                class="w-20 h-40 bg-primaryDark dark:bg-primaryLight rounded-tr-full rounded-br-full absolute top-0 left-0 mt-8 hover:w-80 transition-all ease-in-out 
                flex justify-center items-center text-center text-transparent hover:text-gray-100 hover:dark:text-gray-900 px-4 text-lg">
                {{config('app.description.1')}}
            </div>
            <div
                class="w-20 h-40 bg-primaryDark dark:bg-primaryLight rounded-tl-full rounded-bl-full absolute bottom-0 right-0 mb-8 hover:w-80 transition-all ease-in-out 
                flex justify-center items-center text-center text-transparent hover:text-gray-100 hover:dark:text-gray-900 px-4 text-lg">
                {{config('app.description.2')}}
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mx-10 lg:mx-16">
            <form method="POST" action="{{ route('login') }}" class="w-full">
                <div class="flex flex-col justify-center items-start mb-4">
                    <span
                        class="font-semibold text-primaryDark dark:text-primaryLight text-2xl mb-4">{{ __('Sign In') }}</span>
                    <span
                        class="text-gray-900 dark:text-gray-100 text-xl mb-4">{{ __('Please sign in to continue') }}</span>
                </div>
                @csrf
                <div>
                    <x-input-label for="login" :value="__('Email or Username')" />

                    <x-text-input id="login" class="block mt-1 w-full" type="text" name="login" :value="old('login')"
                        required autofocus />

                    <x-input-error :messages="$errors->get('login')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />

                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                        autocomplete="current-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                <div class="flex items-center justify-between mt-4 gap-3">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox"
                            class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-blue-600 shadow-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
                            name="remember">
                        <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                    </label>
                    @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800"
                        href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                    @endif
                </div>
                <div class="flex items-center justify-between mt-8 gap-5">
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800"
                        href="{{ route('register') }}">
                        {{ __('Create new account?') }}
                    </a>

                    <x-primary-button class="ms-3">
                        {{ __('Sign In') }}
                    </x-primary-button>
                </div>
            </form>
            <x-primary-button class="mt-10 flex gap-5 items-center justify-between w-full" id="googleBTN">
                {{ __('Sign in with Google') }}
                <img src="https://fonts.gstatic.com/s/i/productlogos/googleg/v6/24px.svg" alt="">
            </x-primary-button>
            <div class="mt-4">
                <x-auth-session-status class="mb-4" :status="session('status')" />
            </div>
        </div>
    </div>
    <script>
        document.getElementById("googleBTN").addEventListener("click", () => {
            window.location.href = "/auth/google/redirect";
        });
    </script>
</x-guest-layout>