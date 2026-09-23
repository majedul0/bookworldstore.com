@extends('back.layouts.master')
@section('title', 'Other Expenses')

@php
    $pagination = request('pagination') == 'false' ? false : true;
    $total = 0;
    $from_date = request('from_date') ?? '';
    $to_date = request('to_date') ?? '';
@endphp

@section('master')
<div class="row">
    <div class="col-md-8">
        <div class="card card-primary noPrint">
            <div class="card-body">
                <form action="{{route('back.accounts.otherExpenses')}}" method="get" accept-charset="utf-8">
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
                    </div>

                    <button type="submit" class="btn btn-sm btn-success" autocomplete="off">Search</button>
                    <a href="{{route('back.accounts.otherExpenses')}}" class="btn btn-sm btn-danger">Reset</a>
                </form>
            </div>
        </div>

        <section class="card card-primary" style="margin: 10px 0">
            <div class="card-body">
                <table id="dataTable" class="table table-sm table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Purpose</th>
                        <th class="text-right">
                            Amount
                        </th>
                        <th class="text-right">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($expenses as $expense)
                            @php
                                $total += $expense->amount;
                            @endphp

                            <tr class="{{$expense->deleted_at ? 'table-danger' : ''}}">
                                <td>{{$expense->id}}</td>
                                <td>{{date('d/m/Y', strtotime($expense->created_at))}}</td>
                                <td>{{$expense->note}}</td>
                                <td class="text-right">{{amount($expense->amount)}}</td>
                                <td class="text-right">
                                    @if($expense->deleted_at)
                                    Deleted
                                    @else
                                    <a href="{{route('back.accounts.otherExpensesEdit', $expense->id)}}" class="btn btn-sm btn-success"><i class="fas fa-edit"></i></a>
                                    <a href="{{route('back.accounts.otherExpensesDelete', $expense->id)}}" class="btn btn-sm btn-danger">Delete</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th class="text-right">Total</th>
                            <th class="text-right">{{amount($total)}}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>
    </div>

    <div class="col-md-4">
        <div class="card card-primary noPrint">
            <div class="card-header">
                <h6 class="mb-0">Create Expense</h6>
            </div>

            <form action="{{route('back.accounts.otherExpensesStore')}}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label><b>Date*</b></label>
                        <input type="date" name="date" class="form-control form-control-sm" value="{{old('date', date('Y-m-d'))}}" required>
                    </div>
                    <div class="form-group">
                        <label><b>Amount*</b></label>
                        <input type="number" step="any" name="amount" class="form-control form-control-sm" value="{{old('amount')}}" required>
                    </div>
                    <div class="form-group">
                        <label><b>Purpose*</b></label>
                        <input type="text" name="purpose" class="form-control form-control-sm" value="{{old('purpose')}}" required>
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
