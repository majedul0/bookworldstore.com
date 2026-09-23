@extends('back.layouts.master')
@section('title', 'Edit Profile')

@section('master')
<form action="{{route('back.admins.update.action', $user->id)}}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row">
        <div class="col-md-8">
            <div class="card border-light mt-3 shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><b>Name*</b></label>
                                <input type="text" class="form-control form-control-sm" name="name" value="{{old('name') ?? $user->full_name}}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><b>Email*</b></label>
                                <input type="email" class="form-control form-control-sm" name="email" value="{{old('email') ?? $user->email}}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><b>Mobile Number*</b></label>
                                <input type="number" class="form-control form-control-sm" name="mobile_number" value="{{old('mobile_number') ?? $user->mobile_number}}" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><b>Street*</b></label>
                                <input type="text" class="form-control form-control-sm" name="street" value="{{old('street') ?? $user->street}}" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-light mt-3 shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="text-center">
                                <div class="img_group">
                                    @if($user->profile)
                                    <img class="img-thumbnail uploaded_img" src="{{$user->profile_path}}">
                                    @else
                                    <img class="img-thumbnail uploaded_img" src="{{asset('img/default-img.png')}}">
                                    @endif

                                    <div class="form-group text-center">
                                        <label><b>Profile Picture</b></label>
                                        <div class="custom-file text-left">
                                            <input type="file" class="custom-file-input image_upload" name="profile" >
                                            <label class="custom-file-label">Choose file...</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button class="btn btn-success btn-block">Update</button>
                    <small><b>NB: *</b> marked are required field.</small>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="row">
    <div class="col-md-8"></div>
    <div class="col-md-4">
        <div class="card border-light mt-3 shadow">
            <div>
                <h4 class="py-2 text-center mt-2">UPDATE PASSWORD</h4>
                <hr>
                <form action="{{route('admin.password-update')}}" method="POST">
                    @csrf

                    <div class="px-4">
                        <div class="form-group">
                            <label><b>Old password*</b></label>
                            <input type="password" class="form-control form-control-sm" name="old_password" required>
                        </div>

                        <div class="form-group">
                            <label><b>New Password*</b></label>
                            <input type="password" class="form-control form-control-sm" name="password" required>
                        </div>

                        <div class="form-group">
                            <label><b>Confirm Password*</b></label>
                            <input type="password" class="form-control form-control-sm" name="password_confirmation" required>
                        </div>
                    </div>

                    <div class="px-4 pb-4">
                        <button class="btn btn-success btn-block">Update</button>
                    </div>
                </form>
            </div>
        </div>
   </div>
</div>
@endsection
