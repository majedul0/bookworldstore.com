@extends('back.layouts.master')
@section('title', 'Report Overview')

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.css"/>
@endsection

@section('master')
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-primary mb-3 custom_dashboard_card overflow-hidden">
                <div class="card-body">
                  <div class="row">
                      <div class="col-9">
                        <h1 class="card-title">{{$total_orders}}</h1>
                        <p class="card-text">Total Orders</p>
                      </div>

                      <div class="col-3 text-right">
                          <i class="fas fa-list cdc_icon"></i>
                      </div>
                  </div>
                </div>
                <div class="card-footer text-center"><a href="{{route('back.orders.index')}}?ref=All" class="d-block text-light">More Info <i class="fas fa-arrow-right"></i></a></div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card text-white bg-success mb-3 custom_dashboard_card overflow-hidden">
                <div class="card-body">
                  <div class="row">
                      <div class="col-9">
                        <h1 class="card-title">{{$products}}</h1>
                        <p class="card-text">Total Products</p>
                      </div>

                      <div class="col-3 text-right">
                          <i class="fas fa-people-carry cdc_icon"></i>
                      </div>
                  </div>
                </div>
                <div class="card-footer text-center"><a href="{{route('back.products.index')}}" class="d-block text-light">More Info <i class="fas fa-arrow-right"></i></a></div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-warning mb-3 custom_dashboard_card overflow-hidden">
                <div class="card-body">
                  <div class="row">
                      <div class="col-9">
                        <h1 class="card-title">{{$total_customers}}</h1>
                        <p class="card-text">Total Customer</p>
                      </div>

                      <div class="col-3 text-right">
                          <i class="fas fa-user cdc_icon"></i>
                      </div>
                  </div>
                </div>
                <div class="card-footer text-center"><a href="{{route('back.customers.index')}}" class="d-block text-light">More Info <i class="fas fa-arrow-right"></i></a></div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-info mb-3 custom_dashboard_card overflow-hidden">
                <div class="card-body">
                  <div class="row">
                      <div class="col-9">
                        <h1 class="card-title">{{$reviews}}</h1>
                        <p class="card-text">Total Reviews</p>
                      </div>

                      <div class="col-3 text-right">
                          <i class="fas fa-star cdc_icon"></i>
                      </div>
                  </div>
                </div>
                <div class="card-footer text-center"><a href="{{route('back.products.reviews')}}" class="d-block text-light">More Info <i class="fas fa-arrow-right"></i></a></div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <!-- Card Header -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6>Sales Overview</h6>

            <div class="float-right">
                <div class="dropdown">
                    <button class="btn btn-sm dropdown-toggle" type="button" id="dropdownMenuButtonChart" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Yearly
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
            <div class="chart-area">
                <canvas id="myAreaChart"></canvas>
            </div>
        </div>
    </div>
@endsection

@section('footer')
<script>
    let area_chart_labels = @json($months);
    let area_chart_data = @json($amounts);
    let area_chart_data_monthly = @json($monthly_amounts);
    let area_chart_labels_monthly = @json($days);
    let currency_sign = "{{$settings_g['currency_symbol'] ?? '$'}}";
</script>

<script src="{{asset('back/chart.js/Chart.js')}}"></script>

<script src="{{asset('back/js/chart-area-demo.js')}}?c=1"></script>
@endsection
