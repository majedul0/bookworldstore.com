@extends('back.layouts.master')
@section('title', 'Product Report')

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.css"/>
@endsection

@section('master')
<div class="card border-light shadow mb-4">
    <form action="{{route('back.report.product')}}" method="GET">
        <div class="card-header">
            <h5 class="d-inline-block">Filter</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label><b>From Date</b></label>
                        <input type="date" name="from_date" value="{{request('from_date')}}" class="form-control form-control-sm">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label><b>To Date</b></label>
                        <input type="date" name="to_date" value="{{request('to_date')}}" class="form-control form-control-sm">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label style="visibility: hidden">.</label>
                        <br>
                        <button name="type" value="filter" class="btn btn-success btn-sm"><i class="fas fa-search"></i> Submit</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <a href="{{route('back.report.product')}}" class="btn btn-info"><i class="fas fa-undo-alt"></i> Reset</a>
        </div>
    </form>
</div>

<div class="card border-light mt-3 shadow">
    <div class="card-header">
        <h5 class="d-inline-block">Product list</h5>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-sm" id="dataTable">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                {{-- <th scope="col">Regular Price</th>
                <th scope="col">Sale Price</th> --}}
                <th scope="col">Available Stock</th>
                <th scope="col">Purchase</th>
                <th scope="col">Sold</th>
                <th scope="col" class="text-right">Profit/Loss</th>
                <th scope="col" class="text-right">Action</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
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
            "url": "{{route('back.report.product')}}",
            "dataType": "json",
            "type": "POST",
            "data": {_token: "{{csrf_token()}}", from_date: '{{request("from_date")}}', to_date: '{{request("to_date")}}'}
        },
        "columns": [
            {"data": "sl"},
            {"data": "name"},
            {"data": "available_stock"},
            {"data": "purchase"},
            {"data": "sold"},
            {"data": "profit_loss"},
            {"data": "action"}
        ],
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "order": [[0, "asc"]],
        "columnDefs": [
            { orderable: true, className: 'reorder', targets: [0] },
            { orderable: false, targets: '_all' }
        ]
    });
</script>
@endsection
