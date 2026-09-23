@extends('front.layouts.master')

@section('head')
    @include('meta::manager', [
        'title' => 'Login - ' . ($settings_g['title'] ?? ''),
    ])
@endsection

@section('master')
<div class="container">
    <div class="flex justify-center">
        <div class="w-full max-w-md py-16">
            <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 border border-gray-100" method="POST" action="{{route('login')}}">
                @csrf

                <div class="text-center mb-4">
                    <h4 class="text-xl">Login to You Account</h4>
                </div>

                <div class="mb-4">
                    <label class="block text-font-color-dark text-sm font-bold mb-2">Email or Mobile</label>

                    <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-font-color-dark leading-tight focus:outline-none focus:shadow-outline" value="{{old('email')}}" name="email" required>
                </div>

                <div class="mb-4">
                    <label class="block text-font-color-dark text-sm font-bold mb-2">Password</label>

                    <input type="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-font-color-dark leading-tight focus:outline-none focus:shadow-outline" name="password" required>
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-primary hover:bg-primary-light text-font-color-light font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline text-white">Sign In</button>

                    {{-- <a href="#" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">Forgot Password?</a> --}}
                </div>
                <p>Don't have an account? <a href="{{route('register')}}" class="inline-block align-baseline font-bold text-sm text-primary hover:text-primary-light">Register</a></p>
            </form>
        </div>
    </div>
</div>
@endsection
