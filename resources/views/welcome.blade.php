@extends('layouts.new')

@section('title', 'Welcome to Pengaduan Masyarakat')

@section('content')
    <div class="bg-white">
        <div class="min-h-screen flex flex-col-2 gap-32 items-center justify-center ">
            <div class="text-left"> {{-- text --}}
                <h1 class="text-4xl font-bold text-gray-800 mb-4">Welcome to <br> Pengaduan Masyarakat</h1>
                <p class="text-lg text-gray-600 mb-6">Your platform for resolving community issues and problems.</p>
                <div class="flex space-x-4">
                    @if (Route::has('login'))
                        @auth
                            @if (auth()->user()->role === 'admin')
                                <a href="{{ url('/admin/dashboard') }}"
                                    class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                                    Go to Dashboard
                                </a>
                            @elseif (auth()->user()->role === 'staff')
                                <a href="{{ url('/staff/dashboard') }}"
                                    class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                                    Go to Dashboard
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}"
                                class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                                Log in
                            </a>
                        @endauth
                    @endif
                    <a href="{{ route('posts.index') }}"
                        class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                        Main Page
                    </a>
                </div>
            </div>
            <div class="flex justify-center items-center"> {{-- text --}}
                {{-- <img class="rounded-lg max-w-full h-auto" src="https://picsum.photos/300/300" alt="Responsive Image">
                --}}
                <img class="rounded-lg max-w-full h-auto" src="{{ asset('assets/maskot.png') }}" alt="Responsive Image">
            </div>
        </div>
        <div class="flex justify-center items-center border-b pb-6">
            <h1 class="font-bold text-2xl">About Pengaduan Masyarakat</h1>
        </div>
        <div class="min-h-screen grid grid-cols-1 md:grid-cols-3 gap-8 px-6 md:px-16 py-12 rounded-lg">
            <div class="text-center p-6 bg-white rounded-lg hover:shadow-lg transition-shadow duration-300">
                <h1 class="text-2xl font-semibold text-gray-800 mb-4">Why does this exist?</h1>
                <p class="text-base text-gray-600">This platform was created to provide solutions to problems faced by the community easily and conveniently.</p>
                <div class="flex justify-center items-center">
                    <img class="rounded-lg w-80 h-80 object-cover mt-6" src="{{ asset('assets/mengapa.jpg') }}" alt="">
                </div>
            </div>
            <div class="text-center p-6 bg-white rounded-lg hover:shadow-lg transition-shadow duration-300">
                <h1 class="text-2xl font-semibold text-gray-800 mb-4">What are its features?</h1>
                <p class="text-base text-gray-600">This platform has several features for reporting, monitoring, and resolving issues transparently and efficiently.</p>
                <div class="flex justify-center items-center">
                    <img class="rounded-lg w-80 h-80 object-cover mt-6" src="{{ asset('assets/fitur.jpg') }}" alt="">
                </div>
            </div>
            <div class="text-center p-6 bg-white rounded-lg hover:shadow-lg transition-shadow duration-300">
                <h1 class="text-2xl font-semibold text-gray-800 mb-4">What is its purpose?</h1>
                <p class="text-base text-gray-600">This platform serves as a means to report and resolve community issues effectively and in an organized manner.</p>
                <div class="flex justify-center items-center">
                    <img class="rounded-lg w-80 h-80 object-cover mt-6" src="{{ asset('assets/fungsi.webp') }}" alt="">
                </div>
            </div>
        </div>
    </div>
@endsection
