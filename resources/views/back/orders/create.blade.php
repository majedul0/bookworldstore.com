@extends('back.layouts.master')
@section('title', 'Create Order')

@section('head')
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('master')
<form action="{{route('back.orders.store')}}" method="POST" enctype="multipart/form-data">
@csrf
<input type="hidden" name="failed_order" value="{{request('failed_order')}}">

<div class="card border-light mt-3 shadow">
    <div class="card-header">
        <h6 class="mb-0">Customer Information</h6>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label><b>Mobile Number*</b></label>
                    <div class="input-group">
                        <input class="form-control form-control-sm customer_mobile_number" value="{{old('mobile_number', request('mobile_number'))}}" type="number" name="mobile_number">
                     </div>
                </div>
                {{-- <div class="form-group">
                    <label><b>Mobile Number*</b></label>
                    <div class="input-group">
                        <input class="form-control form-control-sm customer_mobile" value="{{old('mobile_number')}}" type="number" name="mobile_number">

                        <div class="input-group-append">
                            <span class="input-group-btn"><button class="update_price btn btn-info btn-sm search_customer" type="button" title="Search Customer Data">
                            <i class="fas fa-search"></i></button></span>
                        </div>
                    </div>
                </div> --}}
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label><b>Name*</b></label>
                    <input type="text" name="name" value="{{old('name', request('name'))}}" class="form-control form-control-sm customer_name" required>
                    <input type="hidden" name="customer_id" value="{{old('customer_id')}}" class="customer_id">
                </div>
            </div>
            {{-- <div class="col-md-4">
                <div class="form-group">
                    <label><b>Email</b></label>
                    <input type="email" name="email" value="{{old('email')}}" class="form-control form-control-sm customer_email">
                </div>
            </div> --}}
            {{-- <div class="col-md-4">
                <div class="form-group">
                    <label><b>Password*</b></label>
                    <input type="text" name="password" value="123456789" class="form-control form-control-sm customer_password" readonly required>
                </div>
            </div> --}}
            <div class="col-md-4">
                <div class="form-group">
                    <label><b>Date*</b></label>
                    <input type="date" name="date" value="{{old('date', date('Y-m-d'))}}" class="form-control form-control-sm" required>
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-group">
                    <label><b>Address*</b></label>
                    <input type="text" name="address" value="{{old('address', request('address'))}}" class="form-control form-control-sm customer_address" required>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label><b>Order Source</b></label>
                    <select name="source" class="form-control form-control-sm">
                        <option value="">Select Order Source</option>

                        @foreach ($sources_arr as $source)
                        <option value="{{$source}}">{{$source}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label><b>Total Previous Orders</b><span class="view_all_orders" style="display: none">(<a href="" target="_blank">View All</a>)</span></label>
                    <input type="text" value="{{$total_orders}}" class="form-control form-control-sm total_orders" disabled>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label><b>Previous Complected Orders</b></label>
                    <input type="text" value="{{$complected_orders}}" class="form-control form-control-sm complected_orders" disabled>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-light mt-3 shadow">
            <div class="card-header">
                <h6 class="mb-0">Listed Products</h6>
            </div>

            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="d-block"><b>Select Product</b> <a target="_blank" href="{{route('back.products.create')}}" class="btn btn-success btn-sm float-right"><i class="fas fa-plus"></i></a></label>
                            <select class="form-control form-control-sm selectpicker_products"></select>
                        </div>
                    </div>
                </div>
                <br>

                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead>
                        <tr>
                            {{-- <th scope="col">SL</th> --}}
                            <th scope="col">Name</th>
                            <th scope="col">Image</th>
                            <th scope="col">Variation</th>
                            {{-- <th scope="col">Tax %</th>
                            <th scope="col">Discount %</th> --}}
                            <th scope="col" style="width: 120px">Unit Price</th>
                            <th scope="col" style="width: 120px">Quantity</th>
                            <th scope="col" style="width: 120px">Subtotal</th>
                            <th scope="col" class="text-right"><i class="fas fa-trash"></i></th>
                        </tr>
                        </thead>
                        <tbody class="listed_items">
                            @if($failed_order)
                            @foreach ($failed_order->failed_order_items as $failed_order_item)
                                @if($failed_order_item->product)
                                @include('back.orders.addItem', [
                                    'product' => $failed_order_item->product
                                ])
                                @endif
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><b>Order Status*</b></label>

                            <select name="status" class="form-control form-control-sm" required>
                                <option value="Pending">Pending</option>
                                <option value="Delivered">Delivered</option>
                                <option value="Completed">Completed</option>
                                <option value="In Courier">In Courier</option>
                            </select>
                        </div>
                    </div>
                    {{-- <div class="col-md-3">
                        <div class="form-group">
                            <label><b>Payment Status*</b></label>

                            <select name="payment_status" class="form-control form-control-sm" required>
                                <option value="Due">Due</option>
                                <option value="Paid">Paid</option>
                            </select>
                        </div>
                    </div> --}}

                    <div class="col-md-4">
                        <div class="form-group">
                            <label><b>Payment Method</b></label>

                            <select name="payment_method" class="form-control form-control-sm" required>
                                <option value="Cash on Delivery">Cash on Delivery</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label><b>Attachments</b></label>

                            <input type="file" name="attachment" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Order Note</b></label>
                            <textarea name="note" class="form-control form-control-sm" cols="30" rows="5"></textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Staff Note</b></label>
                            <textarea name="staff_note" class="form-control form-control-sm" cols="30" rows="5"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-light mt-3 shadow">
            <div class="card-header">
                <h6 class="mb-0">Summary</h6>
            </div>

            <div class="card-body">
                <div class="form-group">
                    <label><b>Reference No</b></label>
                    <input type="text" class="form-control form-control-sm" name="reference_no">
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <tr>
                            <th>Subtotal({{$settings_g['currency_symbol'] ?? 'Tk'}}):</th>
                            <td>
                                <span class="product_sub_total">0</span>
                                <input type="hidden" name="product_total" class="product_total_input" value="0">
                            </td>
                        </tr>
                        <tr>
                            <th>Discount/Less*:</th>
                            <td style="width: 150px">
                                <input type="number" step="any" class="form-control form-control-sm summary_input discount_input" name="discount" value="0" required>
                            </td>
                        </tr>
                        <tr>
                            <th>After Discount({{$settings_g['currency_symbol'] ?? 'Tk'}}):</th>
                            <td>
                                <span class="after_discount">0</span>
                                <input type="hidden" name="discount_amount" value="0" class="discount_amount">
                                <input type="hidden" name="tax_amount" class="tax_amount_input" value="0">
                            </td>
                        </tr>
                        {{-- <tr>
                            <th>Tax:</th>
                            <td>
                                {{$settings_g['currency_symbol'] ?? 'Tk'}}<span class="tax_amount">0</span>
                                <input type="hidden" name="tax_amount" class="tax_amount_input" value="0">
                            </td>
                        </tr> --}}
                        <tr>
                            <th>Shipping*({{$settings_g['currency_symbol'] ?? 'Tk'}}):</th>
                            <td style="width: 150px">
                                <input type="number" step="any" class="form-control form-control-sm summary_input shipping_input" name="shipping" value="{{$settings_g['shipping_charge'] ?? 0}}" required>
                            </td>
                        </tr>
                        <tr>
                            <th>Grand Total({{$settings_g['currency_symbol'] ?? 'Tk'}}):</th>
                            <td>
                                <span class="grand_total">{{$settings_g['shipping_charge'] ?? 0}}</span>
                                <input type="hidden" name="grand_total" class="grand_total_input" value="0">
                            </td>
                        </tr>
                        <tr>
                            <th>Advance Payment:</th>
                            <td style="width: 150px">
                                <input type="number" step="any" class="form-control form-control-sm summary_input paid_amount" name="paid_amount" value="0">
                            </td>
                        </tr>
                        <tr>
                            <th>Due({{$settings_g['currency_symbol'] ?? 'Tk'}}):</th>
                            <td>
                                <span class="due_amount">{{$settings_g['shipping_charge'] ?? 0}}</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card-footer">
                <button class="btn btn-success btn-block">Submit</button>
                <small><b>NB: *</b> marked are required field.</small>
            </div>
        </div>
    </div>
</div>
</form>
@endsection

@section('footer')
    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>

    {{-- @if($failed_order)
    <script>
        getCustomerData();
        grandCalculation();
    </script>
    @endif --}}

    <script>
        var timeoutId;
        $(document).on('focusout', '.customer_mobile_number', function(){
            getCustomerData();
        });
        $(document).on('keyup', '.customer_mobile_number', function(){
            clearTimeout(timeoutId);

            timeoutId = setTimeout(function() {
                getCustomerData();
            }, 800);
        });

        function getCustomerData(){
            let mobile_number = $('.customer_mobile_number').val();

            $.ajax({
                url: '{{route("back.orders.getCustomerData")}}',
                method: 'POST',
                dataType: 'JSON',
                data: {mobile_number, _token: "{{csrf_token()}}"},
                success: function(result){
                    if(result.status){
                        $('.customer_address').val(result.user.street);
                        $('.customer_name').val(result.user.last_name);
                        $('.total_orders').val(result.total_orders);
                        $('.view_all_orders a').attr('href', result.all_link);
                        $('.view_all_orders').show();
                    }else{
                        $('.customer_address').val('');
                        $('.customer_name').val('');
                        $('.total_orders').val(0);
                        $('.complected_orders').val(0);
                        $('.view_all_orders').hide();
                    }
                },
                error: function(){}
            });
        }

        let stock_out_can_order = '{{$settings_g['stock_out_can_order'] ?? 'No'}}';
        // Select2
        $('.selectpicker_products').select2({
            placeholder: "Search Product",
            minimumInputLength: 1,
            ajax: {
                url: '{{ route("back.products.selectList") }}?stock_group=Finished Goods',
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
            let product_id = $(this).val();
            if(product_id){
                cLoader();

                $.ajax({
                    url: '{{route("back.orders.addItem")}}',
                    method: 'POST',
                    data: {product_id, _token: '{{csrf_token()}}'},
                    success: function(result){
                        cLoader('h');

                        if(result == 'false'){
                            cAlert('error', 'Out of stock!');
                        }else{
                            $('.listed_items').append(result);
                            grandCalculation();
                        }
                        $('.selectpicker_products').val([]).trigger('change');
                    },
                    error: function(){
                        cLoader('h');
                        $('.selectpicker_products').val([]).trigger('change');
                    }
                });
            }
        });

        // Remove Item
        $(document).on('click', '.remove_item', function(){
            if(confirm('Are you sure to remobe?')){
                $(this).closest('tr').remove();

                grandCalculation();
            }
        });

        // Edit input
        $(document).on('change keyup input', '.quantity_input', function(){
            let maximum_quantity = $(this).closest('tr').find('.maximum_quantity').val();
            let quantity = $(this).closest('tr').find('.quantity_input').val();
            if(stock_out_can_order == 'No' && (Number(quantity) > Number(maximum_quantity))){
                quantity = maximum_quantity;
                $(this).closest('tr').find('.quantity_input').val(maximum_quantity);

                cAlert('error', ('Maximum quantity ' + maximum_quantity));
            }

            let price = $(this).closest('tr').find('.price_input').val();
            if(price == ''){
                price = 0;
            }
            if(quantity == ''){
                quantity = 0;
            }

            $(this).closest('tr').find('.sub_total').val(parseInt(price) * parseInt(quantity));

            grandCalculation();
        });

        $(document).on('change keyup', '.input_calc', function(){
            let price = $(this).closest('tr').find('.price_input').val();
            if(price == ''){
                price = 0;
            }
            let quantity = $(this).closest('tr').find('.quantity_input').val();
            if(quantity == ''){
                quantity = 0;
            }

            $(this).closest('tr').find('.sub_total').val(parseInt(price) * parseInt(quantity));

            grandCalculation();
        });

        $(document).on('change keyup', '.summary_input', function(){
            grandCalculation();
        });

        // Variation select
        $(document).on('change', '.variation_select', function(){
            let product_data_id = $(this).val();
            // $(this).closest('tr').find('.quantity_input').val(1);
            // let product_quantity = $(this).closest('tr').find('.quantity_input').val();
            // let product_quantity = 1;

            $.ajax({
                url: '{{route("back.products.productDataJson")}}',
                method: 'POST',
                context: this,
                dataType: 'json',
                data: {
                    _token: '{{csrf_token()}}',
                    product_data_id
                },
                success: function(result){
                    if(result.code == 200){
                        $(this).closest('tr').find('.price_input').val(result.data.sale_price);
                        $(this).closest('tr').find('.sub_total').val(result.data.sale_price * 1);
                        $(this).closest('tr').find('.quantity_input').val(1);
                        $(this).closest('tr').find('.quantity_input').removeAttr('readonly', '');
                        $(this).closest('tr').find('.maximum_quantity').val(result.data.stock);
                        if(result.data.color_code){
                            $(this).closest('tr').find('.variation_color_code').css('background', result.data.color_code);
                            $(this).closest('tr').find('.variation_color_code').css('display', 'inline-block');
                        }else{
                            $(this).closest('tr').find('.variation_color_code').hide();
                        }
                    }else{
                        cAlert('error', 'Out of Stock!');
                        $(this).closest('tr').find('.sub_total').val(result.data.sale_price * 0);
                        $(this).closest('tr').find('.quantity_input').val(0);
                        $(this).closest('tr').find('.quantity_input').attr('readonly', '');
                        $(this).closest('tr').find('.maximum_quantity').val(0);
                    }

                    grandCalculation();
                },
                error: function(){
                    console.log('Something wrong!');
                    grandCalculation();
                }
            });
        });

        function grandCalculation(){
            let after_discount = 0;

            let sub_totals = $('.sub_total').map(function () {
                if($(this).val() == ''){
                    return 0;
                }else{
                    return $(this).val();
                }
            });

            var product_total = sub_totals.get().reduce(function(a, b){
                return parseInt(a) + parseInt(b);
            }, 0);
            $('.product_sub_total').html(product_total);
            $('.product_total_input').val(product_total);

            // // Tax Calculation
            // let tax_totals = $('.tax_amount_input_hidden').map(function () {
            //     if($(this).val() == ''){
            //         return 0;
            //     }else{
            //         return $(this).val() * ($(this).closest('tr').find('.quantity_input').val() ?? 1);
            //     }
            // });
            // var tax_amount = tax_totals.get().reduce(function(a, b){
            //     return parseInt(a) + parseInt(b);
            // }, 0);
            // $('.tax_amount').html(tax_amount);
            // $('.tax_amount_input').val(tax_amount);

            let discount = $('.discount_input').val();
            if(discount == ''){
                discount = 0;
            }

            let shipping = $('.shipping_input').val();
            if(shipping == ''){
                shipping = 0;
            }

            if(discount > 0){
                after_discount = product_total - discount;
                // $('.discount_amount').val((product_total * discount) / 100);
                // after_discount = product_total - ((product_total * discount) / 100);
                // $('.discount_amount').val((product_total * discount) / 100);
            }else{
                // $('.discount_amount').val(0);
                after_discount = product_total;
            }
            $('.after_discount').html(after_discount);

            // let tax = '{{$settings_g["tax"] ?? 0}}';
            // let tax_type = '{{$settings_g["tax_type"] ?? ''}}';
            // let tax_amount = 0;
            // if(tax){
            //     if(tax_type == 'Fixed'){
            //         $('.tax_amount').html(tax);
            //         $('.tax_amount_input').val(tax);
            //         tax_amount = tax;
            //     }else{
            //         $('.tax_amount').html((after_discount * tax) / 100);
            //         $('.tax_amount_input').val((after_discount * tax) / 100);
            //         tax_amount = (after_discount * tax) / 100;
            //     }
            // }
            let paid_amount = $('.paid_amount').val();
            let grand_total_amount = Number(after_discount) + Number(shipping);

            // console.log(grand_total);
            $('.grand_total').html(grand_total_amount);
            $('.grand_total_input').html(grand_total_amount);
            $('.due_amount').html(grand_total_amount - paid_amount);
        }

        $(document).on('click', '.search_customer', function(){
            let mobile_number = $('.customer_mobile').val();
            if(mobile_number){
                cLoader();
                $.ajax({
                    url: "{{route('back.orders.customerDetails')}}",
                    method: "POST",
                    dataType: 'JSON',
                    data: {mobile_number, _token: "{{csrf_token()}}"},
                    success: function(result){
                        cLoader('hide');

                        if(result.status){
                            $('.customer_name').val(result.customer.last_name);
                            $('.customer_address').val(result.customer.street);
                            $('.customer_email').val(result.customer.custom_email);
                            $('.customer_id').val(result.customer.id);

                            // $('.customer_password').closest('.form-group').hide();
                            // $('.customer_password').attr('readonly', 'readonly');
                            // $('.customer_password').removeAttr('required', 'required');
                        }else{
                            // $('.customer_password').closest('.form-group').show();
                            // $('.customer_password').removeAttr('readonly', 'readonly');
                            // $('.customer_password').attr('required', 'required');

                            $('.customer_name').val('');
                            $('.customer_address').val('');
                            $('.customer_email').val('');
                            $('.customer_id').val('');
                        }
                        $('.customer_name').removeAttr('readonly', true);
                        $('.customer_address').removeAttr('readonly', 'readonly');
                    },
                    error: function(){
                        cLoader('hide');
                        cAlert('error', 'Something wrong!');
                    },
                });
            }else{
                cAlert('error', 'Please insert mobile number!');
            }
        });
    </script>
@endsection
