@php
$total_amount = 0;
@endphp

<table>
	<thead>
		<tr>
			<th>ID</th>
			<th>Date</th>
			<th>Order Name</th>
			<th>Shipping Address</th>
			<th>Billing Address</th>
			<th>Mobile Number</th>
			<th>Product Total Amount</th>
			<th>Tax Amount</th>
			<th>Discount</th>
			<th>Order Total Amount</th>
			<th>Order Status</th>
			<th>Permanent Status</th>
		</tr>
	</thead>
	<tbody>
        @foreach($orders as $order)
            @php
                $total_amount += $order->grand_total;
            @endphp

            <tr>
                <td> {{$order->id}} </td>
                <td> {{ date('Y-m-d', strtotime($order->created_at)) }}</td>
                <td> {{ $order->full_name }} </td>
                <td> {{ $order->shipping_full_address }} </td>
                <td> {{ $order->full_address }} </td>
                <td> {{ $order->shipping_mobile_number }} </td>
                <td> {{ ($settings_g['currency_symbol'] ?? '$') . number_format($order->product_total, 2) }} </td>
                <td> {{ ($settings_g['currency_symbol'] ?? '$') . number_format($order->tax_amount, 2) }} </td>
                <td> {{ ($settings_g['currency_symbol'] ?? '$') . number_format($order->discount_amount, 2) }} </td>
                <td> {{ ($settings_g['currency_symbol'] ?? '$') . number_format($order->grand_total, 2) }} </td>
                <td> {{ $order->status }} </td>
                <td> {{ $order->payment_status }} </td>
            </tr>
        @endforeach

        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Total Amount</td>
            <td> {{ ($settings_g['currency_symbol'] ?? '$') . number_format($total_amount, 2) }} </td>
            <td></td>
            <td></td>
        </tr>
	</tbody>
</table>
