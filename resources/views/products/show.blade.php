<x-app-layout title="{{ $product->name }}">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            {{ $product->name }}
        </h2>
    </div>
    <div class="pull-right">
        <a class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100" href="{{ route('products.index') }}"> Back</a>
    </div>

    <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
         {{ $product->name }}
    </h4>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            {{ $product->detail }}
        </p>
    </div>

</x-app-layout>