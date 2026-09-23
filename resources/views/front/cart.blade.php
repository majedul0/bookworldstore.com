@extends('front.layouts.master')

@section('head')
@include('meta::manager', [
    'title' => 'Cart - ' . ($settings_g['title'] ?? ''),
])
@endsection

@section('master')
<div class="bg-gray-100">
    <div class="container pb-4 md:pb-16">
        @include('front.layouts.breadcrumb', [
            'title' => 'Cart',
            'url' => '#'
        ])

        @if(count($carts['carts']))
            <form action="{{route('order')}}" method="POST" class="grid grid-cols-1 md:grid-cols-8 gap-4">
                <div class="bg-white border rounded col-span-1 md:col-span-4 lg:col-span-3">
                    <h2 class="text-xl font-medium mb-2 bg-gray-200 p-2">Your Information</h2>

                    <div class="p-4">
                        @csrf
                        @php
                            if(session('uu_id__')){
                                $uuid = session('uu_id__');
                            }else{
                                $uuid = \Str::uuid();
                                session(['uu_id__' => $uuid]);
                            }
                        @endphp
                        <input type="hidden" name="uu_id" class="uu_id" value="{{$uuid}}">

                        <div class="mb-4">
                            <label class="block text-font-color-dark text-sm font-bold mb-2">Enter your name*</label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-font-color-dark leading-tight focus:shadow-outline shipping_name information_field @error('name') border-red-500 @enderror" type="text" name="name" value="{{old('name', (auth()->user()->full_name ?? ''))}}" placeholder="Enter your name">

                            @error('name')
                                <span class="invalid-feedback block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-font-color-dark text-sm font-bold mb-2">Enter your mobile number*</label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-font-color-dark leading-tight focus:shadow-outline information_field mobile_number @error('mobile_number') border-red-500 @enderror" type="number" name="mobile_number" value="{{old('mobile_number', (auth()->user()->mobile_number ?? ''))}}" placeholder="Enter your mobile number">

                            @error('mobile_number')
                                <span class="invalid-feedback block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-font-color-dark text-sm font-bold mb-2">Enter your address*</label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-font-color-dark leading-tight focus:shadow-outline shipping_address information_field @error('address') border-red-500 @enderror" type="text" name="address" value="{{old('address', (auth()->user()->address ?? ''))}}" placeholder="Enter your address">

                            @error('address')
                                <span class="invalid-feedback block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- <div class="mb-4">
                            <label class="block text-font-color-dark text-sm font-bold mb-2">Select your area*</label>
                            <select name="ares" class="shadow appearance-none border rounded w-full py-2 px-3 text-font-color-dark leading-tight focus:shadow-outline @error('address') border-red-500 @enderror change_area" required>
                                <option value="Inside Dhaka">Inside Dhaka</option>
                                <option value="Outside Dhaka">Outside Dhaka</option>
                            </select>
                        </div> --}}

                        <div class="mt-6">
                            <button type="submit" class="text-center rounded-md border-2 border-primary bg-primary px-6 py-2 text-base font-medium text-font-color-light shadow-sm block w-full text-white">Confirm Order</button>
                        </div>
                    </div>
                </div>

                <div class="bg-white border rounded col-span-1 md:col-span-4 lg:col-span-5">
                    <h2 class="text-xl font-medium mb-2 bg-gray-200 p-2">Order Details</h2>

                    <div class="p-4">
                        <div class="py-2 md-py-6">
                            <div class="mt-8">
                                <div class="flow-root">
                                    <ul role="list" class="-my-6 divide-y divide-gray-200">
                                        @foreach ($carts['carts'] as $cart)
                                            @if($cart->Product)
                                                <li class="flex py-6">
                                                    <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                                        <img src="{{$cart->Product->img_paths['small']}}" alt="{{$cart->Product->title}}" class="h-full w-full object-cover object-center">
                                                    </div>

                                                    <div class="ml-4 flex flex-1 flex-col">
                                                        <div>
                                                            <div class="flex justify-between text-base font-medium text-font-color-dark">
                                                            <h3>
                                                                <a href="{{$cart->Product->route}}">{{$cart->Product->title}}</a>

                                                                @if($cart->Product->type == 'Variable')
                                                                <p class="mb-0"><small>{{$cart->ProductData->attribute_items_string ?? ''}}</small></p>
                                                                @endif
                                                            </h3>
                                                            <p class="ml-4">৳<span class="single_cart_amount">{{$cart->Product->prices['sale_price'] * $cart->quantity}}</span></p>
                                                            </div>

                                                            <div class="flex">
                                                                <a href="{{route('cart.remove', $cart->id)}}" onclick="return confirm(`Are you sure to remove?`);" class="mt-1 text-sm text-font-color-dark hover:text-red-700 cursor-pointer"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                                  </svg>
                                                                </a>

                                                                <div class="flex mt-1 ml-2 md:ml-5 cart_qty_wrap">
                                                                    <button type="button" class="w-6 h-6 text-center border-2 border-primary cursor-pointer font-bold border-l-0 bg-primary text-white updateCart" data-type="minus" data-id="{{$cart->id}}">-</button>
                                                                    <input type="number" value="{{$cart->quantity}}" class="h-6 border-2 border-primary px-1 w-10 focus:outline-none updateCartQty" readonly>
                                                                    <button type="button" class="w-6 h-6 text-center border-2 border-primary cursor-pointer font-bold border-r-0 bg-primary text-white updateCart" data-type="plus" data-id="{{$cart->id}}">+</button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{-- <div class="flex flex-1 items-end justify-between text-sm">
                                                            <p>Qty: {{$cart->quantity}}</p>
                                                        </div> --}}
                                                    </div>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 py-2 md-py-6 xl:pt-2 px-4 sm:px-6">
                        <div class="flex justify-between border-b text-base font-medium text-font-color-dark">
                            <p>Subtotal</p>
                            <p>৳<span class="product_total">{{number_format($carts['product_total'], 2)}}</span><input type="hidden" class="product_total_input" value="{{$carts['product_total']}}"></p>
                        </div>
                        <div class="flex justify-between border-b text-base font-medium text-font-color-dark">
                            <p>Payment Method: </p>
                            <p>Cash On Delivery</p>
                        </div>
                        <div class="border-b text-base font-medium text-font-color-dark">
                            {{-- <p>Shipping Charge: </p>
                            <p class="shipping_charge_text">60</p> --}}

                            <p class="text-right">
                                <label class="text-sm mb-2"><input type="radio" class="change_area information_field" name="change_area" value="Outside Dhaka" checked=""> Outside Dhaka: ৳ 100.00</label>
                                <br>
                                <label class="text-sm mb-2"><input type="radio" name="change_area" class="change_area information_field" value="Inside Dhaka"> Inside Dhaka: ৳ 60.00</label>
                            </p>
                            <input type="hidden" class="delivery_charge_input" name="delivery_charge" value="100">
                        </div>
                        <div class="flex justify-between border-b text-base font-medium text-font-color-dark">
                            <p>Grand Total: </p>
                            <p>৳<span class="grand_total">{{number_format(($carts['product_total'] + 100), 2)}}</span></p>
                        </div>

                        <input type="hidden" name="shipping_charge" class="shipping_charge" value="100">

                        <div class="mt-6">
                            <a href="{{route('homepage')}}" class="text-center rounded-md bg-black px-3 py-0.5 text-sm font-medium text-white float-right mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 inline-block">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                                </svg>

                                Back To Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        @else
            <div class="bg-white border rounded p-4">
                <div class="text-center text-lg py-20">
                    <p>No Item in in cart. <a href="{{route('homepage')}}" class="text-primary">Continue Shopping</a></p>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- @if(count($related_products))
<section class="bg-[#eaeaea] py-2 md:py-8">
    <div class="container my-4">
        <div>
            <h4 class="text-2xl font-semibold">Related Products</h4>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-2 md:gap-4 pt-6">
            @foreach ($related_products as $related_product)
                @include('front.layouts.product-loop', [
                    'product' => $related_product
                ])
            @endforeach
        </div>
    </div>
</section>
@endif --}}
@endsection

@section('footer')
    <script>
        let inside_dhaka_delivery_charge = 60;
        let outside_dhaka_delivery_charge = 100;

        $(document).on('click', '.updateCart', function(){
            let shipping_charge = $('.shipping_charge').val();
            let type = $(this).data('type');
            let calculated_quantity = 0;
            let cart_id = $(this).data('id');
            let quantity = $(this).closest('.cart_qty_wrap').find('.updateCartQty').val();

            if(type == 'plus'){
                calculated_quantity = Number(quantity) + 1;
            }else{
                calculated_quantity = Number(quantity) - 1;
            }

            if(calculated_quantity > 0){
                $(this).closest('.cart_qty_wrap').find('.updateCartQty').val(calculated_quantity);
                $.ajax({
                url: '{{route("cart.update")}}',
                method: 'POST',
                dataType: 'JSON',
                context: this,
                data: {cart_id, quantity: calculated_quantity, _token: '{{csrf_token()}}'},
                success: function(result){
                    $('.top_cart_count').html(result.summary.count);
                    $('.product_total').html(result.summary.product_total);
                    $('.product_total_input').val(result.summary.product_total);

                    $(this).closest('li').find('.single_cart_amount').html(result.single_amount);
                    $('.grand_total').html(Number(result.summary.product_total) + Number(shipping_charge));
                },
                error: function(){
                    cAlert('error', 'Update cart error!');
                }
            });
            }
        });

        $(document).on('change', '.change_area', function(){
            let area = $(this).val();
            let product_total_input = $('.product_total_input').val();

            if(area == 'Outside Dhaka'){
                $('.shipping_charge').val(Number(outside_dhaka_delivery_charge));
                // $('.shipping_charge_text').html(Number(outside_dhaka_delivery_charge));
                $('.grand_total').html((Number(product_total_input) + Number(outside_dhaka_delivery_charge)).toFixed(2));
            }else{
                $('.shipping_charge').val(Number(inside_dhaka_delivery_charge));
                // $('.shipping_charge_text').html(Number(inside_dhaka_delivery_charge));
                $('.grand_total').html((Number(product_total_input) + Number(inside_dhaka_delivery_charge)).toFixed(2));
            }
        });

        $(document).on('submit', '.disableDoubleClickOnSubmit', function(){
            $('#page_loader').show();
        });

        if (typeof fbq === "function"){
            fbq('track', 'InitiateCheckout');
        }

        // $(document).on('focusout', '.information_field', function(){
        //     activityTrack('Information Inserted');
        // });

        // function activityTrack(event){
        //     let shipping_mobile_number = $('.mobile_number').val() || '';
        //     if(shipping_mobile_number.length >= 11){
        //         let shipping_name = $('.shipping_name').val();
        //         let shipping_address = $('.shipping_address').val();
        //         let shipping_charge = $('.shipping_charge').val();
        //         let uu_id = $('.uu_id').val();

        //         $.ajax({
        //             url: "{{route('orderFailedTrackSaas')}}",
        //             method: "POST",
        //             data: {
        //                 _token: "{{csrf_token()}}",
        //                 uid: uu_id,
        //                 shipping_name,
        //                 event,
        //                 shipping_mobile_number,
        //                 shipping_address,
        //                 shipping_charge
        //             },
        //             success: function(){},
        //             error: function(){}
        //         });
        //     }
        // }
    </script>
@endsection
