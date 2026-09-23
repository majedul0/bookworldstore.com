@extends('back.layouts.master')
@section('title', 'Order Return & Refund')


@section('master')
@php
    $subtotal = 0;
    $total_qiantity = 0;
@endphp

<div class="card border-light mt-3 shadow noPrint">
    <div class="card-header">
        <h6 class="d-inline-block">Return Items</h6>

        <a href="{{route('back.orders.show', $order->id)}}" class="btn btn-info btn-sm float-right">Back to Order</a>
    </div>

    <form action="{{route('back.orders.returnRefund', $order->id)}}" method="POST" id="return_form">
        @csrf

        <div class="card-body table-responsive">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label><b>Return Shipping Amount</b></label>
                        <input type="number" step="any" name="refund_shipping_amount" class="form-control form-control-sm calculate_input return_shipping" value="0">
                    </div>
                </div>
                <div class="col-md-3 d-none">
                    <div class="form-group">
                        <label><b>Other Charge</b></label>
                        <input type="number" name="refund_other_charge" class="form-control form-control-sm calculate_input other_charge" value="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><b>Refund Payment Transaction</b></label>
                        <select name="refund_payment" class="form-control form-control-sm">
                            @foreach ($order->OrderPayments->where('status', 'Active') as $order_payment)
                                <option value="{{$order_payment->id}}">{{$order_payment->txn_number}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><b>Refund HST</b></label>
                        <input type="number" step="any" name="refund_tax" class="form-control form-control-sm tax_amount calculate_input" value="{{number_format($order->tax_amount, 2)}}">
                    </div>
                </div>
            </div>
            <br>


            <table class="table table-bordered table-sm">
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Image</th>
                    <th scope="col">Variation</th>
                    <th scope="col" style="width: 120px">Unit Price</th>
                    <th scope="col" class="text-center" style="width: 120px">Quantity</th>
                    <th scope="col" style="width: 120px">Return Qty.</th>
                    <th scope="col" style="width: 120px" class="text-right">Subtotal</th>
                </tr>
                </thead>

                <tbody class="listed_items">
                    @foreach ($order->OrderProducts as $order_product)
                        <input type="hidden" name="order_product[]" value="{{$order_product->id}}">
                        @php
                            $subtotal += $order_product->sale_price * $order_product->quantity;
                            $total_qiantity += $order_product->quantity;
                        @endphp
                        <tr>
                            <td>{{$order_product->Product->title}}</td>
                            <td><img src="{{$order_product->Product->img_paths['small']}}" style="width:35px"></td>
                            <td>{{$order_product->ProductData->attribute_items_string ?? 'N/A'}}</td>
                            <td>{{($settings_g['currency_symbol'] ?? '$') . $order_product->sale_price}}</td>
                            <td class="text-center">{{$order_product->quantity}}</td>
                            <td>
                                <input type="number" name="return_quantity[]" class="form-control form-control-sm item_return_quantity" data-maxval="{{$order_product->quantity}}" data-saleprice="{{$order_product->sale_price}}" value="{{$order_product->quantity}}">
                            </td>
                            <td class="text-right">{{$settings_g['currency_symbol'] ?? '$'}}<span class="item_total">{{$order_product->sale_price * $order_product->quantity}}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label><b>Total Refund Amount</b></label>
                        <input type="text" name="refund_amount" class="form-control form-control-sm subtotal_amount" value="{{number_format(($subtotal + $order->tax_amount), 2)}}" readonly>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                        <label><b>Note*</b></label>
                        <input type="text" name="note" class="form-control form-control-sm" required>
                    </div>
                </div>

                {{-- <p class="mb-0">Total Refund Amount: $<b class="subtotal_amount">0</b></p>

                <div class="form-group d-inline-block" style="width: 320px;max-width: 100%">
                    <input type="text" placeholder="Note" name="note" class="form-control form-control-sm">
                </div>
                <br> --}}
            </div>

            <input type="hidden" name="total_quantity" value="{{$total_qiantity}}">
            <input type="hidden" name="total_submitted_quantity" class="total_submitted_quantity" value="{{$total_qiantity}}">
            <button class="btn btn-info">Submit</button>
        </div>
    </form>
</div>
@endsection

@section('footer')
    <script>
        $(document).on('focusout keyup', '.item_return_quantity', function(){
            let item_return_quantity = $(this).val();
            let item_max_val = $(this).data('maxval');
            let item_saleprice = $(this).data('saleprice');
            let new_quantity = item_return_quantity;

            if(item_return_quantity < 0){
                $(this).val(0);
                new_quantity = 0;

                cAlert('error', 'Invalid quantity');
            }

            if(item_return_quantity > item_max_val){
                $(this).val(item_max_val);
                new_quantity = item_max_val;

                cAlert('error', 'Invalid quantity');
            }

            if(item_return_quantity == ''){
                $(this).val(0);
            }

            $(this).closest('tr').find('.item_total').html(item_saleprice * new_quantity);

            subtotalCalculation();
        });

        $(document).on('keyup focusout', '.calculate_input', function(){
            subtotalCalculation();
        });

        function subtotalCalculation(){
            let subtotal_amount = 0;
            let sunitted_total_quantity = 0;
            let all_item_amount = $('.item_total').map(function () {
                return $(this).html();
            });
            let all_item_quantity = $('.item_return_quantity').map(function () {
                return $(this).val();
            });

            let return_shipping = $('.return_shipping').val();
            if(return_shipping == ''){
                return_shipping = 0;
            }
            let other_charge = $('.other_charge').val();
            if(other_charge == ''){
                other_charge = 0;
            }
            let tax_amount = "{{$order->tax_amount ?? 0}}";

            $.each(all_item_amount, function (index, item) {
                subtotal_amount = Number(subtotal_amount) + Number(item);
            });

            $.each(all_item_quantity, function (index, item) {
                sunitted_total_quantity = Number(sunitted_total_quantity) + Number(item);
            });

            let calculated_sub_total = Number(subtotal_amount) + Number(return_shipping) + Number(other_charge) + Number(tax_amount);
            $('.subtotal_amount').val(calculated_sub_total.toFixed(2));
            $('.total_submitted_quantity').val(sunitted_total_quantity);
            // console.log(parseInt(subtotal_amount) + parseInt(return_shipping) + parseInt(other_charge));
        }

        $(document).on('submit', '#return_form', function(){
            let sunitted_total_quantity = 0;
            let all_item_quantity = $('.item_return_quantity').map(function () {
                return $(this).val();
            });

            $.each(all_item_quantity, function (index, item) {
                sunitted_total_quantity = Number(sunitted_total_quantity) + Number(item);
            });

            // alert(123);
            // return false;
            // let form_subtotal_amount = $('.subtotal_amount').val();

            if(sunitted_total_quantity < 1){
                cAlert('error', 'Please input some return quantity!');
                return false;
            }
            // alert(sunitted_total_quantity);
            return true;
        });
    </script>
@endsection
