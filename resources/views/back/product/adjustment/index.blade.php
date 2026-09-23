@extends('back.layouts.master')
@section('title', 'Purchase')

@section('master')
@php
    $pagination = request('pagination') == 'false' ? false : true;
    $total = 0;
    $from_date = request('from_date') ?? '';
    $to_date = request('to_date') ?? '';
@endphp

<div class="card card-primary noPrint" style="margin-top: 25px">
    <div class="card-body">
        <form action="{{route('back.adjustments.index')}}" method="get" accept-charset="utf-8">
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
            <a href="{{route('back.adjustments.index')}}" class="btn btn-sm btn-danger">Reset</a>
        </form>
    </div>
</div>

<div class="card border-light mt-3 shadow">
    <div class="card-header">
        <h5 class="d-inline-block">Purchase List</h5>

        <a href="{{route('back.adjustments.create')}}" class="btn btn-success btn-sm float-right"><i class="fas fa-plus"></i> New Purchase</a>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-sm table-hover" id="dataTable">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Date</th>
                <th scope="col">Supplier</th>
                <th scope="col">Total Amount</th>
                <th scope="col">Paid Amount</th>
                <th scope="col">Due Amount</th>
                <th scope="col">Note</th>
                <th scope="col" class="text-right">Action</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($purchases as $adjustment)
                    <tr>
                        <th scope="row">
                            <a href="{{route('back.adjustments.show', $adjustment->id)}}">{{$adjustment->id}}</a>
                        </th>
                        <td>{{date('d/m/Y', strtotime($adjustment->created_at))}}</td>
                        <td><a href="{{route('back.suppliers.show', $adjustment->user_id)}}">{{$adjustment->user->full_name ?? 'n/a'}}</a></td>
                        <td>{{amount($adjustment->grand_total)}}</td>
                        <td>{{amount($adjustment->paid_amount)}}</td>
                        <td>{{amount($adjustment->due)}}</td>
                        <td>{{$adjustment->note}}</td>
                        <td class="text-right">
                            <a href="{{route('back.adjustments.show', $adjustment->id)}}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Details</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('footer')
<script>
    let export_columns = [0, 1, 2, 3, 4, 5, 6];
    datatable_dir = 'desc';
    datatable_filename = 'Purchase List';
    datatable_paging = false;
</script>

@include('back.layouts.datatableJS')
@endsection
