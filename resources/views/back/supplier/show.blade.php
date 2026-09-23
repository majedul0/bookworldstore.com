@extends('back.layouts.master')
@section('title', 'Suppliers Details')

@section('master')
<div class="card border-light mt-3 shadow">
    <div class="card-header">
        <h5 class="d-inline-block">Suppliers Summary <b>{{$user->full_name}}</b></h5>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-sm">
            <tbody>
                <tr>
                    <th class="text-right">Total Purchase</th>
                    <td>{{amount($total_purchase)}}</td>
                </tr>
                <tr>
                    <th class="text-right">Payable Amount</th>
                    <td>{{$user->balance < 0 ? amount(abs($user->balance)) : amount(0 - $user->balance)}}</td>
                </tr>
                <tr>
                    <th class="text-right">Pay Now</th>
                    <td>
                        <a href="" class="btn btn-sm btn-info">Supplier Payment</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card border-light mt-3 shadow">
    <div class="card-header">
        <h5 class="d-inline-block">Suppliers Ledger of <b>{{$user->full_name}}</b></h5>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-sm" id="dataTable">
            <thead>
              <tr>
                <th scope="col">SL.</th>
                <th scope="col">Invoice 1</th>
                <th scope="col">Invoice 2</th>
                <th scope="col">Debit</th>
                <th scope="col">Credit</th>
                <th scope="col">Current Balance</th>
                <th scope="col">Description</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($ledgers as $ledger)
                    <tr>
                        <th scope="row">{{$loop->index + 1}}</th>
                        <td>{{$ledger->invoice_no_1}}</td>
                        <td>{{$ledger->invoice_no_2}}</td>
                        <td>{{amount($ledger->debit)}}</td>
                        <td>{{amount($ledger->credit)}}</td>
                        <td>{{amount($ledger->current_balance)}}</td>
                        <td>{{$ledger->description}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
