<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Order Placed!') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 text-center">
                    
                    <h3 class="text-2xl font-bold text-green-500 mb-4">Thank You!</h3>
                    <p class="mb-4">Your order has been placed successfully.</p>
                    <a href="{{ route('storefront.index') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md">
                        Continue Shopping
                    </a>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>