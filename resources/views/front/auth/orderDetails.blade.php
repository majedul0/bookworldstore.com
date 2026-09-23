@extends('front.layouts.master')

@section('head')
    @include('meta::manager', [
        'title' => "Invoice: #$order->id-$order->status-" . ($settings_g['title'] ?? ''),
    ])
@endsection

@section('master')
@include('front.layouts.breadcrumb', [
    'title' => 'Order Details',
    'url' => '#'
])

<div class="container mt-6 pb-16">
    <a href="{{route('auth.dashboard')}}" class="py-0 px-3 bg-primary text-font-color-light rounded border border-primary hover:bg-white hover:text-primary transition inline-block mt-2 mb-4">
    Back
    </a>

    <div class="shadow-lg bg-gray-100 p-4 rounded">
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-5 mb-10">
                <div class="col-span-8">
                    <p class="mb-0 mt-3"><b>Shipping Address</b></p>
                    <h6 class="mb-0">Name: {{ $order->shipping_full_name }}</h6>
                    <h6>Mobile Number: {{ $order->shipping_mobile_number }}</h6>
                    <h6>
                        {{$order->shipping_street}}
                    </h6>
                </div>

                <div class="col-span-4">
                    <h6>Phone: {{ $order->shipping_mobile_number }}</h6>
                    <h6>Date: {{ date('d-m-Y', strtotime($order->created_at)) }}</h6>
                </div>
            </div>

            <table class="w-full text-sm text-left text-font-color-dark">
                <thead class="">
                    <tr id="table_head">
                        <th class="row-1 row-1 tbl-line-height">Sl</th>
                        <th class="row-5 row-4 tbl-line-height">Description</th>
                        <th class="row-2 row-3 tbl-line-height" style="width: 220px;">Price</th>
                        <th class="row-1 row-2 tbl-line-height" style="width: 20px;">Quantity</th>
                        <th class="row-2 row-3 tbl-line-height text-right" style="width: 200px;">Total</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($order->OrderProducts as $i => $product)
                    <tr>
                        <td class="tbl-line-height">{{ $i + 1 }}</td>
                        <td class="tbl-line-height">{{ $product->Product->title }}  {{$product->ProductData->attribute_items_string}}</td>
                        <td class="tbl-line-height">৳{{ number_format($product->sale_price, 2)}}</td>
                        <td class="tbl-line-height">{{$product->quantity}}</td>
                        <td class="tbl-line-height text-right">৳{{number_format(($product->sale_price * $product->quantity), 2)}}</td>
                    </tr>
                    @endforeach

                    {{-- <tr>
                        <td colspan="2" class="tbl-line-height"></td>
                        <td colspan="2" class="tbl-line-height">Subtotal</td>
                        <td class="tbl-line-height text-right">{{ ($settings_g['currency_symbol'] ?? '$') . number_format($order->product_total, 2) }}</td>
                    </tr> --}}

                    @if($order->discount_amount)
                    <tr>
                        <td colspan="2" class="tbl-line-height"></td>
                        <td colspan="2" class="tbl-line-height">Discount</td>
                        <td class="tbl-line-height text-right">{{ ($settings_g['currency_symbol'] ?? '$') . number_format($order->discount_amount, 2) }}</td>
                    </tr>

                    <tr>
                        <td colspan="2" class="tbl-line-height"></td>
                        <td colspan="2" class="tbl-line-height">After Discount</td>
                        <td class="tbl-line-height text-right">{{ ($settings_g['currency_symbol'] ?? '$') . number_format(($order->product_total - $order->discount_amount), 2) }}</td>
                    </tr>
                    @endif

                    {{-- <tr>
                        <td colspan="2" class="tbl-line-height"></td>
                        <td colspan="2" class="tbl-line-height">HST</td>
                        <td class="tbl-line-height text-right">{{ ($settings_g['currency_symbol'] ?? '$') . number_format($order->tax_amount, 2) }}</td>
                    </tr> --}}

                    <tr class="mb-5">
                        <td colspan="2" class="tbl-line-height pt-4"></td>
                        <td colspan="2" class="tbl-line-height pt-4">Grand total</td>
                        <td class="tbl-line-height text-right pt-4">৳{{ number_format($order->grand_total, 2) }}</td>
                    </tr>

                    <tr>
                        <td colspan="2" class="tbl-line-height"></td>
                        <td colspan="2" class="tbl-line-height">Shipping Method</td>
                        <td class="tbl-line-height text-right">{{ $order->shipping_method }}</td>
                    </tr>

                    <tr class="mb-5">
                        <td colspan="2" class="tbl-line-height"></td>
                        <th colspan="2" class="tbl-line-height">
                            Order Status
                        </th>
                        <th class="tbl-line-height text-right">{{ $order->status }}</th>
                    </tr>

                    {{-- <tr class="mb-5">
                        <td colspan="2" class="tbl-line-height"></td>
                        <th colspan="2" class="tbl-line-height">
                            Payment Status
                        </th>
                        <th class="tbl-line-height text-right">{{ $order->payment_status }}</th>
                    </tr> --}}
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
