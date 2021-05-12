<x-app-layout title="Products">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            {{ __('Products') }}
        </h2>
        <div class="pull-right">
            <a class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100" href="{{ route('products.create') }}"> Create New Product</a>
        </div>
            @if ($message = Session::get('success'))
                <div class="min-w-0 p-4 text-white bg-green-600 rounded-lg shadow-xs">
                <p>
                    {{ $message }}
                </p>
            </div>
            @endif
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">STT</th>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Details</th>
                            <th class="px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @foreach ($products as $product)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3">
                                {{ ++$i }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $product->name }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                               {{ $product->detail }}
                            </td>
                            <td class="px-4 py-3">
                                <form action="{{ route('products.destroy',$product->id) }}" method="POST">
                                <div class="flex items-center space-x-4 text-sm">
                                    <a class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-green-600 rounded-lg focus:outline-none focus:shadow-outline-gray" href="{{ route('products.show',$product->id) }}">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor"
                                            viewBox="0 0 24 24">
                                            <path 
                                                d="M12.015 7c4.751 0 8.063 3.012 9.504 4.636-1.401 1.837-4.713 5.364-9.504 5.364-4.42 0-7.93-3.536-9.478-5.407 1.493-1.647 4.817-4.593 9.478-4.593zm0-2c-7.569 0-12.015 6.551-12.015 6.551s4.835 7.449 12.015 7.449c7.733 0 11.985-7.449 11.985-7.449s-4.291-6.551-11.985-6.551zm-.015 5c1.103 0 2 .897 2 2s-.897 2-2 2-2-.897-2-2 .897-2 2-2zm0-2c-2.209 0-4 1.792-4 4 0 2.209 1.791 4 4 4s4-1.791 4-4c0-2.208-1.791-4-4-4z">
                                            </path>
                                        </svg>

                                    </a>
                                    <a class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-green-600 rounded-lg focus:outline-none focus:shadow-outline-gray" href="{{ route('products.edit',$product->id) }}">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                            </path>
                                        </svg>
                                    </a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-100">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </div>
                                 </form>
                            </td>
                        </tr>
                         @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {!! $products->links() !!}
      
</x-app-layout>