@extends('front.layouts.master')

@section('head')
    @include('meta::manager', [
        'title' => 'Email verify - ' . ($settings_g['title'] ?? ''),
    ])
@endsection

@section('master')
<div class="page_wrap">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5 col-xl-5">
                <div class="auth_form">
                    <div class="card shadow">
                        <div class="card-header text-center"><h4>Email verify</h4></div>

                        <div class="card-body">
                            <p>Thanks for your registration. Please check your mail({{$user->email}}) to verify your account.</p>

                            <a href="{{route('resendVerifyLink', $user->id)}}" style="color: #fff" class="button">Resend verification link</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
