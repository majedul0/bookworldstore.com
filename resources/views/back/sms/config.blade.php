@extends('back.layouts.master')
@section('title', 'SMS Config')

@section('master')
<form action="{{route('back.sms.updateConfig')}}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="card border-light mt-3 shadow mb-3">
        <div class="card-header">
            <h5 class="d-inline-block mt-1 mb-0">SMS Config</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><b>Enable SMS</b></label>
                        <select name="enable_sms" class="form-control form-control-sm">
                            <option value="No" {{(($sms_config['enable_sms'] ?? 'No') == 'No') ? 'selected' : ''}}>Disabled</option>
                            <option value="Yes" {{(($sms_config['enable_sms'] ?? 'No') == 'Yes') ? 'selected' : ''}}>Enable</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><b>SMS Provider</b></label>
                        <select name="sms_provider" class="form-control form-control-sm">
                            <option value="">Select Provider</option>
                            <option value="mysoftit" {{(($sms_config['sms_provider'] ?? '') == 'mysoftit') ? 'selected' : ''}}>My Soft IT</option>
                            <option value="mshastra" {{(($sms_config['sms_provider'] ?? '') == 'mshastra') ? 'selected' : ''}}>Mobi Shastra</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-light mt-3 shadow mb-3">
        <div class="card-header">
            <h5 class="d-inline-block mt-1 mb-0">My Soft IT Credentials</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label><b>API Key</b></label>
                        <input type="password" class="form-control" name="mysoftit_api_key" value="{{$sms_config['mysoftit_api_key'] ?? ''}}" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><b>API Secrete</b></label>
                        <input type="password" class="form-control" name="mysoftit_api_secrete" value="{{$sms_config['mysoftit_api_secrete'] ?? ''}}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><b>Sender ID</b></label>
                        <input type="text" class="form-control" name="mysoftit_sender_id" value="{{$sms_config['mysoftit_sender_id'] ?? ''}}" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-light mt-3 shadow mb-3">
        <div class="card-header">
            <h5 class="d-inline-block mt-1 mb-0">Mobi Shastra Credentials</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label><b>User ID</b></label>
                        <input type="text" class="form-control" name="mshastra_user_id" value="{{$sms_config['mshastra_user_id'] ?? ''}}" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><b>Password</b></label>
                        <input type="password" class="form-control" name="mshastra_password" value="{{$sms_config['mshastra_password'] ?? ''}}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><b>Sender ID</b></label>
                        <input type="text" class="form-control" name="mshastra_sender_id" value="{{$sms_config['mshastra_sender_id'] ?? ''}}" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card border-light mt-3 shadow mb-3">
                <div class="card-header">
                    <h5 class="d-inline-block mt-1 mb-0">SMS Templates</h5>
                </div>
                <div class="card-body overflow-auto">
                    <table class="table table-bordered table-hover">
                        <thead>
                           <tr>
                              <th>Type</th>
                              <th>Massage</th>
                              <th>Status</th>
                           </tr>
                        </thead>
                        <tbody class="text-dark">
                            <tr>
                                <td>On Create Order</td>
                                <td>
                                    <input type="text" name="on_create_order" value="{{$sms_config['on_create_order'] ?? 'Hi {customer_name}, Your order #{invoice_id} has successfully placed. We will call you when the order is on the way2Delivery'}}" class="form-control form-control-sm">
                                </td>
                                <td>
                                    <label class="switch"><input type="checkbox" class="appStatus" name="on_create_order_status" value="Yes" {{ (($sms_config['on_create_order_status'] ?? '') == 'Yes') ? 'checked' : '' }}><span class="slider round"></span></label>
                                </td>
                            </tr>
                            <tr>
                                <td>On Delivered Order</td>
                                <td>
                                    <input type="text" name="on_Delivered_order" value="{{$sms_config['on_Delivered_order'] ?? 'Hi {customer_name}, Your order #{invoice_id} has been Delivered!'}}" class="form-control form-control-sm">
                                </td>
                                <td>
                                    <label class="switch"><input type="checkbox" class="appStatus" name="on_Delivered_order_status" value="Yes" {{ (($sms_config['on_Delivered_order_status'] ?? '') == 'Yes') ? 'checked' : '' }}><span class="slider round"></span></label>
                                </td>
                            </tr>
                            <tr>
                                <td>On Completed Order</td>
                                <td>
                                    <input type="text" name="on_Completed_order" value="{{$sms_config['on_Completed_order'] ?? 'Hi {customer_name}, Your order #{invoice_id} has been Completed!'}}" class="form-control form-control-sm">
                                </td>
                                <td>
                                    <label class="switch"><input type="checkbox" class="appStatus" name="on_Completed_order_status" value="Yes" {{ (($sms_config['on_Completed_order_status'] ?? '') == 'Yes') ? 'checked' : '' }}><span class="slider round"></span></label>
                                </td>
                            </tr>
                            <tr>
                                <td>On In Courier Order</td>
                                <td>
                                    <input type="text" name="on_InCourier_order" value="{{$sms_config['on_InCourier_order'] ?? 'Hi {customer_name}, Your order #{invoice_id} has been given to courier company! Courier invoice are {courier_invoice}'}}" class="form-control form-control-sm">
                                </td>
                                <td>
                                    <label class="switch"><input type="checkbox" class="appStatus" name="on_InCourier_order_status" value="Yes" {{ (($sms_config['on_InCourier_order_status'] ?? '') == 'Yes') ? 'checked' : '' }}><span class="slider round"></span></label>
                                </td>
                            </tr>
                            <tr>
                                <td>On Canceled Order</td>
                                <td>
                                    <input type="text" name="on_Canceled_order" value="{{$sms_config['on_Canceled_order'] ?? 'Hi {customer_name}, Your order #{invoice_id} has been Canceled!'}}" class="form-control form-control-sm">
                                </td>
                                <td>
                                    <label class="switch"><input type="checkbox" class="appStatus" name="on_Canceled_order_status" value="Yes" {{ (($sms_config['on_Canceled_order_status'] ?? '') == 'Yes') ? 'checked' : '' }}><span class="slider round"></span></label>
                                </td>
                            </tr>
                            <tr>
                                <td>On Returned Order</td>
                                <td>
                                    <input type="text" name="on_Returned_order" value="{{$sms_config['on_Returned_order'] ?? 'Hi {customer_name}, Your order #{invoice_id} has been Returned!'}}" class="form-control form-control-sm">
                                </td>
                                <td>
                                    <label class="switch"><input type="checkbox" class="appStatus" name="on_Returned_order_status" value="Yes" {{ (($sms_config['on_Returned_order_status'] ?? '') == 'Yes') ? 'checked' : '' }}><span class="slider round"></span></label>
                                </td>
                            </tr>
                            <tr>
                                <td>On Confirmed Order</td>
                                <td>
                                    <input type="text" name="on_Confirmed_order" value="{{$sms_config['on_Confirmed_order'] ?? 'Hi {customer_name}, Your order #{invoice_id} has been Confirmed!'}}" class="form-control form-control-sm">
                                </td>
                                <td>
                                    <label class="switch"><input type="checkbox" class="appStatus" name="on_Confirmed_order_status" value="Yes" {{ (($sms_config['on_Confirmed_order_status'] ?? '') == 'Yes') ? 'checked' : '' }}><span class="slider round"></span></label>
                                </td>
                            </tr>
                            <tr>
                                <td>On Hold Order</td>
                                <td>
                                    <input type="text" name="on_Hold_order" value="{{$sms_config['on_Hold_order'] ?? 'Hi {customer_name}, Your order #{invoice_id} has been Hold!'}}" class="form-control form-control-sm">
                                </td>
                                <td>
                                    <label class="switch"><input type="checkbox" class="appStatus" name="on_Hold_order_status" value="Yes" {{ (($sms_config['on_Hold_order_status'] ?? '') == 'Yes') ? 'checked' : '' }}><span class="slider round"></span></label>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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

    <div class="card border-light mt-3 shadow mb-3">
        <div class="card-header">
            <h5 class="d-inline-block mt-1 mb-0">SMS Templates Keys</h5>
        </div>
        <div class="card-body">
            <ul>
                <li>Use {customer_name} for Customer Namer</li>
                <li>Use {invoice_id} for Invoice ID</li>
                <li>Use {courier_invoice} for Courier Invoice</li>
                <li>Use {courier_name} for Courier Name</li>
            </ul>
        </div>
    </div>
</form>
@endsection
