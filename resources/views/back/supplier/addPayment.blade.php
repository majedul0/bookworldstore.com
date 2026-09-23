@extends('back.layouts.master')
@section('title', 'Add Supplier Payments')

@section('master')
<div class="card border-light mt-3 shadow">
    <div class="card-header">
        <h5 class="d-inline-block">Supplier Payments</h5>

        <a href="{{route('back.suppliers.payments')}}" class="btn btn-success btn-sm float-right">Payments</a>
    </div>
    <form action="{{route('back.suppliers.addPayment')}}" method="POST">
        @csrf
        <div class="card-body">
            {{-- <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Voucher No</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="voucher_no" value="{{time()}}" readonly>
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Date</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" name="date" value="{{old('date') ?? date('Y-m-d')}}" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Remark</label>
                        <div class="col-sm-8">
                            <textarea name="remark" class="form-control" cols="30" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <br>

            <table class="table table-bordered table-sm table-hover">
                <thead>
                    <tr>
                        <th class="text-center">Supplier Name <i class="text-danger">*</i></th>
                        <th class="text-center">Code</th>
                        <th class="text-center">Balance</th>
                        <th class="text-center">Amount <i class="text-danger">*</i></th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>

                <tbody class="listed_suppliers">
                    <tr>
                        <th class="text-center">
                            <select name="supplier[]" class="form-control select_supplier" required>
                                <option value="">Select Supplier</option>

                                @foreach ($suppliers as $supplier)
                                    <option value="{{$supplier->id}}">{{$supplier->full_name}}</option>
                                @endforeach
                            </select>
                        </th>
                        <th class="text-center">
                            <input type="number" class="form-control supplier_id_input" value="0" readonly>
                        </th>
                        <th class="text-center">
                            <input type="number" class="form-control supplier_ols_due_input" value="0" readonly>
                        </th>
                        <th class="text-center">
                            <input type="number" name="amount[]" class="form-control row_amount" value="0" required>
                        </th>
                        <th class="text-right">
                            <button class="btn btn-danger btn-sm remove_supplier" type="button"><i class="fa fa-trash"></i></button>
                        </th>
                    </tr>
                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="2"><button class="btn btn-info btn-sm add_more_btn" type="button">Add More</button></th>
                        <th class="text-right">Total</th>
                        <th><input type="number" class="form-control total_amount" value="0" name="total_amount" readonly></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="card-footer">
            <button class="btn btn-success">Save</button>
            <br>
            <small><b>NB: *</b> marked are required field.</small>
        </div>
    </form>
</div>
@endsection

@section('footer')
    <script>
        $(document).on('change', '.select_supplier', function(){
            let supplier_id = $(this).val();

            $(this).closest('tr').find('.supplier_id_input').val(supplier_id);

            generateTotal();

            $.ajax({
                url: '{{route("back.suppliers.getInfo")}}',
                method: 'POST',
                dataType: 'JSON',
                context: this,
                data: {supplier: supplier_id, _token: "{{csrf_token()}}"},
                success: function(result){
                    $(this).closest('tr').find('.supplier_ols_due_input').val(result ? result.balance : 0);
                },
                error: function(){}
            });
        });
        $(document).on('change keyup', '.row_amount', function(){
            generateTotal();
        });

        $(document).on('click', '.add_more_btn', function(){
            let html = '<tr>'+
                        '<th class="text-center">'+
                            '<select name="supplier[]" class="form-control select_supplier" required>'+
                                '<option value="">Select Supplier</option>'+

                                '@foreach ($suppliers as $supplier)'+
                                    '<option value="{{$supplier->id}}">{{$supplier->full_name}}</option>'+
                                '@endforeach'+
                            '</select>'+
                        '</th>'+
                        '<th class="text-center">'+
                            '<input type="number" class="form-control supplier_id_input" value="0" readonly>'+
                        '</th>'+
                        '<th class="text-center">'+
                            '<input type="number" class="form-control supplier_ols_due_input" value="0" readonly>'+
                        '</th>'+
                        '<th class="text-center">'+
                            '<input type="number" name="amount[]" class="form-control row_amount" required value="0">'+
                        '</th>'+
                        '<th class="text-right">'+
                            '<button class="btn btn-danger btn-sm remove_supplier" type="button"><i class="fa fa-trash"></i></button>'+
                        '</th>'+
                    '</tr>';

            $('.listed_suppliers').append(html);

            generateTotal();
        });

        $(document).on('click', '.remove_supplier', function(){
            if(confirm('Are you sure to remove?')){
                $(this).closest('tr').remove();
            }
            generateTotal();
        });

        // Total Calculation
        function generateTotal(){
            let amounts = $('.row_amount').map(function () {
                            if($(this).val() == ''){
                                return 0;
                            }else{
                                return $(this).val();
                            }
                        });

            var total_amount = amounts.get().reduce(function(a, b){
                return Number(a) + Number(b);
            }, 0);

            $('.total_amount').val(total_amount);
        }
    </script>
@endsection
