<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('New Arrivals') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200 rounded-lg shadow-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($products as $product)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                        @php
                            $imageUrl = $product->image 
                                        ? asset('storage/' . $product->image) 
                                        : asset('images/sweatergrey.jpg');
                        @endphp
                        <img class="w-full aspect-square object-cover" src="{{ $imageUrl }}" alt="{{ $product->name }}">
                        <div class="p-4">
                            <h3 class="font-semibold text-md text-gray-900 dark:text-gray-100 truncate">{{ $product->name }}</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">${{ number_format($product->price, 2) }}</p>
                            
                            @if($product->stock > 0)
                                <p class="mt-1 text-xs text-green-500">In Stock ({{ $product->stock }})</p>
                            @else
                                <p class="mt-1 text-xs text-red-500">Out of Stock</p>
                            @endif
                            
                            <div class="mt-4">
                                @if($product->stock > 0)
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white">
                                            Add to Cart
                                        </button>
                                    </form>
                                @else
                                    <button class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-400 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest" disabled>
                                        Out of Stock
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 sm:col-span-2 lg:col-span-3 text-center text-gray-500 dark:text-gray-400">
                        No products found.
                    </div>
                @endforelse
            </div>
            
            <div class="mt-6">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>