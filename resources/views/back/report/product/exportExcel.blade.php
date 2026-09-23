<table>
	<thead>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Available Stock</th>
			<th>Purchase</th>
			<th>Sold</th>
			<th>Profit/Loss</th>
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
                <td> {{$key + 1}} </td>
                <td> {{ $product->title }} </td>
                <td> {{ $product->stock }} </td>
                <td> {{ ($settings_g['currency_symbol'] ?? '$') . number_format($purchase->amount ?? 0, 2) }} </td>
                <td> {{ ($settings_g['currency_symbol'] ?? '$') . number_format($sold->amount ?? 0, 2) }} </td>
                <td> {{ ($settings_g['currency_symbol'] ?? '$') . number_format(($sold->amount ?? 0) - ($purchase->amount ?? 0), 2) }} </td>
            </tr>
        @endforeach
	</tbody>
</table>
