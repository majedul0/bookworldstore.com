@extends('front.layouts.master')

@section('head')
    @include('meta::manager', [
        'title' => 'Dashboard - ' . ($settings_g['title'] ?? ''),
    ])
@endsection

@section('master')
@include('front.layouts.breadcrumb', [
    'title' => 'Dashboard',
    'url' => '#'
])

<div class="mt-10 container pb-16">
  <div class="grid grid-cols-2 gap-2">
    <div class="mb-4">
        <h2 class="text-2xl font-bold text-font-color-dark">Order History</h2>
        <p><a href="#" class="py-0 px-3 bg-primary text-font-color-light rounded border border-primary hover:bg-white hover:text-primary transition inline-block mt-2" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></p>
        <form id="logout-form" class="hidden" action="{{ route('logout') }}" method="POST">@csrf</form>
    </div>

    <div class="text-right">
        <a href="{{route('auth.editProfile')}}" class="py-0 px-3 bg-primary text-font-color-light rounded border border-primary hover:bg-white hover:text-primary transition inline-block mt-2">
        Edit Profile
        </a>
    </div>
  </div>

  <div class="overflow-x-auto relative">
      <table class="w-full text-sm text-left text-font-color-dark shadow">
          <thead class="text-xs text-font-color-dark uppercase bg-gray-300">
            <tr>
              <th scope="col" class="py-3 px-6">
                Date
              </th>
              <th scope="col" class="py-3 px-6">
                Order
              </th>
              <th scope="col" class="py-3 px-6">
                Total Amount
              </th>
              <th scope="col" class="py-3 px-6">
                Status
              </th>
              <th scope="col" class="py-3 px-6">
                Action
              </th>
            </tr>
          </thead>

          <tbody>
            @foreach ($orders as $order)
                <tr class="bg-white border-b">
                    <th scope="row" class="py-4 px-6 font-medium text-font-color-dark whitespace-nowrap">
                        {{date('d/m/Y', strtotime($order->created_at))}}
                    </th>

                    <td class="py-4 px-6">
                        <p><b>#{{$order->id}}</b></p>
                        <p>{{$order->shipping_full_name}}</p>
                        <p>{{$order->shipping_street}}</p>
                    </td>
                    <td class="py-4 px-6">
                        ৳{{$order->grand_total}}
                    </td>
                    <td class="py-4 px-6">
                        {{$order->status}}
                    </td>
                    <td class="py-4 px-6">
                        <a href="{{route('auth.orderDetails', $order->id)}}" class="text-primary hover:text-primary-light font-bold">Details</a>
                    </td>
                </tr>
            @endforeach
          </tbody>
      </table>
  </div>
</div>

{{-- @include('front.layouts.footer') --}}
@endsection
