<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- This is now one big form that submits to checkout.process -->
                    <form action="{{ route('checkout.process') }}" method="POST">
                        @csrf
                        
                        <!-- This block will show errors if the user forgets to add their address or phone -->
                        @if ($errors->any())
                            <div class="mb-4 p-4 bg-red-100 dark:bg-red-800 text-red-800 dark:text-red-200 rounded-lg">
                                <p class="font-bold">Please correct the errors below:</p>
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Layout Grid: 2/3 for Shipping, 1/3 for Summary -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            
                            <!-- 1. NEW Shipping Details Form -->
                            <div class="md:col-span-2">
                                <h3 class="text-lg font-medium mb-4">Shipping Details</h3>
                                <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                                    Please enter your shipping address to complete the order.
                                </p>
                                <div class="space-y-4">
                                    <!-- Name -->
                                    <div>
                                        <x-input-label for="name" :value="__('Full Name')" />
                                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $customer->name)" required />
                                    </div>

                                    <!-- Email (Read-only) -->
                                    <div>
                                        <x-input-label for="email" :value="__('Email Address')" />
                                        <x-text-input id="email" class="block mt-1 w-full bg-gray-100 dark:bg-gray-700" type="email" name="email" :value="$customer->email" readonly disabled />
                                    </div>

                                    <!-- Phone (REQUIRED) -->
                                    <div>
                                        <x-input-label for="phone" :value="__('Phone Number')" />
                                        <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $customer->phone)" required placeholder="e.g., 012-3456789" />
                                    </div>

                                    <!-- Address (REQUIRED) -->
                                    <div>
                                        <x-input-label for="address" :value="__('Full Shipping Address')" />
                                        <textarea id="address" name="address" rows="4" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required placeholder="123 Jalan Ampang, 50450 Kuala Lumpur">{{ old('address', $customer->address) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. Order Summary (now inside the form) -->
                            <div class="md:col-span-1">
                                <h3 class="text-lg font-medium mb-4">Order Summary</h3>
                                <div class="space-y-2 mb-4">
                                    @foreach($cart as $id => $details)
                                    <div class="flex justify-between">
                                        <span class="text-sm">{{ $details['name'] }} (x{{ $details['quantity'] }})</span>
                                        <span class="text-sm font-medium">${{ number_format($details['price'] * $details['quantity'], 2) }}</span>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="border-t border-gray-200 dark:border-gray-700 pt-4 flex justify-between items-center">
                                    <span class="text-base font-bold">Total</span>
                                    <span class="text-base font-bold">${{ number_format($total, 2) }}</span>
                                </div>

                                <!-- Payment Button (is now the submit button) -->
                                <div class="mt-6">
                                    <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3 bg-green-600 text-white rounded-md font-semibold text-sm hover:bg-green-700">
                                        Confirm Payment & Place Order
                                    </button>
                                    <a href="{{ route('cart.index') }}" class="w-full text-center mt-2 inline-block text-sm text-gray-600 dark:text-gray-400 hover:underline">
                                        Back to Cart
                                    </a>
                                </div>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>