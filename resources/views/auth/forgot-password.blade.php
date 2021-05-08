<x-guest-layout title="{{ __('auth.Forgot password') }}">
    <div class="flex items-center min-h-screen p-6 bg-gray-50 dark:bg-gray-900">
        <div class="flex-1 h-full max-w-md mx-auto overflow-hidden bg-white rounded-lg shadow-xl dark:bg-gray-800">
            <div class="flex flex-col overflow-y-auto md:flex-row">
                <div class="flex-1 items-center justify-center p-6 sm:p-12 md:w-1/2">
                    <div class="w-full">
                        <img class="w-32 h-32 mx-auto" src="{{ asset('img/icone.png') }}">
                        <h1 class="mb-4 text-center text-xl font-semibold text-gray-700 dark:text-gray-200">{{ __('auth.Forgot password') }}</h1>
                        @if ($errors->any())
                        <div class="mb-4">
                            <div class="font-medium text-red-600">{{ __('message.Whoops! Something went wrong.') }}</div>

                            <ul class="mt-3 text-sm text-red-600 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        @if (session('status'))
                        <div class="mb-4 text-sm font-medium text-green-600">
                            {{ session('status') }}
                        </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            <label class="block text-sm">
                                <span class="text-gray-700 dark:text-gray-400">Email</span>
                                <input
                                    class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-green-400 focus:outline-none focus:shadow-outline-green dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                    placeholder="jane@actcms.work" name="email" :value="old('email')" required autofocus />
                            </label>

                            <button
                                class="block w-full px-4 py-2 mt-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-lg active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-green"
                                type="submit">
                                {{ __('auth.Email Password Reset Link') }}
                            </button>
                        </form>
                        <p class="mt-4 text-center">
                            <a class="text-sm font-medium text-green-600 dark:text-green-400 hover:underline" href="{{ route('login') }}">
                                {{ __('auth.Login') }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
