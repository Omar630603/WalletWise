<x-guest-layout>
    <div class="flex flex-col justify-center items-center">
        <div class="text-gray-900 dark:text-gray-100 text-2xl mb-4">
            {{ __('Welcome to') }} <span class="font-semibold text-red-300">{{ config('app.name') }}</span>
        </div>
        <div class="text-gray-900 dark:text-gray-100 text-xl mb-4">
            {{ __('Please login or register to continue') }}
        </div>
    </div>
</x-guest-layout>