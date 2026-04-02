<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="flex justify-center">
        <img src="{{ asset('assets/logo/login.jpeg') }}" alt="Logo" class="h-44 w-full object-contain rounded-lg">
    </div>
    <h1 class="text-center pt-6  text-2xl font-bold text-gray-800 tracking-wide">
        Login
    </h1>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Username/Email Field -->
        <div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <input type="text" id="login" name="login" value="{{ old('login') }}"
                    placeholder="Enter your username or email"
                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2160BC] focus:border-transparent transition duration-200"
                    required autofocus autocomplete="username">
            </div>
            <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>

        <!-- Password Field -->
        <div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                </div>
                <input type="password" id="password" name="password" placeholder="Enter your password"
                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2160BC] focus:border-transparent transition duration-200"
                    required autocomplete="current-password">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input type="checkbox" id="remember_me" name="remember"
                    class="h-4 w-4 text-[#2160BC] focus:ring-[#2160BC] border-gray-300 rounded">
                <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                    {{ __('Remember me') }}
                </label>
            </div>

        </div>

        <!-- Login Button -->
        <button type="submit"
            class="w-full bg-[#2160BC] text-white py-3 px-4 rounded-lg hover:bg-[#1a4a96] focus:ring-2 focus:ring-[#2160BC] focus:ring-offset-2 transition duration-200 font-medium">
            {{ __('Sign In') }}
        </button>
    </form>
</x-guest-layout>
