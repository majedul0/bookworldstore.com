@extends('back.layouts.master')
@section('title', 'Edit Other Expenses')

@php
    $pagination = request('pagination') == 'false' ? false : true;
    $total = 0;
    $from_date = request('from_date') ?? '';
    $to_date = request('to_date') ?? '';
@endphp

@section('master')
<div class="row">
    <div class="col-md-4">
        <div class="card card-primary noPrint">
            <div class="card-header">
                <h6 class="mb-0">Edit Expense</h6>
            </div>

            <form action="{{route('back.accounts.otherExpensesEdit', $expense->id)}}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label><b>Date*</b></label>
                        <input type="date" name="date" class="form-control form-control-sm" value="{{old('date', date('Y-m-d', strtotime($expense->created_at)))}}" required>
                    </div>
                    <div class="form-group">
                        <label><b>Amount*</b></label>
                        <input type="number" step="any" name="amount" class="form-control form-control-sm" value="{{old('amount', $expense->amount)}}" required>
                    </div>
                    <div class="form-group">
                        <label><b>Purpose*</b></label>
                        <input type="text" name="purpose" class="form-control form-control-sm" value="{{old('purpose', $expense->note)}}" required>
                    </div>
                </div>

                <div class="card-footer">
                    <button class="btn btn-success btn-block create_btn">Create</button>
                    <small><b>NB: *</b> marked are required field.</small>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script>
    let export_columns = [0, 1, 2, 3];
    datatable_dir = 'desc';
    datatable_filename = 'Other Expenses';
    datatable_paging = false;
</script>

@include('back.layouts.datatableJS')
@endsection
