@extends('back.layouts.master')
@section('title', 'Edit Payment Method')

@section('master')
<form action="{{route('back.paymentMethods.update', $payment_method->id)}}" method="POST">
@csrf
@method('PUT')
<div class="row">
    <div class="col-md-8">
        <div class="card border-light mt-3 shadow">
            <div class="card-header no_icon">
                <a href="{{route('back.paymentMethods.index')}}" class="btn btn-primary btn-sm"><i class="fas fa-angle-double-left"></i> View All</a>
            </div>

            <div class="card-body">
                <div class="form-group">
                    <label><b>Name*</b></label>
                    <input type="text" class="form-control form-control-sm" name="name" value="{{old('name', $payment_method->name)}}" placeholder="e.g. bKash, Nagad, Rocket" required>
                </div>
                <div class="form-group">
                    <label><b>Send Money Number*</b></label>
                    <input type="text" class="form-control form-control-sm" name="number" value="{{old('number', $payment_method->number)}}" placeholder="e.g. 01XXXXXXXXX" required>
                </div>
                <div class="form-group">
                    <label><b>Instructions</b></label>
                    <textarea class="form-control form-control-sm" name="instructions" cols="30" rows="4" placeholder="Shown to the customer when they select this payment method, e.g. Send Money to this number, then enter your sender number, amount, and transaction ID below.">{{old('instructions', $payment_method->instructions)}}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-light mt-3 shadow">
            <div class="card-body">
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="status" name="status" value="1" {{$payment_method->status ? 'checked' : ''}}>
                        <label class="custom-control-label" for="status"><b>Active</b> (visible to customers at checkout)</label>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary btn-block" type="submit">Update</button>
                <small><b>NB: *</b> marked are required field.</small>
            </div>
        </div>
    </div>
</div>
</form>
@endsection
