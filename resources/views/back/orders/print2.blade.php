<div class="card text-dark {{(isset($for) && $for == 'multiple' ? 'd-block mb-0' : 'd-none mb-3')}} text-dark print" style="border: 0">
    <div class="card-body" style="color: #000 !important;">
        <div class="invoice_wrap">
            <div class="i_company_info">
                <div class="row">
                    <div class="col-4">
                        <div class="ic_box">
                            <h2>Customer Info</h2>

                            <p><b><span>Contact Name</span>: {{$order->shipping_full_name}}</b></p>
                            <p><span>Address</span><span class="icb_address"><span>:</span> {{$order->shipping_full_address}}</span></p>
                            <p><span>Phone</span>: {{$order->shipping_mobile_number ?? 'N/A'}}</p>
                            @if($order->shipping_email)
                            <p><span>Email</span>: {{$order->shipping_email ?? 'N/A'}}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="ic_box">
                            <h2>Company Info</h2>
                            <p class="mb-0">{{$settings_g['title'] ?? env('APP_NAME')}}</p>
                            <p class="mb-0">{{$settings_g['street'] ?? ''}}</p>
                            <p class="mb-0">{{$settings_g['city'] ?? ''}}- {{$settings_g['zip'] ?? ''}}, {{$settings_g['state'] ?? ''}}</p>

                            <p class="mb-0">{{$settings_g['mobile_number'] ?? ''}} {{$settings_g['tel'] ? (', Tel: ' . $settings_g['tel']) : ''}}</p>
                            <p class="mb-0">{{$settings_g['email'] ?? ''}}</p>
                            <p class="mb-0">{{ env('APP_WEB_URL') ?? '' }}</p>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="ic_box">
                            <h2>INVOICE</h2>
                            <p><span>Invoice No</span>: {{$order->id}}</p>
                            <p><span>Invoice Date</span>: {{date('d/m/Y', strtotime($order->created_at))}}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="i_product_info">
                <table class="table table-striped table-sm table-hover text-dark mt-3" style="color: #000 !important;">
                    <tbody>
                    <tr id="table_head">
                        <th class="row-1 row-1 tbl-line-height">SL.</th>
                        <th class="row-5 row-4 tbl-line-height">Item Description</th>
                        <th class="row-2 row-3 tbl-line-height" style="width: 220px;">Price</th>
                        <th class="row-1 row-2 tbl-line-height" style="width: 20px;">Qty.</th>
                        <th class="row-2 row-3 tbl-line-height text-right" style="width: 200px;">Total</th>
                    </tr>

                    @foreach($order->OrderProducts as $i => $product)
                        <tr class="{{$i % 2 != 0 ? 'odd' : ''}}">
                            <td class="tbl-line-height">{{ $i + 1 }}</td>
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
                            <td class="tbl-line-height">{{amount($product->sale_price)}}</td>
                            <td class="tbl-line-height">{{$product->quantity}}</td>
                            <td class="tbl-line-height text-right">{{amount($product->sale_price * $product->quantity)}}</td>
                        </tr>
                    @endforeach

                    @if($order->status == 'Returned' || $order->status == 'Partial')
                    <tr class="return_tr">
                        <th colspan="5" class="text-center">Returned Items</th>
                    </tr>
                    @foreach($order->OrderProducts as $i => $product)
                        @if($product->return_quantity > 0)
                            <tr class="return_tr">
                                <td class="tbl-line-height">{{ $i + 1 }}</td>
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
                                <td class="tbl-line-height">{{amount($product->sale_price)}}</td>
                                <td class="tbl-line-height">{{$product->quantity}}</td>
                                <td class="tbl-line-height text-right">{{amount($product->sale_price * $product->quantity)}}</td>
                            </tr>
                        @endif
                    @endforeach
                    <tr class="return_tr">
                        <td class="tbl-line-height text-right" colspan="4">Total Returned</td>
                        <td class="tbl-line-height text-right">{{amount($order->refund_product_total)}}</td>
                    </tr>
                    @endif
                    </tbody>
                </table>
            </div>

            <div class="i_footer">
                <div class="row">
                    <div class="col-md-4">
                        <p class="mb-4"><b>Thank you for your Business</b></p>
                    </div>

                    <div class="col-md-4">
                    </div>

                    <div class="col-md-4">
                        <div class="if_summary">
                            <div class="row">
                                <div class="col-4">
                                    <p><b>Subtotal</b></p>
                                    <p><b>Discount</b></p>
                                    <p><b>Shipping</b></p>
                                    @if($order->refund_shipping_amount)
                                    <p><b>Shipping Refund</b></p>
                                    @endif
                                    @if($order->refund_total_amount)
                                    <p><b>Total Refund</b></p>
                                    @endif
                                </div>

                                <div class="col-8">
                                    <p>: {{ amount($order->product_total) }}</p>
                                    <p>: {{ amount($order->discount_amount) }}</p>
                                    <p>: {{ amount($order->shipping_charge) }}</p>
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
            </div>
        </div>
    </div>
</div>
