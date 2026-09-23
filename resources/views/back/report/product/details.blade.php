@extends('back.layouts.master')
@section('title', 'Product Details Report')

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.css"/>
@endsection

@section('master')
<div class="card border-light mt-3 shadow">
    <div class="card-header">
        <h5 class="d-inline-block">Details Report of "{{$product->title}}"</h5>
        <a href="{{route('back.report.product')}}" class="btn btn-sm btn-info float-right"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
    <form action="{{route('back.report.productDetails', $product->id)}}">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label><b>From Date</b></label>
                        <input type="date" class="form-control form-control-sm" name="start" value="{{request('start') ?? \Carbon\Carbon::now()->subDay(7)->format('Y-m-d')}}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><b>To Date</b></label>
                        <input type="date" class="form-control form-control-sm" name="end" value="{{request('end') ?? date('Y-m-d')}}">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-sm btn-success">Filter</button>
        </div>
    </form>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="card text-white bg-info mb-3 custom_dashboard_card overflow-hidden">
                    <div class="card-body dashboard_card_body">
                        <div class="row">
                            <div class="col-3">
                                <i class="fas fa-list cdc_icon"></i>
                            </div>

                            <div class="col-9 text-right">
                            <h1 class="card-title mb-0">{{$total_quantity}}</h1>
                            <p class="card-text">Total Order</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @foreach ($reports as $report)
                @if($report->total_quantity > 0)
                @if($report->status == 'Returned')
                @php
                    $return_quantity = $return_quantity + $report->total_quantity;
                @endphp
                @else
                @php
                    if($report->status == 'Delivered' || $report->status == 'Completed'){
                        $class = 'bg-success';
                        $icon_class = 'fa-check';
                    }elseif($report->status == 'Canceled' || $report->status == 'Hold'){
                        $class = 'bg-secondary';
                        $icon_class = 'fa-times';
                    }elseif($report->status == 'In Courier'){
                        $class = 'bg-warning';
                        $icon_class = 'fa-truck';
                    }elseif($report->status == 'Confirmed'){
                        $class = 'bg-primary';
                        $icon_class = 'fa-check';
                    }else{
                        $class = 'bg-info';
                        $icon_class = 'fa-list';
                    }
                @endphp
                <div class="col-md-3">
                    <div class="card text-white {{$class}} mb-3 custom_dashboard_card overflow-hidden">
                        <div class="card-body dashboard_card_body">
                            <div class="row">
                                <div class="col-3">
                                    <i class="fas {{$icon_class}} cdc_icon"></i>
                                </div>

                                <div class="col-9 text-right">
                                <h1 class="card-title mb-0">{{$report->total_quantity}}</h1>
                                <p class="card-text">{{$report->status}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @endif
            @endforeach

            @if($return_quantity > 0)
            <div class="col-md-3">
                <div class="card text-white bg-danger mb-3 custom_dashboard_card overflow-hidden">
                    <div class="card-body dashboard_card_body">
                        <div class="row">
                            <div class="col-3">
                                <i class="fas fa-undo cdc_icon"></i>
                            </div>

                            <div class="col-9 text-right">
                            <h1 class="card-title mb-0">{{$return_quantity}}</h1>
                            <p class="card-text">Returned</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('footer')
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.js"></script>

<script>
    $('#dataTable').DataTable({
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "order": [[0, "desc"]]
    });
</script>
@endsection
