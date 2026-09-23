@extends('back.layouts.master')
@section('title', "Failed Orders")

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.css"/>
@endsection

@section('master')
<div class="card mb-4 shadow">
    <form action="{{route('back.orders.index')}}" method="GET">
        <div class="card-body pb-3">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label><b>From Date</b></label>
                        <input type="date" name="from" value="{{request('from')}}" class="form-control from_date">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label><b>To Date</b></label>
                        <input type="date" name="to" value="{{request('to')}}" class="form-control to_date">
                    </div>
                </div>
            </div>

            <button class="btn btn-info">Submit</button>
            <a href="{{route('back.orders.failed')}}" class="btn btn-secondary">Clear Filter</a>
        </div>
    </form>
</div>

<div class="card mb-4 shadow">
    <div class="card-header">
        <h5>Order List</h5>
    </div>

    <div class="card-body table-responsive">
        <table class="table table-bordered table-sm" id="dataTable">
            <thead>
              <tr>
                <th scope="col">Select</th>
                <th scope="col">ID</th>
                <th scope="col">Create Date</th>
                <th scope="col">Last Update At</th>
                <th scope="col">Last Update</th>
                <th scope="col">Name</th>
                <th scope="col">Address</th>
                <th scope="col">Mobile Number</th>
                <th scope="col">Order Items</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($failed_orders as $failed_order)
                    <tr>
                        <td>
                            <input class="mt-1" name="orders[]" type="checkbox" value="{{$failed_order->id}}" style="width: 20px;height:20px">
                        </td>
                        <td>{{$failed_order->id}}</td>
                        <td>{{date('d/m/Y h:i:a', strtotime($failed_order->created_at))}}</td>
                        <td>{{date('d/m/Y h:i:a', strtotime($failed_order->updated_at))}}</td>
                        <td>{{$failed_order->last_event}}</td>
                        <td>{{$failed_order->shipping_name}}</td>
                        <td>{{$failed_order->shipping_address}}</td>
                        <td>{{$failed_order->shipping_mobile_number}}</td>
                        <td>
                            <ul class="pl-3">
                                @foreach ($failed_order->failed_order_items as $failed_order_item)
                                    <li>{{$failed_order_item->product->title ?? 'n/a'}}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <a href="{{route('back.orders.create')}}?failed_order={{$failed_order->id}}&name={{$failed_order->shipping_name}}&mobile_number={{$failed_order->shipping_mobile_number}}&address={{$failed_order->shipping_address}}" class="btn btn-sm btn-info">Make Confirm</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('footer')
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.js"></script>

<script>
    $('#dataTable').DataTable({
        order: [[1, "desc"]],
    });
</script>
@endsection
