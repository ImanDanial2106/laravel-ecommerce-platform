<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Products') }}
            </h2>
            <a href="{{ route('products.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                Add New Product
            </a>
        </div>
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
                            
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-500">
                                @if($product->stock > 0)
                                    <span>In Stock ({{ $product->stock }})</span>
                                @else
                                    <span class="text-red-500">Out of Stock</span>
                                @endif
                            </p>
                            
                            <div class="mt-4 flex items-center space-x-4">
                                <a href="{{ route('products.edit', $product->id) }}" class="font-medium text-sm text-indigo-600 dark:text-indigo-500 hover:underline">Edit</a>
                                
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-medium text-sm text-red-600 dark:text-red-500 hover:underline">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-1 sm:col-span-2 lg:col-span-3 text-center text-gray-500 dark:text-gray-400">
                        No products found. <a href="{{ route('products.create') }}" class="text-indigo-500 hover:underline">Add one!</a>
                    </div>
                @endforelse
            </div>
            
            <div class="mt-6">
                {{ $products->links() }}
            </div>

        </div>
    </div>
</x-app-layout>