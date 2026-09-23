@extends('back.layouts.master')
@section('title', 'Add new Purchase')

@section('head')
<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('master')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-light mt-3 shadow">
            <div class="card-header">
                <h6 class="d-inline-block">Search Product</h6>

                {{-- <select name="category" class="form-control form-control-sm d-inline-block float-right select_category" style="width: 140px;">
                    <option value="All">Select category</option>

                    @foreach ($categories as $category)
                        <option value="{{$category->id}}">{{$category->title}}</option>
                    @endforeach
                </select> --}}
            </div>

            <div class="card-body">
                <div class="form-group">
                    <select class="form-control selectpicker_products"></select>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{route('back.adjustments.store')}}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="card border-light mt-3 shadow">
        <div class="card-header">
            <h6 class="d-inline-block">Listed Product</h6>
        </div>

        <div class="card-body table-responsive">

            <table class="table table-bordered table-sm">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Image</th>
                    <th scope="col">Variation</th>
                    {{-- <th scope="col">Type</th> --}}
                    <th scope="col" style="width: 120px">Unit Cost</th>
                    <th scope="col" style="width: 120px">Quantity</th>
                    <th scope="col" style="width: 120px">Subtotal</th>
                    <th scope="col" class="text-right"><i class="fas fa-trash"></i></th>
                </tr>
                </thead>
                <tbody class="listed_items">
                    @if($product)
                        @include('back.product.adjustment.addItem', [
                            'product' => $product
                        ])
                    @endif
                </tbody>
            </table>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Grand Total*</label>
                        <input type="number" name="grand_total" value="{{$product->ProductData->cost ?? '0'}}" class="form-control form-control-sm grand_total" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Date*</label>
                        <input type="date" name="date" value="{{date('Y-m-d')}}" class="form-control form-control-sm" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Invoice No</label>
                        <input type="text" name="invoice_no" value="{{old('invoice_no')}}" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Supplier*</label>
                        <select name="supplier" class="form-control" required>
                            <option value="">Select Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{$supplier->id}}">{{$supplier->full_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Paid Amount*</label>
                        <input type="number" oninput="grandCalculation()" step="any" name="paid_amount" class="form-control form-control-sm paid_amount" value="0" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Due Amount</label>
                        <input name="due_amount" class="form-control form-control-sm due_amount" disabled>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>Note</label>
                        <input type="text" name="note" class="form-control form-control-sm">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button class="btn btn-success">Submit</button>
            <br>
            <small><b>NB: *</b> marked are required field.</small>
        </div>
    </div>
</form>
@endsection

@section('footer')
<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>

<script>
    // Select2
    $('.selectpicker_products').select2({
        placeholder: "Search Product",
        minimumInputLength: 1,
        ajax: {
            url: '{{ route("back.products.selectList") }}?for=purchase',
            dataType: 'json',
            data: function (params) {
                return {
                    q: $.trim(params.term)
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

    $(document).on('change', '.selectpicker_products',  function(){
        let id = $(this).val();
        cLoader();

        $.ajax({
            url: '{{route("back.adjustments.addItem")}}',
            method: 'POST',
            data: {id, _token: '{{csrf_token()}}'},
            success: function(result){
                cLoader('h');

                $('.listed_items').append(result);

                grandCalculation();
            },
            error: function(){
                cLoader('h');
            }
        });
    });

    // Add Item
    $(document).on('click', '.add_item', function(){
        let id = $(this).data('id');
        cLoader();

        $.ajax({
            url: '{{route("back.adjustments.addItem")}}',
            method: 'POST',
            data: {id, _token: '{{csrf_token()}}'},
            success: function(result){
                cLoader('h');

                $('.listed_items').append(result);

                grandCalculation();
            },
            error: function(){
                cLoader('h');
            }
        });
    });

    // Change Variation
    $(document).on('change', '.change_variation', function(){
        let variation_id = $(this).val();
        cLoader();

        $.ajax({
            url: '{{route("back.adjustments.getCost")}}',
            method: 'POST',
            data: {variation_id, _token: '{{csrf_token()}}'},
            context: this,
            success: function(cost){
                cLoader('h');
                $(this).closest('tr').find('.price_input').val(cost);
                // setTimeout(function() {
                // }, 1000);
                singleCalculation(this);
            },
            error: function(){
                cLoader('h');
            }
        });
    });

    // Edit input
    $(document).on('change keyup', '.input_calc', function(){
        singleCalculation(this);
    });

    function singleCalculation(that){
        let input_type = $(that).data('type');
        let quantity = $(that).closest('tr').find('.quantity_input').val();
        if(quantity == ''){
            quantity = 0;
        }

        if(input_type == 'subtotal'){
            let sub_total = $(that).closest('tr').find('.sub_total').val();
            if(sub_total == ''){
                sub_total = 0;
            }
            if(sub_total > 0 && quantity > 0){
                $(that).closest('tr').find('.price_input').val(sub_total / quantity);
            }else{
                $(that).closest('tr').find('.price_input').val(0);
            }
        }else{
            let single_total = $(that).closest('tr').find('.price_input').val();
            if(single_total == ''){
                single_total = 0;
            }
            if(single_total > 0 && quantity > 0){
                $(that).closest('tr').find('.sub_total').val(Number(single_total) * Number(quantity));
            }else{
                $(that).closest('tr').find('.sub_total').val(0);
            }
        }
        // let type = $(that).closest('tr').find('.type_input').val();

        // $(that).closest('tr').find('.sub_total').val(parseInt(price) * parseInt(quantity));

        grandCalculation();
    }

    // Remove Item
    $(document).on('click', '.remove_item', function(){
        if(confirm('Are you sure to remove?')){
            $(this).closest('tr').remove();

            grandCalculation();
        }
    });
    function grandCalculation(){
        let sub_totals = $('.sub_total').map(function () {
            if($(this).val() == ''){
                return 0;
            }else{
                return $(this).val();
            }
        });
        let paid_amount = $('.paid_amount').val();

        var grand_total = sub_totals.get().reduce(function(a, b){
            return parseInt(a) + parseInt(b);
        }, 0);


        $('.grand_total').val(grand_total);

        $('.due_amount').val(grand_total - paid_amount);
    }
</script>
@endsection
