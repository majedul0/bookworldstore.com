@extends('back.layouts.master')
@section('title', "Invoce: #$order->id-$order->status")

@section('head')
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
@endsection

@section('master')
<form action="{{route('back.orders.selectCourier', $order->id)}}" method="POST" enctype="multipart/form-data">
@csrf

<div class="card border-light mt-3 shadow noPrint">
    <div class="card-header">
        <h6 class="d-inline-block">Select Courier</h6>
    </div>

    <div class="card-body">
        <div class="form-group courier_from_group">
            <label>Select Courier*</label>
            <select name="courier" class="form-control form-control-sm shipping_courier" required>
                <option value="">Select Select</option>

                @foreach ($shipping['data']['Quote'] as $item)
                    <option value="{{$item['@attributes']['carrierName']}} - {{$item['@attributes']['serviceName']}}::{{$item['@attributes']['totalCharge']}}::{{$item['@attributes']['serviceId']}}::{{$item['@attributes']['totalCharge']}}">{{$item['@attributes']['carrierName']}} - {{$item['@attributes']['serviceName']}} - {{($settings_g['currency_symbol'] ?? '$')}}{{number_format($item['@attributes']['totalCharge'], 2)}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="card-footer">
        <button class="btn btn-info">Submit</button>
    </div>
</div>
@endsection
