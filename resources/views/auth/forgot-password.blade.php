<x-guest-layout>
    <div class="min-h-screen grid grid-flow-row grid-cols-1 md:grid-cols-3 grid-rows-1 gap-0 mb-4">
        <div class="flex flex-col justify-center items-center mx-10 lg:mx-16">
            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400 text-center">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </div>
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                        required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <div class="flex items-center justify-end mt-4">
                    <x-primary-button>
                        {{ __('Email Password Reset Link') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
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
    </div>
</x-guest-layout>