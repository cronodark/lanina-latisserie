@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">

        <!-- LEFT (FORM) -->
        <div class="flex items-center justify-center px-4 sm:px-6 md:px-10 lg:px-16 xl:px-24 2xl:px-32 py-10 bg-[#ADC178]">
            <div class="w-full max-w-md sm:max-w-lg lg:max-w-xl">

                <!-- HEADER -->
                <div class="flex justify-between items-center">
                    <h2 class="font-serif text-2xl sm:text-3xl lg:text-4xl xl:text-5xl font-bold text-white">
                        Register
                    </h2>
                    <a href="{{ route('login') }}"
                        class="font-serif text-sm sm:text-base lg:text-lg font-bold text-white hover:underline">
                        Login
                    </a>
                </div>

                <p class="mt-3 sm:mt-4 text-white text-sm sm:text-base lg:text-lg leading-relaxed">
                    Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis suscipit eaque.
                </p>

                <!-- FORM -->
                <form action="#" method="POST" class="mt-8 sm:mt-10 space-y-5 sm:space-y-6">

                    <!-- Nama -->
                    <div>
                        <label class="block text-sm sm:text-base font-medium text-white">
                            Nama Lengkap
                        </label>
                        <input type="text" required
                            class="mt-2 w-full rounded-md bg-white px-4 py-2 sm:py-3 text-sm sm:text-base text-gray-900 outline-none focus:ring-2 focus:ring-[#6A7941]" />
                    </div>

                    <!-- No Telp -->
                    <div>
                        <label class="block text-sm sm:text-base font-medium text-white">
                            Nomor Telepon
                        </label>
                        <input type="text" required
                            class="mt-2 w-full rounded-md bg-white px-4 py-2 sm:py-3 text-sm sm:text-base text-gray-900 outline-none focus:ring-2 focus:ring-[#6A7941]" />
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm sm:text-base font-medium text-white">
                            Email
                        </label>
                        <input type="email" required
                            class="mt-2 w-full rounded-md bg-white px-4 py-2 sm:py-3 text-sm sm:text-base text-gray-900 outline-none focus:ring-2 focus:ring-[#6A7941]" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm sm:text-base font-medium text-white">
                            Password
                        </label>
                        <input type="password" required
                            class="mt-2 w-full rounded-md bg-white px-4 py-2 sm:py-3 text-sm sm:text-base text-gray-900 outline-none focus:ring-2 focus:ring-[#6A7941]" />
                    </div>

                    <!-- Konfirmasi -->
                    <div>
                        <label class="block text-sm sm:text-base font-medium text-white">
                            Konfirmasi Password
                        </label>
                        <input type="password" required
                            class="mt-2 w-full rounded-md bg-white px-4 py-2 sm:py-3 text-sm sm:text-base text-gray-900 outline-none focus:ring-2 focus:ring-[#6A7941]" />
                    </div>

                    <!-- BUTTON -->
                    <button type="submit"
                        class="mt-6 w-full sm:w-2/3 lg:w-4/5 mx-auto block rounded-lg bg-[#6A7941] py-3 text-sm sm:text-base font-semibold text-white hover:bg-[#5E702A] transition duration-300 shadow-md hover:shadow-lg">
                        Daftar
                    </button>

                </form>
            </div>
        </div>

        <!-- RIGHT (IMAGE) -->
        <div class="hidden lg:block h-screen">
            <img src="{{ asset('images/login.png') }}" alt="Register Image" class="w-full h-full object-cover">
        </div>

    </div>
@endsection
