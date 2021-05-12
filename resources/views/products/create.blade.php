<x-app-layout title="Add New Product">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            {{ __('Add New Product') }}
        </h2>

        @if ($errors->any())
            <div class="min-w-0 p-4 text-white bg-red-600 rounded-lg shadow-xs">
                <h4 class="mb-4 font-semibold">
                    <strong>Whoops!</strong> There were some problems with your input.
                </h4>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.store') }}" method="POST">
        @csrf
        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">Name</span>
                <input type="text" name="name" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-green-400 focus:outline-none focus:shadow-outline-green dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="Name" />
            </label>

            <label class="block mt-4 text-sm">
                <span class="text-gray-700 dark:text-gray-400">Detail</span>
                <textarea name="detail" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-textarea focus:border-green-400 focus:outline-none focus:shadow-outline-green dark:focus:shadow-outline-gray" rows="3" placeholder="Detail"></textarea>
            </label>
            <div class="mt-4">
                <button type="submit" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-lg active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-green">
                    Submit
                </button>
            </div>
        </div>
        </form>
</x-app-layout>