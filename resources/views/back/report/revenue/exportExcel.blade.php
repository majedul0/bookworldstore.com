<table>
	<thead>
		<tr>
			<th>ID</th>
			<th>Date</th>
			<th>Debit</th>
			<th>Credit</th>
			<th>Balance</th>
			<th>Note</th>
		</tr>
	</thead>
	<tbody>
        @foreach($accounts as $account)
            <tr>
                <td> {{$account->id}} </td>
                <td> {{ date('Y-m-d', strtotime($account->created_at)) }}</td>
                <td> {{ $account->type == 'Debit' ? (($settings_g['currency_symbol'] ?? '$') . number_format($account->amount, 2)) : '' }} </td>
                <td> {{ $account->type == 'Credit' ? (($settings_g['currency_symbol'] ?? '$') . number_format($account->amount, 2)) : '' }} </td>
                <td> {{ ($settings_g['currency_symbol'] ?? '$') . number_format($account->current_balance, 2) }} </td>
                <td> {{ $account->note }} </td>
            </tr>
        @endforeach
	</tbody>
</table>
