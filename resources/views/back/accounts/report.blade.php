@extends('back.layouts.master')
@section('title', 'Customers')

@php
    $from_date = request('from_date') ?? '';
    $to_date = request('to_date') ?? '';
@endphp

@section('master')
<div class="card card-primary noPrint">
    <div class="card-body">
        <form action="{{route('back.accounts.report')}}" method="get" accept-charset="utf-8">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="from_date"><b>Start Date</b></label>
                        <input type="date" name="from_date" class="form-control form-control-sm" id="from_date" placeholder="Start Date" value="{{$from_date}}" autocomplete="off">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="to_date"><b>End Date</b></label>
                        <input type="date" name="to_date" class="form-control form-control-sm" id="to_date" placeholder="End Date" value="{{$to_date}}" autocomplete="off">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-sm btn-success" autocomplete="off">Search</button>
            <a href="{{route('back.accounts.report')}}" class="btn btn-sm btn-danger">Reset</a>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card border-light mt-3 shadow">
            <div class="card-header">
                <h5 class="d-inline-block">Sales Amount</h5>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <p class="mb-0">Total Sales Amount</p>
                            </td>
                            <td>{{amount($total_sales)}}</td>
                        </tr>
                        <tr>
                            <td>
                                <p class="mb-0">Total Shipping Charge</p>
                            </td>
                            <td>{{amount($total_shipping_charge)}}</td>
                        </tr>
                        <tr>
                            <th>
                                <p class="mb-0">After Shipping Charge</p>
                            </th>
                            <td>{{amount($after_shipping = $total_sales - $total_shipping_charge)}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card border-light mt-3 shadow">
            <div class="card-header">
                <h5 class="d-inline-block">Profit/Loss</h5>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <p class="mb-0">Total Income</p>
                            </td>
                            <td>{{amount($after_shipping)}}</td>
                        </tr>
                        <tr>
                            <td>
                                <p class="mb-0">Total Expense</p>
                            </td>
                            <td>{{amount($supplier_payment_amount)}}</td>
                        </tr>
                        <tr>
                            <th>
                                <p class="mb-0">Profit/Loss</p>
                            </th>
                            <td>{{amount($after_shipping - $supplier_payment_amount)}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                <p class="mb-0"><b>NB:</b> সেলস এমাউন্ট এর হিসাব "Completed" অর্ডার থেকে করা হবে!</p>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-light mt-3 shadow">
            <div class="card-header">
                <h5 class="d-inline-block">Purchase Amount</h5>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <p class="mb-0">Total Supplier Payment</p>
                            </td>
                            <td>{{amount($supplier_payment_amount)}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card border-light mt-3 shadow">
            <div class="card-header">
                <h5 class="d-inline-block">Courier Amount</h5>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <p class="mb-0">In Hold</p>
                            </td>
                            <td>{{amount($hold_amount)}}</td>
                        </tr>
                        <tr>
                            <td>
                                <p class="mb-0">Receivable</p>
                            </td>
                            <td>{{amount($receivable_amount)}}</td>
                        </tr>
                        <tr>
                            <th>
                                <p class="mb-0">Total Receive</p>
                            </th>
                            <td>{{amount($received_amount)}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
