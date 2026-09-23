@extends('back.layouts.master')
@section('title', 'Dashboard')

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.css"/>
@endsection

@section('master')
    {{-- <div class="row">
        <div class="col-md-6 col-lg-6 col-xl-3 mb-3">
            <div class="card text-white bg-primary mb-3 custom_dashboard_card overflow-hidden">
                <a href="{{route('back.orders.index')}}?ref=All" class="card-body dashboard_card_body">
                  <div class="row">
                    <div class="col-3">
                        <i class="fas fa-list cdc_icon"></i>
                    </div>

                      <div class="col-9 text-right">
                        <h1 class="card-title mb-0">{{$total_orders}}</h1>
                        <p class="card-text">Completed Orders</p>
                      </div>
                  </div>
                </a>
            </div>
        </div>
        <div class="col-md-6 col-lg-6 col-xl-3 mb-3">
            <div class="card text-white bg-success mb-3 custom_dashboard_card overflow-hidden">
                <a href="{{route('back.orders.index')}}?ref=All" class="card-body dashboard_card_body">
                  <div class="row">
                        <div class="col-3">
                            <i class="fas fa-people-carry cdc_icon"></i>
                        </div>

                        <div class="col-9 text-right">
                            <h1 class="card-title mb-0">{{$pending_orders}}</h1>
                            <p class="card-text">Processing Orders</p>
                        </div>
                  </div>
                </a>
            </div>
        </div>
        <div class="col-md-6 col-lg-6 col-xl-3 mb-3">
            <div class="card text-white bg-warning mb-3 custom_dashboard_card overflow-hidden">
                <a href="{{route('back.customers.index')}}" class="card-body dashboard_card_body">
                  <div class="row">
                    <div class="col-3">
                        <i class="fas fa-user cdc_icon"></i>
                    </div>

                      <div class="col-9 text-right">
                        <h1 class="card-title mb-0">{{$total_customers}}</h1>
                        <p class="card-text">Total Customer</p>
                      </div>
                  </div>
                </a>
            </div>
        </div>
        <div class="col-md-6 col-lg-6 col-xl-3 mb-3">
            <div class="card text-white bg-info mb-3 custom_dashboard_card overflow-hidden">
                <a href="{{route('back.orders.index')}}?ref=All" class="card-body dashboard_card_body">
                    <div class="row">
                        <div class="col-3">
                            <i class="fas fa-shopping-cart cdc_icon"></i>
                        </div>

                        <div class="col-9 text-right">
                            <h1 class="card-title mb-0">{{$todays_orders}}</h1>
                            <p class="card-text">Today's Orders</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div> --}}

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6>Overview</h6>

                    <div class="float-right">
                        <div class="dropdown">
                            <button class="btn btn-sm dropdown-toggle" type="button" id="dropdownMenuButtonChart" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Monthly
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonChart">
                                <a class="dropdown-item chart_type" href="#" data-type="yearly">Yearly</a>
                                <a class="dropdown-item chart_type" href="#" data-type="monthly">Monthly</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                    <canvas id="myAreaChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between d-inline-block">
                    <h6>Top 15 Products</h6>

                    <div class="float-right">
                        <div class="dropdown">
                            <button class="btn btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Daily
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                              <a class="dropdown-item top_product_type" data-type="Daily" href="#">Daily</a>
                              <a class="dropdown-item top_product_type" data-type="Weekly" href="#">Weekly</a>
                              <a class="dropdown-item top_product_type" data-type="Monthly" href="#">Monthly</a>
                            </div>
                          </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                    <table class="table table-sm">
                        <tbody class="top_products">
                            @foreach ($top_products as $top_product)
                                <tr>
                                    <td><img src="{{$top_product->Product->img_paths['small']}}" class="img_sm"></td>
                                    <td>
                                        <a target="_blank" href="{{$top_product->Product->route ?? '#'}}">{{$top_product->Product->title ?? 'N/A'}}</a>
                                        <br>
                                        <small>{{amount($top_product->Product->prices['sale_price'] ?? 0)}}</small>
                                    </td>
                                    <td class="text-right">
                                        {{amount($top_product->amount)}}
                                        <br>
                                        <small>{{$top_product->total_sales}} Sold</small>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="card shadow mb-4">
        <!-- Card Header -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6>Recent Orders</h6>
        </div>

        <!-- Card Body -->
        <div class="card-body table-responsive">
            <table class="table table-sm" id="dataTable">
                <thead>
                    <tr>
                        <th>Order No.</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Order Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($recent_orders as $recent_order)
                        <tr>
                            <td><b>#{{$recent_order->id}}</b></td>
                            <td>{{$recent_order->full_name ?? ''}}</td>
                            <td>{{date('d/m/Y', strtotime($recent_order->created_at))}}</td>
                            <td>{{amount($recent_order->grand_total, 2)}}</td>
                            <td>
                                @if($recent_order->status == 'Delivered')
                                <span class="text-primary">{{$recent_order->status}}</span>
                                @elseif($recent_order->status == 'Processing')
                                <span class="text-primary">{{$recent_order->status}}</span>
                                @elseif($recent_order->status == 'Completed')
                                <span class="text-success">{{$recent_order->status}}</span>
                                @elseif($recent_order->status == 'Returned')
                                <span class="text-danger">{{$recent_order->status}}</span>
                                @else
                                <span class="text-info">{{$recent_order->status}}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{route('back.orders.show', $recent_order->id)}}" class="btn btn-sm btn-success">Details</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div> --}}
@endsection

@section('footer')
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.js"></script>

<script>
    let area_chart_labels = @json($months);
    let area_chart_data = @json($amounts);
    let area_chart_data_monthly = @json($monthly_amounts);
    let area_chart_labels_monthly = @json($days);
    let currency_sign = "{{$settings_g['currency_symbol'] ?? '$'}}";

    let chart_yearly_purchase_amounts = @json($yearly_purchase_amounts);
    let chart_monthly_purchase_amounts = @json($monthly_purchase_amounts);
</script>

<script src="{{asset('back/chart.js/Chart.js')}}"></script>

<script src="{{asset('back/js/chart-bar.js')}}?c=1"></script>
{{-- <script src="{{asset('back/js/chart-area-demo.js')}}"></script> --}}
{{-- <script src="{{asset('back/js/datatables-demo.js')}}"></script> --}}

<script>
    $(document).ready( function () {
        $('#dataTable').DataTable({
            order: [[0, "desc"]],
        });
    });

    // Get Top Product
    $(document).on('click', '.top_product_type', function(){
        let this_data_type = $(this).data('type');

        $(this).closest('.dropdown').find('button').html(this_data_type);

        cLoader();
        $.ajax({
            url: '{{route("topProducts")}}',
            method: 'POST',
            data: {type: this_data_type, _token: '{{csrf_token()}}'},
            success: function(result){
                cLoader('h');
                $('.top_products').html(result);
            },
            error: function(){
                cLoader('h');
                console.log('Error from top products ajax!');
            }
        });
    });
</script>
@endsection
