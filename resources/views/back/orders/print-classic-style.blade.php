<div class="card text-dark mb-3 text-dark d-none print" style="border: 0">
    <div class="card-body" style="color: #000 !important;">
        <div class="invoiceHead text-center">
            <h4>{{$settings_g['title'] ?? env('APP_NAME')}}</h4>

            <div class="address d-inline-block">
                <p class="mb-0">Mobile: {{$settings_g['mobile_number'] ?? ''}}</p>
                <p class="mb-0">{{$settings_g['street'] ?? ''}}</p>
                <p class="mb-0">Web: {{ env('APP_WEB_URL') ?? '' }}, E-mail: {{$settings_g['email'] ?? ''}}</p>
            </div>

            <div class="logo float-left" >
                <h5 class="d-none orderNumber"><b>Order ID: #{{ $order->id }}</b></h5>
                <br>
                <img src="{{$settings_g['logo'] ?? ''}}" style="max-width: 165px;position: absolute;top: 20px;height: 110px;object-fit: contain;">
            </div>
        </div>
        <hr>

        <div class="row">
            <div class="col-md-8">
                <h4 class="mb-0">Billing Address</h4>
                <h5 class="mb-0">Name: {{ $order->full_name }}</h5>
                <h5 style="font-weight: normal">
                    {{$order->street}}
                    <br>
                    {{$order->city}} - {{$order->zip}}, {{$order->state}}, {{$order->country}}
                </h5>

                <h4 class="mb-0">Shipping Address</h4>
                <h5 class="mb-0">Name: {{ $order->shipping_full_name }}</h5>
                <h5 style="font-weight: normal">
                    {{$order->shipping_street}}
                    <br>
                    {{$order->shipping_city}} - {{$order->shipping_post_code}}, {{$order->shipping_state}}, {{$order->shipping_country}}
                </h5>
            </div>

            <div class="col-md-4">
                <h5>Phone: {{ $order->shipping_mobile_number }}</h5>
                <h5>Date: {{ date('d-m-Y', strtotime($order->created_at)) }}</h5>
            </div>
        </div>

        <table class="table table-sm table-striped table-hover table-bordered text-dark mt-3" style="color: #000 !important;">
            <tbody>
            <tr id="table_head">
                <th class="row-1 row-1 tbl-line-height">Sl</th>
                <th class="row-5 row-4 tbl-line-height">Description</th>
                <th class="row-2 row-3 tbl-line-height" style="width: 220px;">Price</th>
                <th class="row-1 row-2 tbl-line-height" style="width: 20px;">Quantity</th>
                <th class="row-2 row-3 tbl-line-height text-right" style="width: 200px;">Total</th>
            </tr>

            @foreach($order->OrderProducts as $i => $product)
            <tr>
                <td class="tbl-line-height">{{ $i + 1 }}</td>
                <td class="tbl-line-height">{{ $product->Product->title }} {{$product->ProductData->attribute_items_string}}</td>
                <td class="tbl-line-height">{{($settings_g['currency_symbol'] ?? '$') . $product->sale_price}}</td>
                <td class="tbl-line-height">{{$product->quantity}}</td>
                <td class="tbl-line-height text-right">{{($settings_g['currency_symbol'] ?? '$') . ($product->sale_price * $product->quantity)}}</td>
            </tr>
            @endforeach

            <tr>
                <td colspan="2" class="tbl-line-height"></td>
                <td colspan="2" class="tbl-line-height">Subtotal</td>
                <td class="tbl-line-height text-right">{{ ($settings_g['currency_symbol'] ?? '$') . $order->product_total }}</td>
            </tr>

            <tr>
                <td colspan="2" class="tbl-line-height"></td>
                <td colspan="2" class="tbl-line-height">Discount</td>
                <td class="tbl-line-height text-right">{{ ($settings_g['currency_symbol'] ?? '$') . $order->discount_amount }}</td>
            </tr>

            <tr>
                <td colspan="2" class="tbl-line-height"></td>
                <td colspan="2" class="tbl-line-height">After Discount</td>
                <td class="tbl-line-height text-right">{{ ($settings_g['currency_symbol'] ?? '$') . ($order->product_total - $order->discount_amount) }}</td>
            </tr>

            <tr>
                <td colspan="2" class="tbl-line-height"></td>
                <td colspan="2" class="tbl-line-height">TAX</td>
                <td class="tbl-line-height text-right">{{ ($settings_g['currency_symbol'] ?? '$') . $order->tax_amount }}</td>
            </tr>

            <tr>
                <td colspan="2" class="tbl-line-height"></td>
                <td colspan="2" class="tbl-line-height">Shipping</td>
                <td class="tbl-line-height text-right">{{ ($settings_g['currency_symbol'] ?? '$') . $order->shipping_charge }}</td>
            </tr>

            <tr class="mb-5">
                <td colspan="2" class="tbl-line-height"></td>
                <td colspan="2" class="tbl-line-height">Grand total</td>
                <td class="tbl-line-height text-right">{{ ($settings_g['currency_symbol'] ?? '$') . $order->grand_total }}</td>
            </tr>
            </tbody>
        </table>

        @if($for == 'admin')
        <div class="float-right mt-5">
            <p style="line-height: 6px;">----------------------------------</p>
            <p style="line-height: 6px;" class="text-center">Shop Signature</p>
        </div>
        @endif
    </div>
</div>
