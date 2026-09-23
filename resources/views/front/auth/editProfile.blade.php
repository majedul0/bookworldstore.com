@extends('front.layouts.master')

@section('head')
    @include('meta::manager', [
        'title' => 'Edit Profile - ' . ($settings_g['title'] ?? ''),
    ])
@endsection

@section('master')
@include('front.layouts.breadcrumb', [
    'title' => 'Edit Profile',
    'url' => '#'
])

<div class="mt-10 container pb-16">
  <div class="grid grid-cols-2 gap-2">
    <div class="mb-4">
        <h2 class="text-2xl font-bold text-font-color-dark">Edit Profile</h2>
        <p><a href="#" class="hover:text-primary" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></p>
        <form id="logout-form" class="hidden" action="{{ route('logout') }}" method="POST">@csrf</form>
    </div>

    <div class="text-right">
        <a href="{{route('auth.dashboard')}}" class="py-0 px-3 bg-primary text-font-color-light rounded border border-primary hover:bg-white hover:text-primary transition inline-block mt-2">
        Dashboard
        </a>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-5">
    <form class="bg-white rounded shadow p-4" method="POST" action="{{route('auth.updateProfile')}}">
      @csrf
      <h2 class="text-2xl font-bold mb-4 text-font-color-dark">Your Information</h2>

      <div v-if="user_info">
        <div class="mb-4">
          <label class="block text-font-color-dark text-sm font-bold mb-2">Full Name*</label>
          <input class="shadow appearance-none border rounded w-full py-2 px-3 text-font-color-dark leading-tight focus:shadow-outline @error('name') border-red-500 @enderror" type="text" placeholder="Full Name" name="name" value="{{old('name', auth()->user()->last_name)}}" required>

          @error('name')
              <span class="invalid-feedback block" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>

        <div class="mb-4">
          <label class="block text-font-color-dark text-sm font-bold mb-2">Email Address</label>
          <input class="shadow appearance-none border rounded w-full py-2 px-3 text-font-color-dark leading-tight focus:shadow-outline @error('email') border-red-500 @enderror" type="email" placeholder="Email Address" name="email" value="{{old('email', auth()->user()->email)}}">

          @error('email')
              <span class="invalid-feedback block" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>

        <div class="mb-4">
          <label class="block text-font-color-dark text-sm font-bold mb-2">Phone Number*</label>
          <input class="shadow appearance-none border rounded w-full py-2 px-3 text-font-color-dark leading-tight focus:shadow-outline @error('mobile_number') border-red-500 @enderror" type="number" placeholder="Phone Number" name="mobile_number" value="{{old('mobile_number', auth()->user()->mobile_number)}}" required>

          @error('mobile_number')
              <span class="invalid-feedback block" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>

        <div class="mb-4">
          <label class="block text-font-color-dark text-sm font-bold mb-2">Full Address*</label>
          <input class="shadow appearance-none border rounded w-full py-2 px-3 text-font-color-dark leading-tight focus:shadow-outline @error('address') border-red-500 @enderror" type="text" placeholder="Full Address" name="address" value="{{old('address', auth()->user()->address)}}" required>

          @error('address')
              <span class="invalid-feedback block" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>

        <button class="rounded-md border-2 border-primary bg-primary px-6 py-2 text-base font-medium text-font-color-light shadow-sm hover:bg-primary-light mt-2" type="submit">Update</button>
      </div>
    </form>

    <form action="{{route('auth.updatePassword')}}" method="POST" class="bg-white rounded shadow p-4">
        @csrf
      <h2 class="text-2xl font-bold mb-4 text-font-color-dark">Change Password</h2>

      <div class="mb-4">
        <label class="block text-font-color-dark text-sm font-bold mb-2">Old Password*</label>

        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-font-color-dark leading-tight focus:shadow-outline @error('old_password') border-red-500 @enderror" type="password" name="old_password" placeholder="Old Password">

        @error('old_password')
            <span class="invalid-feedback block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
      </div>

      <div class="mb-4">
        <label class="block text-font-color-dark text-sm font-bold mb-2">New Password*</label>

        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-font-color-dark leading-tight focus:shadow-outline @error('password') border-red-500 @enderror" type="password" name="password" required placeholder="New Password">

        @error('password')
            <span class="invalid-feedback block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
      </div>

      <div class="mb-4">
        <label class="block text-font-color-dark text-sm font-bold mb-2">Confirm Password*</label>

        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-font-color-dark leading-tight focus:shadow-outline" type="password" name="password_confirmation" required placeholder="Confirm Password">
      </div>

      <button class="rounded-md border-2 border-primary bg-primary px-6 py-2 text-base font-medium text-font-color-light shadow-sm hover:bg-primary-light mt-2">Update</button>
    </form>
  </div>
</div>
@endsection
