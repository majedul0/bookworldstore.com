@extends('back.layouts.auth')

@section('master')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card border-light mb-3 mt-5 shadow-lg">
            <div class="card-header text-center"><h3>Admin Login</h3></div>
            <div class="card-body">
                @if(isset($errors))
                    @include('extra.error-validation')
                @endif
                @if(session('success'))
                    @include('extra.success')
                @endif
                @if(session('error'))
                    @include('extra.error')
                @endif

                <form method="POST" action="{{route('login')}}">
                    @csrf
                    <div class="form-group">
                      <label>Email address</label>
                      <input type="email" class="form-control" name="email" value="{{old('email')}}">
                    </div>
                    <div class="form-group">
                      <label>Password</label>
                      <input type="password" class="form-control" name="password">
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group form-check">
                                <p>Forgot <a href="{{route('password.request')}}">your password?</a></p>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
