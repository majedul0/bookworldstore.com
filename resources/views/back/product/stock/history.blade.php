@extends('back.layouts.master')
@section('title', 'Product Stock History')

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.css"/>
@endsection

@section('master')
<div class="card border-light mt-3 shadow">
    <div class="card-body table-responsive">
        <table class="table table-bordered table-sm" id="dataTable">
            <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Product</th>
                    <th scope="col">Variation</th>
                    <th scope="col" class="text-center">Quantity</th>
                    <th scope="col">Type</th>
                    <th scope="col">Note</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@section('footer')
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.js"></script>

<script>
    $('#dataTable').DataTable({
        "processing": true,
        "serverSide": true,
        "searching": false,
        "ajax": {
            "url": "{{route('back.stoct.history')}}",
            "dataType": "json",
            "type": "POST",
            "data": {_token: "{{csrf_token()}}", request_type: 'stock_out'}
        },
        "columns": [
            {"data": "date"},
            {"data": "product"},
            {"data": "variation"},
            {"data": "quantity"},
            {"data": "type"},
            {"data": "note"}
        ],
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "order": [[0, "desc"]],
        "columnDefs": [
            { orderable: true, className: 'reorder', targets: [0] },
            { orderable: false, targets: '_all' }
        ]
    });
</script>
@endsection
