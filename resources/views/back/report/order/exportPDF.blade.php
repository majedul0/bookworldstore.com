<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order Report</title>
</head>

@php
    $total_amount = 0;
@endphp

<body style="box-sizing:border-box;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';" data-saferedirecturl="https://www.google.com/url?q=http://localhost&amp;source=gmail&amp;ust=1607844883689000&amp;usg=AFQjCNE16dMwx_olZ4z84pumj26bbtpg_g">

    {{-- <div style="text-align:center">
        <img src="{{$settings_g['logo'] ?? ''}}" style="">
    </div> --}}

    <table style="width: 100%">
        <thead>
            <tr>
                <th colspan="6" style="text-align:left">
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents($logo_path)) }}" style="width: 280px;height:110px;object-fit: contain;">
                </th>

                <th colspan="6" style="text-align:right">
                    <h3>{{$title}}</h3>
                </th>
            </tr>
            {{-- <tr>
                <th style="width: 100%;overflow:hidden;height: 250px" colspan="12">
                    <img style="width: 100px; display:inline-block;float:left" src="data:image/png;base64,{{ base64_encode(file_get_contents($logo_path)) }}">
                    <h1 style="float: left;text-transform: uppercase;margin-top: 50px;margin-left: 15px;">{{$settings_g['title'] ?? env('APP_NAME')}}</h1>
                </th>
            </tr> --}}
        </thead>
    </table>

    <table style="border-collapse: collapse;width: 100%;">
        <thead>
            <tr>
               <th style="border: 1px solid black;text-align:left">ID</th>
               <th style="border: 1px solid black;text-align:left">Date</th>
               <th style="border: 1px solid black;text-align:left">Order Name</th>
               <th style="border: 1px solid black;text-align:left">Shipping Address</th>
               <th style="border: 1px solid black;text-align:left">Billing Address</th>
               <th style="border: 1px solid black;text-align:left">Mobile Number</th>
               <th style="border: 1px solid black;text-align:left">Product Total Amount</th>
               <th style="border: 1px solid black;text-align:left">Tax Amount</th>
               <th style="border: 1px solid black;text-align:left">Discount</th>
               <th style="border: 1px solid black;text-align:left">Order Total Amount</th>
               <th style="border: 1px solid black;text-align:left">Order Status</th>
               <th style="border: 1px solid black;text-align:center">Permanent Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $key => $order)
                @php
                    $total_amount += $order->grand_total;
                @endphp

                <tr>
                    <td style="border: 1px solid black"> {{$order->id}} </td>
                    <td style="border: 1px solid black"> {{ date('Y-m-d', strtotime($order->created_at)) }} </td>
                    <td style="border: 1px solid black"> {{ $order->full_name }} </td>
                    <td style="border: 1px solid black"> {{ $order->shipping_full_address }} </td>
                    <td style="border: 1px solid black"> {{ $order->full_address }} </td>
                    <td style="border: 1px solid black"> {{ $order->shipping_mobile_number }} </td>
                    <td style="border: 1px solid black"> {{ ($settings_g['currency_symbol'] ?? '$') . number_format($order->product_total, 2) }} </td>
                    <td style="border: 1px solid black"> {{ ($settings_g['currency_symbol'] ?? '$') . number_format($order->tax_amount, 2) }} </td>
                    <td style="border: 1px solid black"> {{ ($settings_g['currency_symbol'] ?? '$') . number_format($order->discount_amount, 2) }} </td>
                    <td style="border: 1px solid black"> {{ ($settings_g['currency_symbol'] ?? '$') . number_format($order->grand_total, 2) }} </td>
                    <td style="border: 1px solid black"> {{ $order->status }} </td>
                    <td style="border: 1px solid black;text-align:center"> {{ $order->payment_status }} </td>
                </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <th style="border: 1px solid black;text-align:right;" colspan="9">Total Amount</th>
                <th style="border: 1px solid rgb(32, 30, 30);text-align:left;" colspan="3"> {{ ($settings_g['currency_symbol'] ?? '$') . number_format($total_amount, 2) }} </th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
