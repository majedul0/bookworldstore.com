@extends('back.layouts.master')
@section('title', 'Pre alert')

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.css"/>
@endsection

@section('master')
<div class="card border-light mt-3 shadow">
    <div class="card-body table-responsive">
        <table class="table table-bordered table-sm" id="dataTable">
            <thead>
                <tr>
                    <th scope="col">SL</th>
                    {{-- <th scope="col">SKU</th> --}}
                    <th scope="col">Name</th>
                    <th scope="col">Image</th>
                    <th scope="col">Type</th>
                    <th scope="col">Available Stock</th>
                    <th scope="col">Department</th>
                    <th scope="col" class="text-right">Action</th>
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
        "ajax": {
            "url": "{{route('back.products.table')}}",
            "dataType": "json",
            "type": "POST",
            "data": {_token: "{{csrf_token()}}", request_type: 'pre_alert'}
        },
        "columns": [
            {"data": "sl"},
            // {"data": "sku"},
            {"data": "name"},
            {"data": "image"},
            {"data": "type"},
            {"data": "stock"},
            {"data": "depertment"},
            {"data": "stock_action"}
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
