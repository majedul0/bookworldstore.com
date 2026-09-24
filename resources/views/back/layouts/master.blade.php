<!doctype html>
<html class="no-js" lang="en">

@php
    $pending_orders = App\Models\Order\Order::where('status', 'Pending')->count();
    $delivered_orders = App\Models\Order\Order::where('status', 'Delivered')->count();
    $in_courier_orders = App\Models\Order\Order::where('status', 'In Courier')->count();
    $completed_orders = App\Models\Order\Order::where('status', 'Completed')->count();
    $returned_orders = App\Models\Order\Order::where('status', 'Returned')->count();
    $confirmed_orders = App\Models\Order\Order::where('status', 'Confirmed')->count();
    $canceled_orders = App\Models\Order\Order::where('status', 'Canceled')->count();
    $hold_orders = App\Models\Order\Order::where('status', 'Hold')->count();
    $orders = App\Models\Order\Order::where('admin_read', 2)->count();
    $customers = App\Models\User::where('admin_read', 2)->where('type', 'customer')->active()->count();
@endphp

<head>
  <meta charset="utf-8">
  <title>@yield('title') - {{$settings_g['title'] ?? env('APP_NAME')}}</title>
  {{-- <meta name="description" content=""> --}}
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Icons -->
  <link rel="shortcut icon" href="{{$settings_g['favicon'] ?? ''}}">

  @include('back.layouts.color')

  <link rel="stylesheet" href="{{asset('back/css/normalize.css')}}">
  <link rel="stylesheet" href="{{asset('back/css/main.css')}}">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <link rel="stylesheet" href="{{asset('back/css/style.css')}}?c=3">
  <link rel="stylesheet" href="{{asset('back/css/responsive.css')}}">

  {{-- <link href="{{asset('back/css/app.css')}}" rel="stylesheet"> --}}

  <!-- fontawesome -->
  {{-- <script src="https://kit.fontawesome.com/9c65216417.js" crossorigin="anonymous"></script> --}}
  <link href="{{asset('fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">

  <meta name="theme-color" content="#fafafa">

  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.bootstrap4.min.css">

  @yield('head')

  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

  @include('switcher::code')

  <script>
    window.application_root = '{{url("/")}}';
    window.application_root_api = '{{url("/api")}}';
    window._token = '{{csrf_token()}}';
    window.upload_required = false;
    let datatable_dir = 'desc';
    let datatable_filename = 'DataTable Export';
    let datatable_paging = true;
    let datatable_searching = true;
  </script>

  <link href="{{asset('back/css/print.css')}}?c=2" media="print" rel="stylesheet">
</head>

<body>
    <form id="logout-form" action="{{route('logout')}}" method="POST" style="display: none;">
        @csrf
    </form>
    <input type="hidden" class="gallery_skip" value="0">

    <!-- Custom Loader -->
    <div class="loader noPrint" style="display: none">
        <i class="fas fa-spinner fa-spin"></i>
    </div>

  <div class="main" id="app">
    <header class="noPrint">
      <div class="container-fluid">
        <div class="header_wrap">
          <div class="row">
            <div class="col-md-4">
              <ul class="npnls left_menu d-none d-md-block">
                <li>
                    <a href="{{route('homepage')}}" target="_blank" class="app_name">
                        @if(isset($settings_g['logo']) && $settings_g['logo'])
                        <img src="{{$settings_g['logo'] ?? ''}}" alt="{{$settings_g['title'] ?? ''}}" class="whp" style="background: #fff;padding: 5px;height: 50px;object-fit: contain;">
                        @else
                        {{$settings_g['title'] ?? ''}}
                        @endif
                    </a>
                </li>
              </ul>
            </div>

            <div class="col-md-4 text-center pt-2">
                <a href="{{route('back.orders.create')}}" class="btn btn-info btn-sm"><i class="fas fa-edit"></i> Order</a>
                {{-- <a href="{{route('back.suppliers.addPayment')}}" class="btn btn-info btn-sm"><i class="fas fa-dollar-sign"></i> Supplier Payment</a> --}}
            </div>

            <div class="col-md-4">
              <div class="row">
                <div class="col-6 d-block d-md-none">
                  <ul class="npnls header_right_items hli">
                    <li><a href="#" onclick="menuTrigger()"><i class="fas fa-bars"></i></a></li>
                  </ul>
                </div>
                <div class="col-6 col-md-12">
                  <ul class="npnls text-right header_right_items d-none d-md-block">
                    <li>
                      <a href="#"><i class="fa fa-user"></i> {{auth()->user()->full_name}}</a>

                      <ul class="npnls header_right_dropdown">
                        <li><a href="{{route('admin.update-profile')}}"><i class="fas fa-user mr-2"></i>Profile</a></li>
                        <li><a href="" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-lock mr-2"></i>Logout</a></li>
                      </ul>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>

    <aside class="main-sidebar noPrint" id="sidebar_accordion">
      <ul class="npnls">
        <li class="{{(Route::is('dashboard') || Route::is('dashboard_d')) ? 'active' : ''}}"><a href="{{route('dashboard')}}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>

        @if(in_array('Product', $role_groups))
        <li class="{{(Route::is('back.products.index') || Route::is('back.products.create') || Route::is('back.products.edit')) ? 'active' : ''}}"><a href="{{route('back.products.index')}}"><i class="fas fa-edit"></i> Product</a></li>
        @endif

        {{-- <li class="{{(request()->route()->getName() == 'back.stocks.index') ? 'active' : ''}}"><a href="{{route('back.stocks.index')}}"><i class="fas fa-layer-group"></i> Product Stock</a></li> --}}

        @if(in_array('Category', $role_groups))
        <li class="{{(Route::is('back.categories.index') || Route::is('back.categories.create') || Route::is('back.categories.edit')) ? 'active' : ''}}"><a href="{{route('back.categories.index')}}"><i class="fas fa-list"></i> Categories</a></li>
        @endif

        @if(in_array('Brand', $role_groups))
        <li class="{{(Route::is('back.brands.index') || Route::is('back.brands.create') || Route::is('back.brands.edit')) ? 'active' : ''}}"><a href="{{route('back.brands.index')}}"><i class="fas fa-tag"></i> Brand</a></li>
        @endif

        <li class="{{Route::is('back.products.reviews') ? 'active' : ''}}"><a href="{{route('back.products.reviews')}}"><i class="fas fa-star"></i> Reviews</a></li>

        <li>
            <a href="{{route('back.orders.index')}}?ref=All" class="{{(Route::is('back.orders.create') || Route::is('back.orders.index') || Route::is('back.orders.show')) ? 'active' : 'collapsed'}}" type="button" data-toggle="collapse" data-target="#collapse_order" aria-expanded="false"><i class="fas fa-truck-loading"></i> Orders @if($orders)<span class="badge badge-primary" style="background: red;color: yellow;">{{$orders}}</span>@endif <i class="fas fa-chevron-right float-right text-right sub_menu_arrow"></i></a>

            <ul class="sub_ms collapse {{(Route::is('back.orders.create') || Route::is('back.orders.index') || Route::is('back.orders.show')) ? 'show' : ''}}" id="collapse_order" data-parent="#sidebar_accordion">
              <li class="{{(request()->route()->getName() == 'back.orders.create') ? 'active_sub_menu' : ''}}"><a href="{{route('back.orders.create')}}"><i class="fas fa-circle"></i> Create new</a></li>
              <li class="{{((Route::is('back.orders.index') && request('ref') == 'All') || Route::is('back.orders.show')) ? 'active_sub_menu' : ''}}"><a href="{{route('back.orders.index')}}?ref=All"><i class="fas fa-circle"></i> All Orders @if($orders > 0)<span class="badge badge-primary" style="background: red;color: yellow;">{{$orders}}</span>@endif</a></li>
              <li class="{{(Route::is('back.orders.index') && request('ref') == 'Pending') ? 'active_sub_menu' : ''}}"><a href="{{route('back.orders.index')}}?ref=Pending"><i class="fas fa-circle"></i> Pending @if($pending_orders)<span class="badge badge-primary" style="background: red;color: yellow;">{{$pending_orders}}</span>@endif</a></li>

              <li class="{{(Route::is('back.orders.index') && request('ref') == 'Confirmed') ? 'active_sub_menu' : ''}}"><a href="{{route('back.orders.index')}}?ref=Confirmed"><i class="fas fa-circle"></i> Confirmed @if($confirmed_orders)<span class="badge badge-primary" style="background: red;color: yellow;">{{$confirmed_orders}}</span>@endif</a></li>

              <li class="{{(Route::is('back.orders.index') && request('ref') == 'In Courier') ? 'active_sub_menu' : ''}}"><a href="{{route('back.orders.index')}}?ref=In Courier"><i class="fas fa-circle"></i> In Courier @if($in_courier_orders)<span class="badge badge-primary" style="background: red;color: yellow;">{{$in_courier_orders}}</span>@endif</a></li>

              <li class="{{(Route::is('back.orders.index') && request('ref') == 'Delivered') ? 'active_sub_menu' : ''}}"><a href="{{route('back.orders.index')}}?ref=Delivered"><i class="fas fa-circle"></i> Delivered @if($delivered_orders)<span class="badge badge-primary" style="background: red;color: yellow;">{{$delivered_orders}}</span>@endif</a></li>

              <li class="{{(Route::is('back.orders.index') && request('ref') == 'Completed') ? 'active_sub_menu' : ''}}"><a href="{{route('back.orders.index')}}?ref=Completed"><i class="fas fa-circle"></i> Completed @if($completed_orders)<span class="badge badge-primary" style="background: red;color: yellow;">{{$completed_orders}}</span>@endif</a></li>

              <li class="{{(Route::is('back.orders.index') && request('ref') == 'Canceled') ? 'active_sub_menu' : ''}}"><a href="{{route('back.orders.index')}}?ref=Canceled"><i class="fas fa-circle"></i> Canceled @if($canceled_orders)<span class="badge badge-primary" style="background: red;color: yellow;">{{$canceled_orders}}</span>@endif</a></li>

              <li class="{{(Route::is('back.orders.index') && request('ref') == 'Returned') ? 'active_sub_menu' : ''}}"><a href="{{route('back.orders.index')}}?ref=Returned"><i class="fas fa-circle"></i> Returned @if($returned_orders)<span class="badge badge-primary" style="background: red;color: yellow;">{{$returned_orders}}</span>@endif</a></li>

              <li class="{{(Route::is('back.orders.index') && request('ref') == 'Hold') ? 'active_sub_menu' : ''}}"><a href="{{route('back.orders.index')}}?ref=Hold"><i class="fas fa-circle"></i> Hold @if($hold_orders)<span class="badge badge-primary" style="background: red;color: yellow;">{{$hold_orders}}</span>@endif</a></li>
            </ul>
        </li>

        @php
            $customer_routes = Route::is('back.customers.index') || Route::is('back.customers.create') || Route::is('back.customers.edit') || Route::is('back.customers.show');
        @endphp
        <li>
            <a href="{{route('back.customers.index')}}" class="{{($customer_routes) ? 'active' : 'collapsed'}}" type="button" data-toggle="collapse" data-target="#collapse_customers" aria-expanded="false"><i class="fas fa-users"></i> Customers <i class="fas fa-chevron-right float-right text-right sub_menu_arrow"></i></a>

            <ul class="sub_ms collapse {{($customer_routes) ? 'show' : ''}}" id="collapse_customers" data-parent="#sidebar_accordion">
              <li class="{{Route::is('back.customers.create') ? 'active_sub_menu' : ''}}"><a href="{{route('back.customers.create')}}"><i class="fas fa-circle"></i> Create</a></li>
              <li class="{{(Route::is('back.customers.index') || Route::is('back.customers.edit') || Route::is('back.customers.show')) ? 'active_sub_menu' : ''}}"><a href="{{route('back.customers.index')}}"><i class="fas fa-circle"></i> Manage</a></li>
            </ul>
        </li>

        {{-- @if(in_array('Report', $role_groups))
        <li>
            <a href="{{route('back.report.overview')}}" class="{{(Route::is('back.report.overview') || Route::is('back.report.product') || Route::is('back.report.orders') || Route::is('back.report.revenue') || Route::is('back.report.couponDetails') || Route::is('back.report.productDetails')) ? 'active' : 'collapsed'}}" type="button" data-toggle="collapse" data-target="#collapse_report" aria-expanded="false"><i class="fas fa-chart-bar"></i> Report <i class="fas fa-chevron-right float-right text-right sub_menu_arrow"></i></a>

            <ul class="sub_ms collapse {{(Route::is('back.report.overview') || Route::is('back.report.product') || Route::is('back.report.orders') || Route::is('back.report.revenue') || Route::is('back.report.couponDetails') || Route::is('back.report.productDetails')) ? 'show' : ''}}" id="collapse_report" data-parent="#sidebar_accordion">
              <li class="{{Route::is('back.report.overview') ? 'active_sub_menu' : ''}}"><a href="{{route('back.report.overview')}}"><i class="fas fa-circle"></i> Overview</a></li>
              <li class="{{(Route::is('back.report.product') || Route::is('back.report.productDetails')) ? 'active_sub_menu' : ''}}"><a href="{{route('back.report.product')}}"><i class="fas fa-circle"></i> Product</a></li>
              <li class="{{Route::is('back.report.orders') ? 'active_sub_menu' : ''}}"><a href="{{route('back.report.orders')}}"><i class="fas fa-circle"></i> Orders</a></li>
            </ul>
        </li>
        @endif --}}

        @if(in_array('Attribute', $role_groups))
        <li class="{{(request()->route()->getName() == 'back.attributes.index') ? 'active' : ''}}"><a href="{{route('back.attributes.index')}}"><i class="fas fa-bars"></i> Attributes</a></li>
        @endif

        @if(in_array('Supplier', $role_groups))
        @php
            $supplier_routes = Route::is('back.suppliers.index') || Route::is('back.suppliers.create') || Route::is('back.suppliers.edit') || Route::is('back.suppliers.show') || Route::is('back.suppliers.payable') || Route::is('back.suppliers.payments') || Route::is('back.suppliers.addPayment') || Route::is('back.suppliers.paymentDetails');
        @endphp
        <li>
            <a href="{{route('back.suppliers.index')}}" class="{{($supplier_routes) ? 'active' : 'collapsed'}}" type="button" data-toggle="collapse" data-target="#collapse_suppliers" aria-expanded="false"><i class="fas fa-user-tie"></i> Suppliers <i class="fas fa-chevron-right float-right text-right sub_menu_arrow"></i></a>

            <ul class="sub_ms collapse {{($supplier_routes) ? 'show' : ''}}" id="collapse_suppliers" data-parent="#sidebar_accordion">
              <li class="{{Route::is('back.suppliers.create') ? 'active_sub_menu' : ''}}"><a href="{{route('back.suppliers.create')}}"><i class="fas fa-circle"></i> Create</a></li>

              <li class="{{(Route::is('back.suppliers.index') || Route::is('back.suppliers.edit')) ? 'active_sub_menu' : ''}}"><a href="{{route('back.suppliers.index')}}"><i class="fas fa-circle"></i> Manage</a></li>

              <li class="{{Route::is('back.suppliers.payable') ? 'active_sub_menu' : ''}}"><a href="{{route('back.suppliers.payable')}}"><i class="fas fa-circle"></i> Payable</a></li>

              <li class="{{Route::is('back.suppliers.payments') ? 'active_sub_menu' : ''}}"><a href="{{route('back.suppliers.payments')}}"><i class="fas fa-circle"></i> Payments</a></li>

              <li class="{{(Route::is('back.suppliers.addPayment') || Route::is('back.suppliers.paymentDetails'))? 'active_sub_menu' : ''}}"><a href="{{route('back.suppliers.addPayment')}}"><i class="fas fa-circle"></i> Add Payments</a></li>
            </ul>
        </li>
        @endif

        @if(in_array('Purchase', $role_groups))
        <li class="{{(Route::is('back.adjustments.index') || Route::is('back.adjustments.create') || Route::is('back.adjustments.show')) ? 'active' : ''}}"><a href="{{route('back.adjustments.index')}}"><i class="fas fa-dollar-sign"></i> Purchase</a></li>
        @endif

        @if(in_array('Purchase', $role_groups))
        @php
            $settings_route = Route::is('back.frontend.general') || Route::is('back.pages.index') || Route::is('back.pages.create') || Route::is('back.pages.edit') || Route::is('back.menus.index') || Route::is('back.sliders.index') || Route::is('back.sliders.edit') || Route::is('back.media.settings') || Route::is('back.courier.config') || Route::is('back.sms.config') || Route::is('back.footer-widgets.index') || Route::is('back.footer-widgets.edit') || Route::is('back.paymentMethods.*');
        @endphp
        <li>
          <a href="" class="{{$settings_route ? 'active' : 'collapsed'}}" type="button" data-toggle="collapse" data-target="#collapse_frontend" aria-expanded="false"><i class="fas fa-cog"></i> Settings <i class="fas fa-chevron-right float-right text-right sub_menu_arrow"></i></a>

          <ul class="sub_ms collapse {{$settings_route ? 'show' : ''}}" id="collapse_frontend" data-parent="#sidebar_accordion">
            @if(in_array('back.frontend.general', $role_routes))
            <li class="{{(request()->route()->getName() == 'back.frontend.general') ? 'active_sub_menu' : ''}}"><a href="{{route('back.frontend.general')}}"><i class="fas fa-circle"></i> General Settings</a></li>
            @endif

            @if(in_array('back.pages.index', $role_routes))
            <li class="{{(Route::is('back.pages.index') || Route::is('back.pages.create') || Route::is('back.pages.edit')) ? 'active_sub_menu' : ''}}"><a href="{{route('back.pages.index')}}"><i class="fas fa-circle"></i> Pages</a></li>
            @endif

            @if(in_array('back.menus.index', $role_routes))
            <li class="{{(request()->route()->getName() == 'back.menus.index') ? 'active_sub_menu' : ''}}"><a href="{{route('back.menus.index')}}"><i class="fas fa-circle"></i> Menu</a></li>
            @endif

            @if(in_array('back.sliders.index', $role_routes))
            <li class="{{(Route::is('back.sliders.index') || Route::is('back.sliders.edit')) ? 'active_sub_menu' : ''}}"><a href="{{route('back.sliders.index')}}"><i class="fas fa-circle"></i> Slider</a></li>
            @endif

            @if(in_array('back.media.settings', $role_routes))
            <li class="{{(request()->route()->getName() == 'back.media.settings') ? 'active_sub_menu' : ''}}"><a href="{{route('back.media.settings')}}"><i class="fas fa-circle"></i> Media</a></li>
            @endif

            @if(in_array('back.paymentMethods.index', $role_routes))
            <li class="{{(Route::is('back.paymentMethods.*')) ? 'active_sub_menu' : ''}}"><a href="{{route('back.paymentMethods.index')}}"><i class="fas fa-circle"></i> Payment Methods</a></li>
            @endif

            {{-- @if(in_array('back.courier.config', $role_routes))
            <li class="{{(request()->route()->getName() == 'back.courier.config') ? 'active_sub_menu' : ''}}"><a href="{{route('back.courier.config')}}"><i class="fas fa-circle"></i> Courier</a></li>
            @endif --}}

            {{-- @if(in_array('back.sms.config', $role_routes))
            <li class="{{(request()->route()->getName() == 'back.sms.config') ? 'active_sub_menu' : ''}}"><a href="{{route('back.sms.config')}}"><i class="fas fa-circle"></i> SMS</a></li>
            @endif --}}

            <li class="{{(Route::is('back.footer-widgets.index')) ? 'active_sub_menu' : ''}}"><a href="{{route('back.footer-widgets.index')}}"><i class="fas fa-circle"></i> Footer Widgets</a></li>
          </ul>
        </li>
        @endif

        @if(in_array('Report', $role_groups))
        @php
            $accounts_route = Route::is('back.accounts.otherExpenses') || Route::is('back.accounts.report') || Route::is('back.accounts.courier');
        @endphp
        <li>
          <a href="" class="{{$accounts_route ? 'active' : 'collapsed'}}" type="button" data-toggle="collapse" data-target="#collapse_accounts" aria-expanded="false"><i class="fas fa-chart-bar"></i> Accounts <i class="fas fa-chevron-right float-right text-right sub_menu_arrow"></i></a>

          <ul class="sub_ms collapse {{$accounts_route ? 'show' : ''}}" id="collapse_accounts" data-parent="#collapse_accounts">
            <li class="{{(request()->route()->getName() == 'back.accounts.report') ? 'active_sub_menu' : ''}}"><a href="{{route('back.accounts.report')}}"><i class="fas fa-circle"></i> Report Summary</a></li>
            <li class="{{(request()->route()->getName() == 'back.accounts.otherExpenses') ? 'active_sub_menu' : ''}}"><a href="{{route('back.accounts.otherExpenses')}}"><i class="fas fa-circle"></i> Other Expenses</a></li>
            {{-- <li class="{{(request()->route()->getName() == 'back.accounts.courier') ? 'active_sub_menu' : ''}}"><a href="{{route('back.accounts.courier')}}"><i class="fas fa-circle"></i> Courier</a></li> --}}
          </ul>
        </li>
        @endif

        {{-- <li class="{{Route::is('back.carts') ? 'active' : ''}}"><a href="{{route('back.carts')}}"><i class="fas fa-shopping-cart"></i> Customer Carts @if($carts)<span class="badge badge-primary" style="background: red;color: yellow;">{{$carts}}</span>@endif</a></li> --}}

        @php
            $unread_contact_messages = \App\Models\ContactMessage::where('admin_read', 2)->count();
        @endphp
        <li class="{{Route::is('back.contactMessages') ? 'active' : ''}}"><a href="{{route('back.contactMessages')}}"><i class="fas fa-envelope"></i> Contact Messages @if($unread_contact_messages)<span class="badge badge-primary" style="background: red;color: yellow;">{{$unread_contact_messages}}</span>@endif</a></li>

        {{-- @if(in_array('Location', $role_groups))
        <li class="{{(Route::is('back.locations.index')) ? 'active' : ''}}"><a href="{{route('back.locations.index')}}"><i class="fas fa-map-pin"></i> Locations</a></li>
        @endif --}}

        @if(in_array('Role Permission', $role_groups))
        <li class="{{(Route::is('back.roles.index') || Route::is('back.roles.create') || Route::is('back.roles.edit')) ? 'active' : ''}}"><a href="{{route('back.roles.index')}}"><i class="fas fa-lock"></i> Roles</a></li>
        @endif

        @if(in_array('Recycle Bin', $role_groups))
        <li class="{{(Route::is('back.recycleBun.index')) ? 'active' : ''}}"><a href="{{route('back.recycleBun.index')}}"><i class="fas fa-trash"></i> Recycle Bin</a></li>
        @endif

        @if(in_array('Testimonials', $role_groups))
        <li class="{{(Route::is('back.testimonials.index')) ? 'active' : ''}}"><a href="{{route('back.testimonials.index')}}"><i class="fas fa-star"></i> Testimonials</a></li>
        @endif

        @if(in_array('Admin', $role_groups))
        <li class="{{(Route::is('back.admins.index') || Route::is('back.admins.create') || Route::is('back.admins.edit')) ? 'active' : ''}}"><a href="{{route('back.admins.index')}}"><i class="fas fa-user"></i> Admins</a></li>
        @endif

        @if(in_array('Blogs', $role_groups))
            @php
                $blog_routes = Route::is('back.blogs.index') || Route::is('back.blogs.create') || Route::is('back.blogs.edit') || Route::is('back.blogs.categories') || Route::is('back.blogs.categories.create') || Route::is('back.blogs.categories.edit');
            @endphp
            <li>
                <a href="" class="{{($blog_routes) ? 'active' : 'collapsed'}}" type="button" data-toggle="collapse" data-target="#collapse_blog" aria-expanded="false"><i class="fas fa-edit"></i> Blog <i class="fas fa-chevron-right float-right text-right sub_menu_arrow"></i></a>

                <ul class="sub_ms collapse {{($blog_routes) ? 'show' : ''}}" id="collapse_blog" data-parent="#sidebar_accordion">
                    <li class="{{(Route::is('back.blogs.index') || Route::is('back.blogs.create') || Route::is('back.blogs.edit')) ? 'active_sub_menu' : ''}}"><a href="{{route('back.blogs.index')}}"><i class="fas fa-circle"></i> List</a></li>

                    <li class="{{(Route::is('back.blogs.categories') || Route::is('back.blogs.categories.create') || Route::is('back.blogs.categories.edit')) ? 'active_sub_menu' : ''}}"><a href="{{route('back.blogs.categories')}}"><i class="fas fa-circle"></i> Categories</a></li>
                </ul>
            </li>
        @endif

        <li><a href="{{route('cacheClearAdmin')}}"><i class="fas fa-times"></i> Cache Clear</a></li>
      </ul>
    </aside>

    <div class="content-wrapper">
      <div class="content">
        <section class="content-header noPrint">
          <div class="row">
            <div class="col-md-6">
              <h1>
                @yield('title')
                <small>{{env('APP_NAME')}}</small>
              </h1>
            </div>

            <div class="col-md-6">
              <ul class="npnls text-left text-md-right ch_breadcrumb">
                <li><a href="{{route('dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard > </a></li>
                <li class="active">@yield('title')</li>
              </ul>
            </div>
          </div>
        </section>

        <div class="content_body">
            @if(isset($errors))
                @include('extra.error-validation')
            @endif
            @if(session('success'))
                @include('extra.success')
            @endif
            @if(session('error'))
                @include('extra.error')
            @endif

            @yield('master')

            <div class="modal fade detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="detailsModalLabel">Details Item</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="detailsModalContent">

                    </div>
                  </div>
                </div>
            </div>
        </div>
      </div>

      <footer class="pt-3 px-3 noPrint pb-0">
        <p class="mb-0">&copy; {{ ($settings_g['title'] ?? env('APP_NAME')) . ' ' . date('Y')}}</p>
      </footer>
    </div>
  </div>

<!-- Media Modal -->
<div class="modal fade" id="mediaModal" tabindex="-1" aria-labelledby="mediaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="mediaModalLabel">Choice Image</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
          <div class="row gallery_items">
          </div>

          <div class="text-center">
              <button class="btn btn-success btn-sm mt-4 gallery_load_more_btn show_gallery_btn" data-type="more">Load More</button>
          </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>

  <script src="{{asset('back/js/vendor/modernizr-3.11.2.min.js')}}"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  <script src="{{asset('back/js/plugins.js')}}"></script>
  <!-- Sweetalert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9.3.0/dist/sweetalert2.all.min.js"></script>

  <script src="{{asset('back/js/main.js')}}?c=1"></script>

  {{-- <script src="{{asset('back/js/app.js')}}"></script> --}}

    @if(session('success-alert'))
        <script>
            cAlert('success', "{{session('success-alert')}}");
        </script>
    @endif

    @if(session('error-alert'))
        <script>
            cAlert('error', "{{session('error-alert')}}");
        </script>
    @endif

    @if(session('error-alert2'))
        <script>
            Swal.fire(
                'Failed!',
                '{{session("error-alert2")}}',
                'error'
            )
        </script>
    @endif

    @if(session('success-alert2'))
        <script>
            Swal.fire(
                'Success!',
                '{{session("success-alert2")}}',
                'success'
            )
        </script>
    @endif

    @if(session('error-transaction'))
        <script>
            Swal.fire(
                'Transaction Failed!',
                '{{session("error-transaction")}}',
                'error'
            )
        </script>
    @endif

    <script>
        function detailItem(type, item_id){
            cLoader();

            $.ajax({
                url: '{{route("back.show")}}',
                method: 'POST',
                data: {type, item_id, _token: '{{csrf_token()}}'},
                success: function(result){
                    cLoader('h');
                    $('.detailsModalContent').html(result);
                    $('.detailsModal').modal('show');
                },
                error: function(){
                    cLoader('h');
                    cAlert('error', 'Something wring!');
                }
            });
        }
    </script>

    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>

    @yield('footer')
</body>

</html>
