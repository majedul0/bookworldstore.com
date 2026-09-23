@extends('back.layouts.master')
@section('title', 'Order Report')

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.css"/>

<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('master')
    <div class="card border-light shadow mb-4">
        <div class="card-header">
            <h5 class="d-inline-block">Summary</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="card text-white bg-info custom_dashboard_card overflow-hidden">
                        <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <h1 class="card-title">{{$statuses['Total']}}</h1>
                                <p class="card-text">Total Order</p>
                            </div>

                            <div class="col-3 text-right">
                                <i class="fas fa-list cdc_icon"></i>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card text-white bg-success custom_dashboard_card overflow-hidden">
                        <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <h1 class="card-title">{{$statuses['Completed']}}</h1>
                                <p class="card-text">Successful Orders</p>
                            </div>

                            <div class="col-3 text-right">
                                <i class="fas fa-check cdc_icon"></i>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card text-white bg-warning custom_dashboard_card overflow-hidden">
                        <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <h1 class="card-title">{{$statuses['Processing']}}</h1>
                                <p class="card-text">Processing Orders</p>
                            </div>

                            <div class="col-3 text-right">
                                <i class="fas fa-spinner cdc_icon"></i>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card text-white bg-danger custom_dashboard_card overflow-hidden">
                        <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <h1 class="card-title">{{$statuses['Returned']}}</h1>
                                <p class="card-text">Returned Orders</p>
                            </div>

                            <div class="col-3 text-right">
                                <i class="fas fa-times cdc_icon"></i>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-light shadow mb-4">
        <form action="{{route('back.report.orders')}}" method="GET">
            <div class="card-header">
                <h5 class="d-inline-block">Filter</h5>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label><b>Select Status</b></label>
                            <select name="status" class="form-control form-control-sm">
                                <option value="" {{!request('status') ? 'selected' : ''}}>All</option>
                                <option value="Processing" {{request('status') == 'Processing' ? 'selected' : ''}}>Processing</option>
                                <option value="Delivered" {{request('status') == 'Delivered' ? 'selected' : ''}}>Delivered</option>
                                <option value="Completed" {{request('status') == 'Completed' ? 'selected' : ''}}>Completed</option>
                                <option value="Returned" {{request('status') == 'Returned' ? 'selected' : ''}}>Returned</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="d-block"><b>Select Customer</b></label>
                                    <select name="customer" class="form-control form-control-sm selectpicker">
                                        @if($customer)
                                        <option value="{{$customer->id}}" selected="selected">{{$customer->full_name}}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

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
                                    <label><b>ID Range</b></label>
                                    <input type="text" placeholder="EX: 10-100" name="id_range" value="{{request('id_range')}}" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button name="type" value="filter" class="btn btn-success"><i class="fas fa-search"></i> Submit</button>
                <a href="{{route('back.report.orders')}}" class="btn btn-info"><i class="fas fa-undo-alt"></i> Reset</a>
            </div>
        </form>
    </div>

    <div class="card border-light shadow">
        <div class="card-header">
            <h5 class="d-inline-block">Order List</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-sm" id="dataTable">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Date</th>
                    <th scope="col">Shipping Name</th>
                    <th scope="col">Shipping Address</th>
                    <th scope="col">Shipping Mobile Number</th>
                    <th scope="col">Order Total Amount</th>
                    <th scope="col">Status</th>
                    <th scope="col">Payment Status</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>

                <tfoot>
                    <tr>
                        <th class="text-right" colspan="5">Total</th>
                        <th colspan="3">{{amount($total_amount)}}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection

@section('footer')
<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.js"></script>

<script>
    // Select2
    $('.selectpicker').select2({
        placeholder: "Search Customer",
        minimumInputLength: 1,
        ajax: {
            url: '{{ route("back.customers.selectList") }}?type=customer',
            dataType: 'json',
            data: function (params) {
                return {
                    q: $.trim(params.term)
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

    // Datatable
    let status = "{{request('status') ?? 'All'}}";
    let customer = "{{request('customer')}}";
    let from_date = "{{request('from_date')}}";
    let to_date = "{{request('to_date')}}";
    let id_range = "{{request('id_range')}}";

    $('#dataTable').DataTable({
        "processing": true,
        "serverSide": true,
        "searching": false,
        "ajax": {
            "url": "{{route('back.orders.table')}}",
            "dataType": "json",
            "type": "POST",
            "data": {_token: "{{csrf_token()}}", status, customer, from_date, to_date, id_range}
        },
        "columns": [
            {"data": "id"},
            {"data": "date"},
            {"data": "order_name"},
            {"data": "full_address"},
            {"data": "mobile_number"},
            {"data": "total_amount"},
            {"data": "status"},
            {"data": "payment_status"}
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
