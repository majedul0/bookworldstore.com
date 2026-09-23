@extends('front.layouts.master')

@section('head')
    @include('meta::manager', [
        'title' => 'Register - ' . ($settings_g['title'] ?? ''),
    ])
@endsection

@section('master')
<div class="container">
    <div class="flex justify-center">
        <div class="w-full max-w-md py-16">
            <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 border border-gray-100" method="POST" action="{{route('register')}}">
                @csrf

                <div class="text-center mb-4">
                    <h4 class="text-xl">Register new Account</h4>
                </div>

                <div class="mb-4">
                    <label class="block text-font-color-dark text-sm font-bold mb-2">Full Name*</label>

                    <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-font-color-dark leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" value="{{old('name')}}" name="name" required>

                    @error('name')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-font-color-dark text-sm font-bold mb-2">Email Address*</label>

                    <input type="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-font-color-dark leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror" value="{{old('email')}}" name="email" required>

                    @error('email')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-font-color-dark text-sm font-bold mb-2">Mobile Number*</label>

                    <input type="number" class="shadow appearance-none border rounded w-full py-2 px-3 text-font-color-dark leading-tight focus:outline-none focus:shadow-outline @error('mobile_number') border-red-500 @enderror" value="{{old('mobile_number')}}" name="mobile_number" required>

                    @error('mobile_number')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-font-color-dark text-sm font-bold mb-2">Full Address*</label>

                    <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-font-color-dark leading-tight focus:outline-none focus:shadow-outline @error('address') border-red-500 @enderror" value="{{old('address')}}" name="address" required>

                    @error('address')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-font-color-dark text-sm font-bold mb-2">Password*</label>

                    <input type="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-font-color-dark leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror" name="password" required>

                    @error('password')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-font-color-dark text-sm font-bold mb-2">Confirm Password*</label>

                    <input type="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-font-color-dark leading-tight focus:outline-none focus:shadow-outline" name="password_confirmation" required>
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-primary hover:bg-primary-light text-font-color-light font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Register</button>
                </div>
                <p>Already have account? <a href="{{route('login')}}" class="inline-block align-baseline font-bold text-sm text-primary hover:text-primary-light">Login Now</a></p>
            </form>
        </div>
    </div>
</div>
@endsection

