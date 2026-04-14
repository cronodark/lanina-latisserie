@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">

    <!-- LEFT (FORM) -->
    <div class="flex items-center justify-center px-4 sm:px-6 md:px-10 lg:px-16 xl:px-24 2xl:px-32 py-10 bg-[#ADC178]">
        <div class="w-full max-w-md sm:max-w-lg lg:max-w-xl">

            <!-- HEADER -->
            <div class="flex justify-between items-center">
                <h2 class="font-serif text-2xl sm:text-3xl lg:text-4xl xl:text-5xl font-bold text-white">
                    Login
                </h2>
                <a href="{{ route('register') }}"
                    class="font-serif text-sm sm:text-base lg:text-lg font-bold text-white hover:underline">
                    Register
                </a>
            </div>

            <p class="mt-3 sm:mt-4 text-white text-sm sm:text-base lg:text-lg leading-relaxed">
                Selamat datang kembali! Silakan masuk untuk melanjutkan aktivitas Anda.
            </p>

            <!-- FORM -->
            <form action="{{ route('login') }}" method="POST" class="mt-8 sm:mt-10 space-y-5 sm:space-y-6">
                @csrf

                <!-- EMAIL -->
                <div>
                    <label class="block text-sm sm:text-base font-medium text-white">
                        Email
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="mt-2 w-full rounded-md bg-white px-4 py-2 sm:py-3 text-sm sm:text-base text-gray-900 outline-none focus:ring-2 focus:ring-[#6A7941]" />

                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <p class="mt-4 text-white text-sm sm:text-base lg:text-lg leading-relaxed">
                    Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis suscipit eaque,
                    iste dolor cupiditate blanditiis ratione.
                </p>

                <!-- FORM -->
                <form action="{{ route('login.submit') }}" method="POST" class="mt-10 space-y-6 lg:space-y-8">
                    @csrf

                    <!-- EMAIL -->
                    <div>
                        <label class="block text-sm lg:text-base font-medium text-white">
                            Email address
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="mt-2 w-full rounded-md bg-white px-4 py-2 lg:py-3 text-sm lg:text-base text-gray-900 outline-none focus:ring-2 focus:ring-[#6A7941]" />
                        @error('email')
                            <p class="mt-2 text-sm text-red-100">{{ $message }}</p>
                        @enderror
                    </div>

                    <input type="password" name="password" required
                        class="mt-2 w-full rounded-md bg-white px-4 py-2 sm:py-3 text-sm sm:text-base text-gray-900 outline-none focus:ring-2 focus:ring-[#6A7941]" />

                        <input type="password" name="password" required
                            class="mt-2 w-full rounded-md bg-white px-4 py-2 lg:py-3 text-sm lg:text-base text-gray-900 outline-none focus:ring-2 focus:ring-[#6A7941]" />
                        @error('password')
                            <p class="mt-2 text-sm text-red-100">{{ $message }}</p>
                        @enderror
                    </div>

                <!-- BUTTON -->
                <button type="submit"
                    class="mt-10 w-full sm:w-2/3 lg:w-4/5 mx-auto block rounded-lg bg-[#6A7941] py-3 text-sm sm:text-base font-semibold text-white hover:bg-[#5E702A] transition duration-300 shadow-md hover:shadow-lg">
                    Masuk
                </button>

            </form>
        </div>
    </div>

    <!-- RIGHT (IMAGE) -->
    <div class="hidden lg:block h-screen">
        <img src="{{ asset('images/login.png') }}" alt="Login Image" class="w-full h-full object-cover">
    </div>

</div>
@endsection
