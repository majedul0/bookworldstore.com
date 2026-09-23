@extends('back.layouts.master')
@section('title', 'Edit Adjustment')

@section('head')
<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('master')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-light mt-3 shadow">
            <div class="card-header">
                <h6 class="d-inline-block">Seatch Product</h6>
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
                    <th scope="col">Type</th>
                    <th scope="col" style="width: 120px">Unit Cost</th>
                    <th scope="col" style="width: 120px">Quantity</th>
                    <th scope="col" style="width: 120px">Subtotal</th>
                    <th scope="col" class="text-right"><i class="fas fa-trash"></i></th>
                </tr>
                </thead>
                <tbody class="listed_items">
                    @foreach($adjustment->Stocks as $stock)
                        @include('back.product.adjustment.addItem', [
                            'product' => $stock->Product
                        ])
                    @endforeach
                </tbody>
            </table>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Grand Total*</label>
                        <input type="number" name="grand_total" value="{{$adjustment->total_amount}}" class="form-control form-control-sm grand_total" readonly>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="form-group">
                        <label>Note*</label>
                        <input type="text" name="note" class="form-control form-control-sm" value="{{old('note') ?? $adjustment->note}}" required>
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
            url: '{{ route("back.products.selectList") }}',
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

    // Change Varioation
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
            },
            error: function(){
                cLoader('h');
            }
        });
    });

    // Edit input
    $(document).on('change keyup', '.input_calc', function(){
        let price = $(this).closest('tr').find('.price_input').val();
        if(price == ''){
            price = 0;
        }
        let quantity = $(this).closest('tr').find('.quantity_input').val();
        if(quantity == ''){
            quantity = 0;
        }
        let type = $(this).closest('tr').find('.type_input').val();

        if(type == 'Addition'){
            $(this).closest('tr').find('.sub_total').val(parseInt(price) * parseInt(quantity));
        }else{
            $(this).closest('tr').find('.sub_total').val(0);
        }

        grandCalculation();
    });

    // Remove Item
    $(document).on('click', '.remove_item', function(){
        if(confirm('Are you sure to remobe?')){
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

        var grand_total = sub_totals.get().reduce(function(a, b){
            return parseInt(a) + parseInt(b);
        }, 0);

        $('.grand_total').val(grand_total);
    }
</script>
@endsection
