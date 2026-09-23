@extends('back.layouts.master')
@section('title', 'Supplier Payment Details')

@section('master')
<div class="card border-light mt-3 shadow">
    <div class="card-header noPrint">
        <h5 class="d-inline-block">Invoice Information</h5>

        <button class="btn btn-info btn-sm float-right" type="button" onclick="window.print();"><i class="fas fa-print"></i> Print</button>
    </div>

    <div class="card-body">
        @include('back.layouts.print-header')

        <ul class="npnls">
            <li><b>ID:</b> {{$supplier_payment->id}}</li>
            <li><b>Date:</b> {{date('d/m/Y', strtotime($supplier_payment->created_at))}}</li>
            {{-- <li><b>Voucher No:</b> {{$supplier_payment->voucher_no}}</li> --}}
        </ul>

        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Supplier Name</th>
                        <th>
                            Total Amount
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($supplier_payment->supplier_payment_items as $supplier_payment_item)

                    <tr>
                        <td>{{$loop->index + 1}}</td>
                        <td><a href="{{$supplier_payment_item->supplier ? route('back.suppliers.show', $supplier_payment_item->supplier->id) : '#'}}">{{$supplier_payment_item->supplier->full_name ?? 'n/a'}}</a></td>
                        <td>{{amount($supplier_payment_item->amount)}}</td>
                    </tr>
                @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="2" class="text-right">Supplier Name</th>
                        <th>{{amount($supplier_payment->amount)}}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection


