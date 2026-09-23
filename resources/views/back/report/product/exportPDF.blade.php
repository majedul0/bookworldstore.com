<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product Report</title>
</head>

<body style="box-sizing:border-box;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';" data-saferedirecturl="https://www.google.com/url?q=http://localhost&amp;source=gmail&amp;ust=1607844883689000&amp;usg=AFQjCNE16dMwx_olZ4z84pumj26bbtpg_g">
    <table style="width: 100%">
        <thead>
            <tr>
                <th style="width: 100%;overflow:hidden">
                    <img style="width: 100px; display:inline-block;float:left" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQp0vqAhuT3qyL72y_sdyJHNBxOHrWpENh_GA&usqp=CAU">
                    <h1 style="float: left;text-transform: uppercase;margin-top: 50px;margin-left: 15px;">{{$settings_g['title'] ?? env('APP_NAME')}}</h1>
                </th>
            </tr>
        </thead>
    </table>

    <table style="border-collapse: collapse;width: 100%;">
        <thead>
            <tr>
               <th style="border: 1px solid black;text-align:left">ID</th>
               <th style="border: 1px solid black;text-align:left">Name</th>
               <th style="border: 1px solid black;text-align:left">Available Stock</th>
               <th style="border: 1px solid black;text-align:left">Purchase</th>
               <th style="border: 1px solid black;text-align:left">Sold</th>
               <th style="border: 1px solid black;text-align:right">Profit/Loss</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $key => $product)
                @php
                    $purchase = \App\Models\Product\Stock::query();
                    // Date Filter
                    if($from_date){
                        $purchase->whereDate('created_at', '>=', $from_date);
                    }
                    if($to_date){
                        $purchase->whereDate('created_at', '<=', $to_date);
                    }
                    $purchase->where('product_id', $product->id)->select(DB::raw('SUM(purchase_price * purchase_quantity) as `amount`'));
                    $purchase = $purchase->groupby('product_id')->orderByDesc('product_id')->first();


                    $sold = \App\Models\Order\OrderProduct::query();
                    // Date Filter
                    if($from_date){
                        $sold->whereDate('created_at', '>=', $from_date);
                    }
                    if($to_date){
                        $sold->whereDate('created_at', '<=', $to_date);
                    }
                    $sold->where('product_id', $product->id)->select(DB::raw('SUM(sale_price * quantity) as `amount`'));
                    $sold = $sold->groupby('product_id')->orderByDesc('product_id')->first();
                @endphp

                <tr>
                    <td style="border: 1px solid black"> {{$key + 1}} </td>
                    <td style="border: 1px solid black"> {{ $product->title }} </td>
                    <td style="border: 1px solid black"> {{ $product->stock }} </td>
                    <td style="border: 1px solid black"> {{ ($settings_g['currency_symbol'] ?? '$') . number_format($purchase->amount ?? 0, 2) }} </td>
                    <td  style="border: 1px solid black"> {{ ($settings_g['currency_symbol'] ?? '$') . number_format($sold->amount ?? 0, 2) }} </td>
                    <td  style="border: 1px solid black;text-align:right"> {{ ($settings_g['currency_symbol'] ?? '$') . number_format(($sold->amount ?? 0) - ($purchase->amount ?? 0), 2) }} </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
