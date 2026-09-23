@extends('back.layouts.master')
@section('title', 'Courier Config')

@section('master')
<form action="{{route('back.courier.update')}}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- <div class="card border-light mt-3 shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label><b>Default Courier*</b></label>
                        <select name="courier" class="form-control">
                            <option value="">Select Courier</option>
                            <option value="Pathao" {{($settings_g['courier'] ?? '') == 'Pathao' ? 'selected' : ''}}>Pathao</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="card border-light mt-3 shadow mb-4">
        <div class="card-header">
            <h5 class="d-inline-block mt-1 mb-0">Pathao Credentials</h5>

            <label class="switch float-right"><input type="checkbox" class="appStatus" name="pathao_enabled" value="Yes" {{ (($courier_config['pathao_enabled'] ?? '') == 'Yes') ? 'checked' : '' }}><span class="slider round"></span></label>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><b>Client ID</b></label>
                        <input type="text" class="form-control" name="pathao_client_id" value="{{$courier_config['pathao_client_id'] ?? ''}}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><b>Client Secret</b></label>
                        <input type="password" class="form-control" name="pathao_client_secret" value="{{$courier_config['pathao_client_secret'] ?? ''}}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><b>Username</b></label>
                        <input type="text" class="form-control" name="pathao_username" value="{{$courier_config['pathao_username'] ?? ''}}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><b>Password</b></label>
                        <input type="password" class="form-control" name="pathao_password" value="{{$courier_config['pathao_password'] ?? ''}}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-light mt-3 shadow mb-4">
        <div class="card-header">
            <h5 class="d-inline-block mt-1 mb-0">REDX Credentials</h5>

            <label class="switch float-right"><input type="checkbox" class="appStatus" name="redx_enabled" value="Yes" {{ (($courier_config['redx_enabled'] ?? '') == 'Yes') ? 'checked' : '' }}><span class="slider round"></span></label>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label><b>REDX API Token</b></label>
                        <input type="password" class="form-control" name="redx_api_token" value="{{$courier_config['redx_api_token'] ?? ''}}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-light mt-3 shadow mb-4">
        <div class="card-header">
            <h5 class="d-inline-block mt-1 mb-0">Steadfast Credentials</h5>

            <label class="switch float-right"><input type="checkbox" class="appStatus" name="steadfast_enabled" value="Yes" {{ (($courier_config['steadfast_enabled'] ?? '') == 'Yes') ? 'checked' : '' }}><span class="slider round"></span></label>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label><b>API Key</b></label>
                        <input type="text" class="form-control" name="steadfast_api_key" value="{{$courier_config['steadfast_api_key'] ?? ''}}">
                    </div>
                    <div class="form-group">
                        <label><b>Secret Key</b></label>
                        <input type="password" class="form-control" name="steadfast_secret_key" value="{{$courier_config['steadfast_secret_key'] ?? ''}}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-light mt-3 shadow mb-4">
        <div class="card-header">
            <h5 class="d-inline-block mt-1 mb-0">eCourier Credentials</h5>

            <label class="switch float-right"><input type="checkbox" class="appStatus" name="ecourier_enabled" value="Yes" {{ (($courier_config['ecourier_enabled'] ?? '') == 'Yes') ? 'checked' : '' }}><span class="slider round"></span></label>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label><b>API Key</b></label>
                        <input type="text" class="form-control" name="ecourier_api_key" value="{{$courier_config['ecourier_api_key'] ?? ''}}">
                    </div>
                    <div class="form-group">
                        <label><b>Secret Key</b></label>
                        <input type="password" class="form-control" name="ecourier_secret_key" value="{{$courier_config['ecourier_secret_key'] ?? ''}}">
                    </div>
                    <div class="form-group">
                        <label><b>User ID</b></label>
                        <input type="password" class="form-control" name="ecourier_user_id" value="{{$courier_config['ecourier_user_id'] ?? ''}}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-light mt-3 shadow mb-4">
        <div class="card-header">
            <h5 class="d-inline-block mt-1 mb-0">Paperfly Credentials</h5>

            <label class="switch float-right"><input type="checkbox" class="appStatus" name="paperfly_enabled" value="Yes" {{ (($courier_config['paperfly_enabled'] ?? '') == 'Yes') ? 'checked' : '' }}><span class="slider round"></span></label>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label><b>API Key</b></label>
                        <input type="password" class="form-control" name="paperfly_api_key" value="{{$courier_config['paperfly_api_key'] ?? ''}}">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><b>Username</b></label>
                                <input type="text" class="form-control" name="paperfly_username" value="{{$courier_config['paperfly_username'] ?? ''}}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label><b>Password</b></label>
                                <input type="password" class="form-control" name="paperfly_password" value="{{$courier_config['paperfly_password'] ?? ''}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-light mt-3 shadow mb-4">
        <div class="card-header">
            <h5 class="d-inline-block mt-1 mb-0">Pidex Credentials</h5>

            <label class="switch float-right"><input type="checkbox" class="appStatus" name="pidex_enabled" value="Yes" {{ (($courier_config['pidex_enabled'] ?? '') == 'Yes') ? 'checked' : '' }}><span class="slider round"></span></label>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label><b>Merchant ID</b></label>
                <input type="text" class="form-control" name="pidex_merchant_id" value="{{$courier_config['pidex_merchant_id'] ?? ''}}">
            </div>
            <div class="form-group">
                <label><b>API Token</b></label>
                <input type="password" class="form-control" name="pidex_api_token" value="{{$courier_config['pidex_api_token'] ?? ''}}">
            </div>
        </div>
    </div>

    <div class="card border-light mt-3 shadow mb-4">
        <div class="card-body">
            <button class="btn btn-success create_btn">Update</button>
            <br>
            <small><b>NB: *</b> marked are required field.</small>
        </div>
    </div>
</form>
@endsection
