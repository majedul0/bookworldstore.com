@extends('back.layouts.master')
@section('title', 'Supplier Payments')

@php
    $pagination = request('pagination') == 'false' ? false : true;
    $total = 0;
    $from_date = request('from_date') ?? '';
    $to_date = request('to_date') ?? '';
@endphp

@section('master')
<div class="card card-primary noPrint" style="margin-top: 25px">
    <div class="card-body">
        <form action="{{route('back.suppliers.payments')}}" method="get" accept-charset="utf-8">
            <input type="hidden" name="pagination" value="{{$pagination}}">
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

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="to_date"><b>Supplier</b></label>
                        <select name="supplier" class="form-control form-control-sm">
                            <option value="">All</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{$supplier->id}}" {{request('supplier') == $supplier->id ? 'selected' : ''}}>{{$supplier->full_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-sm btn-success" autocomplete="off">Search</button>
            <a href="{{route('back.suppliers.payments')}}" class="btn btn-sm btn-danger">Reset</a>
        </form>
    </div>
</div>

<section class="card card-primary" style="margin: 10px 0">
    <div class="card-body">
        {{-- @include('back.layouts.print-header') --}}

        <table id="dataTable" class="table table-sm table-bordered table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                {{-- <th>Voucher No</th> --}}
                <th class="text-right">
                    Total Amount
                </th>
                <th>Remark</th>
                <th class="text-right">Action</th>
            </tr>
            </thead>
            <tbody>
                @foreach($supplier_payments as $supplier_payment)
                    @php
                        $total += $supplier_payment->amount;
                    @endphp

                    <tr>
                        <td><a href="{{route('back.suppliers.paymentDetails', $supplier_payment->id)}}">{{$supplier_payment->id}}</a></td>
                        <td>{{date('d/m/Y', strtotime($supplier_payment->date))}}</td>
                        {{-- <td>{{$supplier_payment->voucher_no}}</td> --}}
                        <td class="text-right">{{amount($supplier_payment->amount)}}</td>
                        <td>{{$supplier_payment->remark}}</td>
                        <td class="text-right"><a href="{{route('back.suppliers.paymentDetails', $supplier_payment->id)}}" class="btn btn-sm btn-info">Details</a></td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <th></th>
                    {{-- <th></th> --}}
                    <th class="text-right">Total</th>
                    <th class="text-right">{{amount($total)}}</th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</section>
@endsection

@section('footer')
<script>
    let export_columns = [0, 1, 2, 3, 4];
    datatable_dir = 'desc';
    datatable_filename = 'Supplier Payments';
    datatable_paging = false;
</script>

@include('back.layouts.datatableJS')
@endsection
