@extends('front.layouts.master')

@section('head')
@include('meta::manager', [
    'title' => 'Order Success - ' . ($settings_g['title'] ?? ''),
])
@endsection

@section('master')
@php
    $products = array();
    $content_ids = [];
@endphp

<div class="container pb-16">
    @include('front.layouts.breadcrumb', [
        'title' => 'Order Success',
        'url' => '#'
    ])

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
                @foreach($order->OrderProducts as $i => $product)
                    @php
                        $products[] = [
                            'id' => $product->product_id,
                            'quantity' => $product->quantity
                        ];
                        $content_ids[] = $product->product_id;
                    @endphp

                    <tr class="border-b">
                        <td class="px-3 py-2 text-sm text-gray-900 border-r font-light">
                            <img src="{{ $product->Product->img_paths['small'] ?? asset('img/default-img.png') }}" alt="{{ $product->Product->title ?? 'n/a' }}" class="w-20 h-20 object-contain">
                            <p class="text-lg">{{ $product->Product->title ?? 'n/a' }}</p>
                            <p class="mb-0"><small>{{$product->ProductData->attribute_items_string ?? ''}}</small></p>
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
                <tr>
                    <th scope="col" class="text-sm font-semibold text-gray-900 px-3 py-2 border-r">
                      Payment Method
                    </th>
                    <th scope="col" class="text-sm font-semibold text-gray-900 px-3 py-2 border-r">
                        {{$order->payment_method ?? 'Cash on Delivery'}}
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="text-center mb-8">
        <a href="{{route('homepage')}}" class="text-center rounded-md bg-black px-3 py-0.5 text-sm font-medium text-white mb-3">আরো শপিং করুন</a>
    </div>
</div>
@endsection

@section('footer')
    @if(env('APP_FB_TRACK') && $track)
    <script>
        fbq('track', 'Purchase', {
            value: {{ $order->grand_total }},
            currency: 'BDT',
            contents: @json($products),
            content_ids: '{{ $order->id }}"',
        });

        $(window).on('load', function() {
            $.ajax({
                type: "POST",
                url: "{{ route('fbTrackLanding') }}",
                data: {
                    _token,
                    track_type: 'Purchase',
                    currency: 'BDT',
                    content_type: 'product',
                    content_ids: @json($content_ids),
                    contents: @json($products),
                    event_id: '{{$order->id}}',
                    value: '{{$order->grand_total}}',
                    phone: '{{hash("sha256", $order->shipping_mobile_number)}}',
                    name: '{{hash("sha256", $order->shipping_full_name)}}',
                    external_id: '{{hash("sha256", $order->id)}}'
                },
                success: function (response) {},
                error: function(){}
            });
        });
    </script>
    @endif
@endsection
