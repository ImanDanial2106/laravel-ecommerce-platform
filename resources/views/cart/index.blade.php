<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Your Shopping Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(empty($cart))
                        <p>Your cart is empty.</p>
                        <a href="{{ route('storefront.index') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md">
                            Continue Shopping
                        </a>
                    @else
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Product</th>
                                        <th scope="col" class="px-6 py-3">Price</th>
                                        <th scope="col" class="px-6 py-3">Quantity</th>
                                        <th scope="col" class="px-6 py-3">Total</th>
                                        <th scope="col" class="px-6 py-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart as $id => $details)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                            {{ $details['name'] }}
                                        </td>
                                        <td class="px-6 py-4">${{ number_format($details['price'], 2) }}</td>
                                        <td class="px-6 py-4">
                                            {{ $details['quantity'] }}
                                        </td>
                                        <td class="px-6 py-4">${{ number_format($details['price'] * $details['quantity'], 2) }}</td>
                                        <td class="px-6 py-4">
                                            <form action="{{ route('cart.remove') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $id }}">
                                                <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 flex justify-end items-center">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Total: ${{ number_format($total, 2) }}</h3>
                            <a href="{{ route('checkout.index') }}" class="ms-4 inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-md font-semibold text-sm hover:bg-green-700">
                                Proceed to Payment
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>