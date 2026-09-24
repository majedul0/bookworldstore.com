@extends('front.layouts.master')

@section('head')
@include('meta::manager', [
    'title' => 'Order Success - ' . ($settings_g['title'] ?? ''),
])
@endsection

@section('master')
@php
    $products = array();
@endphp
@include('front.layouts.breadcrumb', [
    'title' => 'Order Success',
    'url' => '#'
])

<div class="container mt-6 pb-16">
    <div class="bg-black rounded text-center mb-2 text-white py-3 text-lg md:text-2xl px-2">
        Thank You. Your order has been received.
    </div>

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
                @foreach($order->order_items as $i => $product)
                    <tr class="border-b">
                        <td class="px-3 py-2 text-sm text-gray-900 border-r font-light">
                            <p class="text-lg">{{ $product->product_title }}</p>
                            <p class="text-lg">৳ {{ $product->price }} x {{$product->quantity}}</p>
                        </td>
                        <td class="text-gray-900 font-light px-3 py-2 whitespace-nowrap border-r text-lg">
                            ৳ {{$product->price * $product->quantity}}
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
                {{-- <tr class="border-b">
                    <th scope="col" class="text-sm font-semibold text-gray-900 px-3 py-2 border-r">
                      Total
                    </th>
                    <th scope="col" class="text-sm font-semibold text-gray-900 px-3 py-2 border-r">
                        ৳ {{$order['grand_total']}}
                    </th>
                </tr> --}}
                {{-- <tr class="border-b">
                    <th scope="col" class="text-sm font-semibold text-gray-900 px-3 py-2 border-r">
                      Order Number
                    </th>
                    <th scope="col" class="text-sm font-semibold text-gray-900 px-3 py-2 border-r">
                        {{$order->id}}
                    </th>
                </tr> --}}
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

    <div class="text-center mb-8">
        <a href="{{route('homepage')}}" class="text-center rounded-md border-2 border-primary-light bg-primary-light px-3 py-2 text-sm font-medium text-white inline-block">আরো শপিং করুন</a>
    </div>
</div>
@endsection
