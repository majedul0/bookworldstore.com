<div class="card text-dark mb-3 text-dark print {{(isset($for) && $for == 'multiple' ? '' : 'd-none')}}" style="border: 0">
{{-- <div class="card text-dark mb-3 text-dark print" style="border: 0"> --}}
    <div class="card-body p-0" style="color: #000 !important;">
        <div class="invoice_wrap">
            {{-- <div class="in_header">
                <div class="yellow-side"></div>
                <div class="separator"></div>
            </div> --}}
            {{-- <div class="mb-5 px-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="i_logo"><img src="{{$settings_g['logo']}}"></div>
                    </div>
                    <div class="col-md-6 text-right">
                        <h2 style="font-size: 50px;"><b>INVOICE</b></h2>
                    </div>
                </div>
            </div> --}}

            <h2 style="font-size: 22px;text-align:center;margin-bottom: 15px"><b>INVOICE</b></h2>

            <div class="px-4">
                <div class="row">
                    <div class="col-7">
                        <div class="i_logo"><img src="{{$settings_g['logo']}}"></div>
                        <h4 class="mb-1"><b>{{$settings_g['title'] ?? env('APP_NAME')}}</b></h4>
                        <h5 class="mb-0">{{$settings_g['mobile_number'] ?? ''}}</h5>
                        <p class="mb-0">{{$settings_g['street'] ?? ''}}</p>
                    </div>
                    <div class="col-5">
                        <p class="mb-0"><b style="width: 125px;display: inline-block;">Order No:</b> {{$order->id}}</p>
                        <p class="mb-2"><b style="width: 125px;display: inline-block;">Invoice Date:</b> {{date('d/m/Y', strtotime($order->created_at))}}</p>

                        <h4 class="mb-1"><b>BILLING TO:</b></h4>
                        <h5 class="mb-0">{{$order->shipping_full_name}}</h5>
                        <p class="mb-0">{{$order->shipping_street}}</p>
                        <p>{{$order->shipping_mobile_number}}</p>
                    </div>
                </div>
            </div>

            <div class="i_product_info px-4">
                <table class="table table-striped table-hover text-dark mt-3" style="color: #000 !important;">
                    <tbody>
                        <tr id="table_head">
                            <th class="row-5 row-4 tbl-line-height">ITEM NAME</th>
                            <th class="row-2 row-3 tbl-line-height text-center" style="width: 220px;">PRICE</th>
                            <th class="row-1 row-2 tbl-line-height text-center" style="width: 20px;">QTY</th>
                            <th class="row-2 row-3 tbl-line-height  text-center" style="width: 200px;">TOTAL</th>
                        </tr>

                        @foreach($order->OrderProducts as $i => $product)
                            <tr class="{{$i % 2 != 0 ? 'odd' : ''}}">
                                <td class="tbl-line-height">
                                    {{ $product->Product->title }}
                                    @php
                                        $variable_attr = $product->ProductData->attribute_items_string ?? '';
                                    @endphp
                                    {{$variable_attr}}

                                    @if($variable_attr)
                                    <br>
                                    @endif
                                    <br>
                                    {{$product->simple_attributes_arr['string'] ?? ''}}
                                </td>
                                <td class="tbl-line-height text-center">{{amount($product->sale_price)}}</td>
                                <td class="tbl-line-height text-center">{{$product->quantity}}</td>
                                <td class="tbl-line-height  text-center">{{amount($product->sale_price * $product->quantity)}}</td>
                            </tr>
                        @endforeach

                        @if($order->status == 'Returned' || $order->status == 'Partial')
                        <tr class="return_tr">
                            <th colspan="4" class="text-center">Returned Items</th>
                        </tr>
                        @foreach($order->OrderProducts as $i => $product)
                            @if($product->return_quantity > 0)
                                <tr class="return_tr">
                                    <td class="tbl-line-height">
                                        {{ $product->Product->title }}
                                        @php
                                            $variable_attr = $product->ProductData->attribute_items_string ?? '';
                                        @endphp
                                        {{$variable_attr}}

                                        @if($variable_attr)
                                        <br>
                                        @endif
                                        <br>
                                        {{$product->simple_attributes_arr['string'] ?? ''}}
                                    </td>
                                    <td class="tbl-line-height">{{($settings_g['currency_symbol'] ?? '$') . $product->sale_price}}</td>
                                    <td class="tbl-line-height">{{$product->quantity}}</td>
                                    <td class="tbl-line-height text-right">{{($settings_g['currency_symbol'] ?? '$') . ($product->sale_price * $product->quantity)}}</td>
                                </tr>
                            @endif
                        @endforeach
                        <tr class="return_tr">
                            <td class="tbl-line-height text-right" colspan="3">Total Returned</td>
                            <td class="tbl-line-height text-right">{{($settings_g['currency_symbol'] ?? '$') . ($order->refund_product_total)}}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="i_footer mt-5 px-4">
                <div class="row">
                    <div class="col-8">
                        <div class="col-8">
                            <div class="i_sign">
                                <h6>SIGNATURE</h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="if_summary">
                            <div class="row">
                                <div class="col-4">
                                    <p><b>Subtotal</b></p>
                                    @if($order->discount_amount && $order->discount_amount > 0)
                                    <p><b>Discount</b></p>
                                    @endif
                                    <p><b>Shipping</b></p>
                                    <p><b>Paid</b></p>
                                    <p><b>Due</b></p>
                                    @if($order->refund_shipping_amount)
                                    <p><b>Shipping Refund</b></p>
                                    @endif
                                    @if($order->refund_total_amount)
                                    <p><b>Total Refund</b></p>
                                    @endif
                                </div>

                                <div class="col-8">
                                    <p>: {{ amount($order->product_total) }}</p>
                                    @if($order->discount_amount && $order->discount_amount > 0)
                                    <p>: {{ amount($order->discount_amount) }}</p>
                                    @endif
                                    <p>: {{ amount($order->shipping_charge) }}</p>
                                    <p>: {{ amount($order->paid_amount) }}</p>
                                    <p>: {{ amount($order->due) }}</p>
                                    @if($order->refund_shipping_amount)
                                    <p>: {{ amount($order->refund_shipping_amount) }}</p>
                                    @endif
                                    @if($order->refund_total_amount)
                                    <p>: {{ amount($order->refund_total_amount) }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="if_summary_total">
                                <div class="row">
                                    <div class="col-4">
                                        <p><h6>TOTAL</h6></p>
                                    </div>

                                    <div class="col-8">
                                        <p><h6>: {{ amount($order->grand_total) }}</h6></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="row mt-5">
                    <div class="col-md-6">
                        <div class="i_sign p-0" style="border-top: none">
                            <h6>TERMS & CONDITIONS:</h6>
                        </div>
                    </div>
                    <div class="col-md-6 text-right">
                        <div class="i_sign">
                            <h6>SIGNATURE</h6>
                        </div>
                    </div>
                </div> --}}
            </div>

            <div class="print-footer mt-2">
                <div class="row">
                    <div class="col-8 relative">
                        <div class="px-4" style="background: black !important;color: white;padding: 12px 7px;height: 45px;">
                            <div class="row">
                                <div class="col-6 overflow-hidden" style="background: black !important;">
                                    <i class="fas fa-globe mr-2" style="background: {{$settings_g['primary_color'] ?? '#c04000'}} !important;color: white;
                                    width: 20px;
                                    height: 20px;
                                    text-align: center;
                                    line-height: 20px;
                                    border-radius: 100%;
                                    font-size: 13px;float:left"></i> <span style="margin-top: -2px;float: left;background: black !important;color: white !important">{{env('APP_DOMAIN')}}</span>
                                </div>
                                <div class="col-6 overflow-hidden" style="background: black !important;">
                                    <i class="fas fa-phone mr-2" style="background: {{$settings_g['primary_color'] ?? '#c04000'}} !important;color: white;
                                    width: 20px;
                                    height: 20px;
                                    text-align: center;
                                    line-height: 20px;
                                    border-radius: 100%;
                                    font-size: 10px;float:left;color: white !important"></i> <span style="margin-top: -2px;float: left;background: black !important;color: white !important">{{$settings_g['mobile_number'] ?? ''}}</span>
                                </div>
                            </div>
                        </div>

                        <div style="background: white;
                        height: 50px;
                        position: absolute;
                        top: 0;
                        right: -32px;
                        width: 65px;
                        transform: skewX(-37deg);
                        z-index: 9;"></div>
                    </div>
                    <div class="col-4">
                        <div style="background: {{$settings_g['primary_color'] ?? '#c04000'}} !important;color: white;height: 45px">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
