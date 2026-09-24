
@php
    $ref = request('ref') ?? 'All';
@endphp

@extends('back.layouts.master')
@section('title', "$ref Orders")

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.css"/>
@endsection

@section('master')
<div class="card border-light mt-3 shadow">
    {{-- <div class="card-header">
        <h5 class="d-inline-block">Order list</h5>

        <a href="{{route('back.orders.create')}}" class="btn btn-success btn-sm float-right"><i class="fas fa-plus"></i> Create new</a>
    </div> --}}
    <form action="{{route('back.orders.printList')}}" method="POST">
        @csrf

        <div class="card-body table-responsive">
            <table class="table table-bordered table-sm" id="dataTable">
                <thead>
                  <tr>
                    <th scope="col">Select</th>
                    <th scope="col">#</th>
                    <th scope="col">Date</th>
                    <th scope="col">Name</th>
                    <th scope="col">Address</th>
                    <th scope="col">Mobile Number</th>
                    <th scope="col">Total Amount</th>
                    <th scope="col">Status</th>
                    <th scope="col">Payment Info</th>
                    <th scope="col">Action</th>
                    <th scope="col">Staff Note</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-md-6">
                    <button class="btn btn-sm btn-success" name="type" value="print"><i class="fas fa-print"></i> Print Selected</button>
                </div>

                @if($ref == 'Pending' || $ref == 'In Courier' || $ref == 'Hold' || $ref == 'Confirmed' || $ref == 'Canceled')
                <div class="col-md-6">
                    <div class="form-group">
                        <select name="status" class="form-control form-control-sm">
                            <option value="" >Select Status</option>

                            @if($ref != 'Canceled')
                            <option value="In Courier">In Courier</option>
                            @if($ref == 'In Courier')
                            <option value="Delivered">Delivered</option>
                            <option value="Completed">Completed</option>
                            <option value="Returned">Returned</option>
                            @endif

                            <option value="Canceled">Canceled</option>
                            <option value="Hold">Hold</option>
                            <option value="Confirmed"> Confirmed</option>
                            @else
                            <option value="Delete"> Delete</option>
                            @endif
                        </select>
                    </div>

                    <button class="btn btn-sm btn-info" name="type" value="status_update">Change Selected</button>
                </div>
                @endif
            </div>
        </div>
    </form>
</div>
@endsection

@section('footer')
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.js"></script>

<script>
    $('#dataTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "{{route('back.orders.table')}}",
            "dataType": "json",
            "type": "POST",
            "data": {_token: "{{csrf_token()}}", status: '{{$ref}}'}
        },
        "columns": [
            {"data": "select"},
            {"data": "id"},
            {"data": "date"},
            {"data": "order_name"},
            {"data": "full_address"},
            {"data": "mobile_number"},
            {"data": "total_amount"},
            {"data": "status"},
            {"data": "payment_info"},
            {"data": "action"},
            {"data": "staff_note"}
        ],
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "order": [[1, "desc"]],
        "columnDefs": [
            { orderable: true, className: 'reorder', targets: [0] },
            { orderable: false, targets: '_all' }
        ]
    });
</script>
@endsection
