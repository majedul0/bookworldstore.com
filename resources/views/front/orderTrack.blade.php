@extends('front.layouts.master')

@section('head')
@include('meta::manager', [
    'title' => 'Track Order - ' . ($settings_g['title'] ?? ''),
])
@endsection

@section('master')
@php
    $products = array();
@endphp
@include('front.layouts.breadcrumb', [
    'title' => 'Track Order',
    'url' => '#'
])

<div class="container mt-6 pb-16">
    <div class="text-center">
        <form action="{{route('order.track')}}" class="w-96 max-w-full inline-block my-8">
            <h2 class="text-4xl">Track Order</h2>

            <div class="mb-4">
                <label class="block text-font-color-dark text-sm font-bold mb-2">মোবাইল নম্বর*</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-font-color-dark leading-tight focus:shadow-outline" type="number" name="mobile_number" value="{{old('mobile_number', request('mobile_number'))}}" placeholder="মোবাইল নম্বর">
            </div>

            <div class="mt-6">
                <button type="submit" class="text-center rounded-md border-2 border-primary bg-primary px-6 py-2 text-base font-medium text-font-color-light shadow-sm hover:bg-white hover:text-primary block w-full text-white">Track</button>
            </div>
        </form>
    </div>

    @if(request('mobile_number'))
        <div>
            @if($order)
                <ul class=" mb-4">
                    <li><b>Name:</b> {{$order->shipping_full_name}}</li>
                    <li><b>Address:</b> {{$order->shipping_street}}</li>
                </ul>
                <div class="overflow-auto">
                    <table class="w-full border text-left mb-8">
                        <thead class="border-b">
                        <tr>
                            <th scope="col" class="text-sm font-semibold text-gray-900 px-3 py-2 border-r">
                            Product
                            </th>
                            <th scope="col" class="text-sm font-semibold text-gray-900 px-3 py-2 border-r">
                            Price
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($order->OrderProducts as $i => $product)
                                @php

                                    $products[] = [
                                        'id' => $product->product_id,
                                        'quantity' => $product->quantity
                                    ];
                                @endphp

                                <tr class="border-b">
                                    <td class="px-3 py-2 text-sm text-gray-900 border-r font-light">
                                        <img src="{{ $product->Product->img_paths['small'] ?? asset('img/default-img.png') }}" alt="{{ $product->Product->title ?? 'n/a' }}" class="w-20 h-20 object-contain">
                                        <p class="text-lg">{{ $product->Product->title ?? 'n/a' }}</p>
                                        <p class="mb-0"><small>{{$product->ProductData->attribute_items_string}}</small></p>
                                        <p class="text-lg">৳ {{ $product->sale_price }} x {{$product->quantity}}</p>
                                    </td>
                                    <td class="text-gray-900 font-light px-3 py-2 whitespace-nowrap border-r text-lg">
                                        ৳ {{$product->sale_price * $product->quantity}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr class="border-b">
                                <th scope="col" class="text-sm font-semibold text-gray-900 px-3 py-2 border-r">
                                Delivery Cost
                                </th>
                                <th scope="col" class="text-sm font-semibold text-gray-900 px-3 py-2 border-r">
                                    ৳ {{$order->shipping_charge}}
                                </th>
                            </tr>
                            <tr class="border-b">
                                <th scope="col" class="text-sm font-semibold text-gray-900 px-3 py-2 border-r">
                                Total
                                </th>
                                <th scope="col" class="text-sm font-semibold text-gray-900 px-3 py-2 border-r">
                                    ৳ {{$order->grand_total}}
                                </th>
                            </tr>
                            <tr class="border-b">
                                <th scope="col" class="text-sm font-semibold text-gray-900 px-3 py-2 border-r">
                                Order Number
                                </th>
                                <th scope="col" class="text-sm font-semibold text-gray-900 px-3 py-2 border-r">
                                    {{$order->id}}
                                </th>
                            </tr>
                            <tr class="border-b">
                                <th scope="col" class="text-sm font-semibold text-gray-900 px-3 py-2 border-r">
                                    Status
                                </th>
                                <th scope="col" class="text-sm font-semibold text-gray-900 px-3 py-2 border-r">
                                    {{$order->status}}
                                </th>
                            </tr>
                            <tr>
                                <th scope="col" class="text-sm font-semibold text-gray-900 px-3 py-2 border-r">
                                Payment Method
                                </th>
                                <th scope="col" class="text-sm font-semibold text-gray-900 px-3 py-2 border-r">
                                    Cash on Delivery
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="text-center py-2 bg-gray-200 rounded">
                    <p class="text-red-600 text-xl">No order found!</p>
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
