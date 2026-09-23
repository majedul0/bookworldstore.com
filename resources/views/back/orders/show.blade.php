@extends('back.layouts.master')
@section('title', "Invoice: #$order->id-$order->status")

@section('head')
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">

@include('back.orders.color')

<style>
    .select2-container {
        width: 100% !important;
    }
</style>
@endsection

@section('master')
<form action="{{route('back.orders.update', $order->id)}}" method="POST" enctype="multipart/form-data">
@csrf
@method('PATCH')

<div class="card border-light mt-3 shadow noPrint">
    <div class="card-header">
        <h6>Shipping Address</h6>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label><b>Full Name*</b></label>
                    <input type="text" name="name" class="form-control form-control-sm" value="{{$order->shipping_full_name}}" required>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label><b>Mobile Number*</b> <a target="_blank" href="https://wa.me/88{{$order->shipping_mobile_number}}" style="font-size: 24px;"><i class="fab fa-whatsapp"></i></a></label>
                    <input type="text" name="mobile_number" class="form-control form-control-sm" value="{{$order->shipping_mobile_number}}" required>
                </div>
            </div>

            <div class="col-md-8">
                <div class="form-group">
                    <label><b>Address*</b></label>
                    <input type="text" name="address" class="form-control form-control-sm" name="address" value="{{old('address') ?? $order->shipping_street}}" required>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label><b>Order Source</b></label>
                    <select name="source" class="form-control form-control-sm">
                        <option value="">Select Order Source</option>

                        @foreach ($sources_arr as $source)
                        <option value="{{$source}}" {{$source == $order->source ? 'selected' : ''}}>{{$source}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label><b>Total Order of this Customer</b><span class="view_all_orders">(<a href="{{route('back.orders.index')}}?customer_id={{$order->user_id}}" target="_blank">View All</a>)</span></label>
                    <input type="text" value="{{$total_orders}}" class="form-control form-control-sm total_orders" disabled>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label><b>Total Complected Orders</b></label>
                    <input type="text" value="{{$completed_orders}}" class="form-control form-control-sm complected_orders" disabled>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row noPrint">
    <div class="col-md-8">
        <div class="card border-light mt-3 shadow">
            <div class="card-header">
                <h6 class="mb-0 d-inline-block">Order Products - Date: {{ date('d/m/Y h:ia', strtotime($order->created_at)) }}</h6>
            </div>

            <div class="card-body">
                @if(!($order->status == 'Canceled' || $order->status == 'Returned' || $order->status == 'Completed' || $order->status == 'Delivered'))
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="d-block"><b>Select Product</b> <a target="_blank" href="{{route('back.products.create')}}" class="btn btn-success btn-sm float-right"><i class="fas fa-plus"></i></a></label>
                            <select class="form-control form-control-sm selectpicker_products"></select>
                        </div>
                    </div>
                </div>
                <br>
                @endif

                <div class="table-responsive">
                    @if($order->status != 'Returned')
                    <table class="table table-bordered table-sm">
                        <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col" class="text-center">Image</th>
                            <th scope="col" class="text-center">Variant</th>
                            <th scope="col" class="text-center" style="width: 120px">Unit Price</th>
                            <th scope="col" class="text-center" style="width: 120px">Quantity</th>
                            <th scope="col" style="width: 120px" class="text-right">Subtotal</th>
                            <th scope="col" style="width: 120px" class="text-right">Action</th>
                        </tr>
                        </thead>
                        <tbody class="listed_items">
                            @foreach ($order_products as $order_product)
                                @if($order_product->quantity > 0)
                                <tr>
                                    <td>{{$order_product->Product->title}}</td>
                                    <td class="text-center"><img src="{{($order_product->ProductData->image ?? null) ? $order_product->ProductData->img_paths['small'] : $order_product->Product->img_paths['small']}}" style="width:35px"></td>
                                    <td class="text-center">
                                        @php
                                            $variation_attribute = $order_product->ProductData->attribute_items_string ?? '';
                                        @endphp
                                        {{$variation_attribute}}
                                        @if($variation_attribute)
                                        <br>
                                        @endif
                                        {{$order_product->simple_attributes_arr['string'] ?? ''}}

                                        @if($order_product->ProductData->color_code)
                                        <span style="background: {{$order_product->ProductData->color_code}};width: 25px;height: 25px;display: inline-block;border-radius: 50%;"></span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{amount($order_product->sale_price)}}</td>
                                    <td class="text-center">{{$order_product->quantity}}</td>
                                    <td class="text-right">{{amount($order_product->quantity * $order_product->sale_price)}}</td>
                                    <td class="text-right">
                                        @if(!($order->status == 'Canceled' || $order->status == 'Returned' || $order->status == 'Completed' || $order->status == 'Delivered'))
                                        <button class="btn btn-sm btn-info mb-1 addQuantity" type="button" data-id="{{$order_product->id}}" data-title="{{$order_product->Product->title}}" data-current="{{$order_product->quantity}}" data-toggle="modal" data-target="#moreQuantityModal">Add Qty</button>

                                        <button class="btn btn-sm btn-danger mb-1 returnQuantity" type="button" data-id="{{$order_product->id}}" data-title="{{$order_product->Product->title}}" data-current="{{$order_product->quantity}}" data-toggle="modal" data-target="#returnQuantityModal">Return Qty</button>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr>
                                <th class="text-right" colspan="5">Total</th>
                                <th class="text-right">{{amount($order->product_total)}}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                    @endif

                    @if(count($op_returns))
                    <h5>Returned Items</h5>
                    <table class="table table-bordered table-sm table-danger">
                        <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col" class="text-center">Image</th>
                            <th scope="col" class="text-center">Variation</th>
                            <th scope="col" class="text-center" style="width: 120px">Unit Price</th>
                            <th scope="col" class="text-center" style="width: 120px">Quantity</th>
                            <th scope="col" style="width: 120px" class="text-right">Subtotal</th>
                        </tr>
                        </thead>
                        <tbody>
                            @php
                                $refund_product_total = 0;
                            @endphp
                            @foreach ($op_returns as $op_return)
                                <tr>
                                    <td>{{$op_return->Product->title}}</td>
                                    <td class="text-center"><img src="{{$op_return->Product->img_paths['small']}}" style="width:35px"></td>
                                    <td class="text-center">
                                        @php
                                            $variation_attribute = $op_return->ProductData->attribute_items_string ?? '';
                                        @endphp
                                        {{$variation_attribute}}
                                        @if($variation_attribute)
                                        <br>
                                        @endif
                                        {{$op_return->simple_attributes_arr['string'] ?? ''}}
                                    </td>
                                    <td class="text-center">{{amount($op_return->sale_price)}}</td>
                                    <td class="text-center">{{$op_return->return_quantity}}</td>
                                    <td class="text-right">
                                        {{amount($op_return->return_quantity * $op_return->sale_price)}}

                                        @php
                                            $refund_product_total += $op_return->return_quantity * $op_return->sale_price;
                                        @endphp
                                    </td>
                                </tr>
                            @endforeach

                            <tr>
                                <th class="text-right" colspan="5">Total</th>
                                <th class="text-right">{{amount($refund_product_total)}}</th>
                            </tr>
                        </tbody>
                    </table>
                    @endif
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><b>Order Status*</b></label>

                            <select name="status" class="form-control form-control-sm order_status" {{($order->status == 'Canceled' || $order->status == 'Returned' || $order->status == 'Completed') ? 'disabled' : 'required'}}>
                                @if($order->status == 'Pending Return')
                                    <option value="Pending Return" {{$order->status == 'Pending Return' ? 'selected' : ''}}>Pending Return</option>
                                    <option value="Returned">Returned</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Delivered">Delivered</option>
                                    <option value="Hold">Hold</option>
                                @else
                                    @if($order->status != 'Delivered' || $order->status == 'Completed')
                                        <option value="Pending" {{$order->status == 'Pending' ? 'selected' : ''}}>Pending</option>
                                        <option value="In Courier" {{$order->status == 'In Courier' ? 'selected' : ''}}>In Courier</option>
                                    @endif

                                    @if($order->status == 'In Courier' || $order->status == 'Delivered' || $order->status == 'Completed' || $order->status == 'Returned')
                                        <option value="Delivered" {{$order->status == 'Delivered' ? 'selected' : ''}}>Delivered</option>

                                        @if($order->status == 'In Courier' || $order->status == 'Delivered' || $order->status == 'Completed')
                                            <option value="Completed" {{$order->status == 'Completed' ? 'selected' : ''}}>Completed</option>
                                        @endif

                                        @if($order->status != 'Delivered' || $order->status == 'Completed')
                                            <option value="Pending Return" {{$order->status == 'Pending Return' ? 'selected' : ''}}>Pending Return</option>
                                            <option value="Returned" {{$order->status == 'Returned' ? 'selected' : ''}}>Returned</option>
                                        @endif
                                    @endif

                                    @if($order->status != 'Delivered' || $order->status == 'Completed')
                                        <option value="Hold" {{$order->status == 'Hold' ? 'selected' : ''}}>Hold</option>
                                        <option value="Confirmed" {{$order->status == 'Confirmed' ? 'selected' : ''}}> Confirmed</option>
                                        <option value="Canceled" {{$order->status == 'Canceled' ? 'selected' : ''}}>Canceled</option>
                                    @endif
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label><b>Payment Method</b></label>

                            <select name="payment_method" class="form-control form-control-sm" {{$order->payment_status == 'Paid' ? 'disabled' : 'required'}}>
                                <option value="Cash on Delivery">Cash on Delivery</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="d-block"><b>Attachments</b>
                                @if($order->attachment)
                                    <a href="{{$order->attachment_path}}" class="btn btn-success btn-sm float-right" download=""><i class="fas fa-download"></i></a>
                                @endif
                            </label>

                            <input type="file" name="attachment">
                        </div>
                    </div>

                    @php
                        $custom_cancel_reason = ($order->cancel_return_reason == 'Other' || $order->cancel_return_reason == 'Over Price' || $order->cancel_return_reason == 'Color/Size' || $order->cancel_return_reason == null) ? false : true;
                    @endphp
                    <div class="col-md-12 cancel_return_reason_wrap" style="{{($order->status == 'Canceled' || $order->status == 'Returned') ? 'display:block' : 'display:none'}}">
                        <div class="form-group">
                            <label><b>Cancel/Return Reason</b></label>
                            <select name="cancel_return_reason" class="form-control form-control-sm cancel_return_reason">
                                <option value="Other" {{$order->cancel_return_reason == 'Other' ? 'selected' : ''}}>Other</option>
                                <option value="Over Price" {{$order->cancel_return_reason == 'Over Price' ? 'selected' : ''}}>Over Price</option>
                                <option value="Color/Size" {{$order->cancel_return_reason == 'Color/Size' ? 'selected' : ''}}>Color/Size</option>
                                <option value="Custom" {{$custom_cancel_reason ? 'selected' : ''}}>Custom</option>
                            </select>
                            {{-- <input type="text" name="cancel_return_reason" class="form-control form-control-sm" value="{{old('cancel_return_reason', $order->cancel_return_reason)}}"> --}}
                        </div>

                        <div class="form-group custom_cancel_return_reason_wrap" style="display: {{$custom_cancel_reason ? 'block' : 'none'}}">
                            <label><b>Custom Custom/Return Reason</b></label>
                            <input type="text" name="custom_cancel_return_reason" class="form-control form-control-sm" value="{{old('custom_cancel_return_reason', $order->cancel_return_reason)}}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Order Note</b></label>
                            <textarea name="note" class="form-control form-control-sm" cols="30" rows="5">{{$order->note}}</textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Staff Note</b></label>
                            <textarea name="staff_note" class="form-control form-control-sm" cols="30" rows="5">{{$order->staff_note}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(count($order->OrderPayments))
        <div class="card border-light mt-3 shadow">
            <div class="card-header">
                <h6 class="mb-0">Order Payments</h6>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead>
                        <tr>
                            <th scope="col">SL</th>
                            <th scope="col">Date</th>
                            <th scope="col">TXN Number</th>
                            {{-- <th scope="col">Status</th> --}}
                            <th scope="col" class="text-right">Note</th>
                        </tr>
                        </thead>
                        <tbody class="listed_items">
                            @foreach ($order->OrderPayments as $key => $order_payment)
                                <tr>
                                    <td>{{$key + 1}}</td>
                                    <td>{{date('d/m/Y h:ia', strtotime($order_payment->created_at))}}</td>
                                    <td>{{$order_payment->txn_number}}</td>
                                    {{-- <td>{{$order_payment->status}}</td> --}}
                                    <td class="text-right">{{$order_payment->note}}</td>
                                    {{-- <td class="text-right">
                                        @if($order_payment->status == 'Active')
                                        <a href="{{route('back.orders.refund', $order_payment->id)}}" onclick="return confirm('Are you sure to refund?');" class="btn btn-danger btn-sm">Refund</a>
                                        @else
                                        N/A
                                        @endif
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card border-light mt-3 shadow">
            <div class="card-header">
                <h6 class="mb-0">Summary</h6>
            </div>

            <div class="card-body table-responsive">
                <div class="form-group">
                    <label><b>Reference No</b></label>
                    <input type="text" class="form-control form-control-sm" value="{{old('reference_no', $order->reference_no)}}" name="reference_no">
                </div>

                <table class="table table-bordered table-sm">
                    <tr>
                        <th>Subtotal:</th>
                        <td>
                            <span class="product_sub_total">{{amount($order->product_total)}}</span>
                        </td>
                    </tr>

                    <tr>
                        <th>Discount:</th>
                        <td>
                            <input type="text" class="form-control form-control-sm summary_input discount_input" name="discount" value="{{$order->discount_amount}}" value="0" required>
                        </td>
                    </tr>
                    @if($order->discount_amount)
                    <tr>
                        <th>After Discount:</th>
                        <td>
                            <span class="after_discount">{{amount($order->product_total - $order->discount_amount)}}</span>
                            {{-- <input type="hidden" name="discount_amount" value="0" class="discount_amount"> --}}
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <th>Shipping Method:</th>
                        <td>Cash on Delivery</td>
                    </tr>
                    <tr>
                        <th>Shipping Charge:</th>
                        <td>
                            @if(!($order->status == 'Canceled' || $order->status == 'Returned' || $order->status == 'Completed' || $order->status == 'Delivered'))
                            <input type="text" class="form-control form-control-sm" name="shipping_charge" value="{{$order->shipping_charge}}" required>
                            @else
                            {{amount($order->shipping_charge)}}
                            <input type="hidden" name="shipping_charge" value="{{$order->shipping_charge}}">
                            @endif
                        </td>
                    </tr>
                    @if($order->courier_total_charge > 0)
                    <tr>
                        <th>Courier Charge:</th>
                        <td>
                            {{amount($order->courier_total_charge)}}
                        </td>
                    </tr>
                    @endif

                    @if($order->courier_invoice)
                    <tr>
                        <th>Courier:</th>
                        <td>
                            {{$order->courier}}
                        </td>
                    </tr>

                    <tr>
                        <th>Courier Invoice:</th>
                        <td>
                            {{$order->courier_invoice}}
                        </td>
                    </tr>
                    <tr>
                        <th>Courier Status:</th>
                        <td>
                            {{$order->courier_status}}
                        </td>
                    </tr>
                    @endif

                    @if($order->refund_shipping_amount)
                    <tr>
                        <th>Shipping Refund:</th>
                        <td>
                            {{amount($order->refund_shipping_amount)}}
                        </td>
                    </tr>
                    @endif
                    @if($order->refund_tax_amount)
                    <tr>
                        <th>HST Refund:</th>
                        <td>
                            {{amount($order->refund_tax_amount)}}
                        </td>
                    </tr>
                    @endif
                    @if($order->refund_total_amount)
                    <tr>
                        <th>Total Refund:</th>
                        <td>
                            {{amount($order->refund_total_amount)}}
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <th>Grand Total:</th>
                        <td>
                            <span class="grand_total">{{amount($order->grand_total)}}</span>
                            {{-- <input type="hidden" name="grand_total" class="grand_total_input" value="0"> --}}
                        </td>
                    </tr>
                    @if($order->shipping_order_id)
                    <tr>
                        <th>eCourier Shipping ID:</th>
                        <td>{{$order->shipping_order_id}}</td>
                    </tr>
                    @endif
                    <tr>
                        <th>Paid Amount:</th>
                        <td>
                            <input type="number" name="paid_amount" value="{{$order->paid_amount}}" class="form-control form-control-sm" placeholder="Advance paid Amount">
                        </td>
                    </tr>
                    @if($order->refund_product_total)
                    <tr>
                        <th>Returned Amount:</th>
                        <td>
                            {{amount($order->refund_product_total)}}
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <th>Due:</th>
                        <td>
                            <span class="due_amount">{{amount($order->due)}}</span>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="card-footer">
                <div class="mb-3">
                    <button class="btn btn-success">Update</button>
                    <p class="mb-0"><small><b>NB: (*)</b> marked are required field.</small></p>
                </div>

                <button class="btn btn-info" onclick="window.print();" type="button"><i class="fas fa-print"></i> Invoice Print</button>

                @if(($order->status == 'Confirmed' || $order->status == 'Hold') && !$order->courier_invoice)
                    @if(($courier_config['pathao_enabled'] ?? '') == 'Yes')
                    <button class="btn btn-success btn-block mt-2 pathaoModalBtn" data-type="1" type="button"><i class="fas fa-truck"></i> Send to Pathao</button>
                    <button class="btn btn-primary btn-block mt-2 pathaoModalBtn" data-type="2" type="button"><i class="fas fa-truck"></i> Send to Pathao 2</button>
                    @endif

                    @if(($courier_config['redx_enabled'] ?? '') == 'Yes')
                        <button class="btn btn-primary btn-block mt-2 redxModalBtn" type="button"><i class="fas fa-truck"></i> Send to REDX</button>
                    @endif

                    @if(($courier_config['steadfast_enabled'] ?? '') == 'Yes')
                        <button class="btn btn-info btn-block mt-2" data-toggle="modal" data-target="#steadfastModal" type="button"><i class="fas fa-truck"></i> Send to Steadfast</button>
                    @endif

                    @if(($courier_config['ecourier_enabled'] ?? '') == 'Yes')
                        <button class="btn btn-danger btn-block mt-2 eCourierModalBtn" type="button"><i class="fas fa-truck"></i> Send to eCourier</button>
                    @endif

                    @if(($courier_config['paperfly_enabled'] ?? '') == 'Yes')
                        <button class="btn btn-warning btn-block mt-2" data-toggle="modal" data-target="#paperflyModal" type="button"><i class="fas fa-truck"></i> Send to Paperfly</button>
                    @endif

                    @if(($courier_config['pidex_enabled'] ?? '') == 'Yes')
                        <button class="btn btn-secondary btn-block mt-2" onclick="return cAlert('error', 'Pidex API error!');" type="button"><i class="fas fa-truck"></i> Send to Pidex</button>
                    @endif
                @endif

                @if($order->courier_invoice && ($order->status == 'Delivered' || $order->status == 'In Courier'))
                <a href="{{route('orders.updateCourierStatus', $order->id)}}" class="btn btn-success btn-block mt-2">Update Courier Status</a>
                @endif

                <br>
                <p class="mb-0"><small><b>NB:</b> যদি অর্ডার Completed করা হয় তাহলে ডিউ এমাউন্ট পেইড করে দেয়া হবে!</small></p>
                <p class="mb-0"><small><b>NB:</b> যদি অর্ডার Canceled করা হয় তাহলে স্টক প্রোডাক্টের সাথে অ্যাড করে দেয়া হবে!</small></p>
                <p class="mb-0"><small><b>NB:</b> যদি অর্ডার Pending Return করা হয় তাহলে সকল প্রোডাক্ট এর স্টক গুলো সাথে সাথে জমা না হয়ে রিটার্ন এর জন্য অপেক্ষায় থাকবে!</small></p>
                <p class="mb-0"><small><b>NB:</b> যদি অর্ডার Returned করা হয় তাহলে সকল প্রোডাক্ট রিটার্ন করা হবে এবং প্রোডাক্টের স্টক প্রোডাক্টের সাথে যোগ করা হবে!</small></p>
                <p class="mb-0"><small><b>NB:</b> যদি অর্ডার কুরিয়ার এ সাবমিট করা হয় তাহলে কুরিয়ার স্টেটাস ডেলিভার্ড হলে অর্ডার অটো Completed হয়ে যাবে। এর জন্য অর্ডার অবশ্যই In Courier এ থাকতে হবে!</small></p>
            </div>
        </div>
    </div>
</div>
</form>

<div class="modal fade" id="moreQuantityModal" tabindex="-1" aria-labelledby="moreQuantityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="moreQuantityModalLabel">Add More Quantity to "<span class="add_more_title"></span>"</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{route('back.orders.addQuantity')}}" method="POST">
            @csrf

            <div class="modal-body">
                <div class="form-group">
                    <label>Current Quantity</label>
                    <input type="text" class="add_more_current form-control form-control-sm" readonly>
                </div>
                <div class="form-group">
                    <label>Add Quantity</label>
                    <input type="number" name="quantity" value="1" required class="form-control form-control-sm add_more_qty">
                </div>
                <input type="hidden" class="add_more_order_product" name="order_product">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success btn-sm">Submit</button>
            </div>
        </form>
      </div>
    </div>
</div>

<div class="modal fade" id="returnQuantityModal" tabindex="-1" aria-labelledby="moreQuantityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="moreQuantityModalLabel">Return quantity of "<span class="return_title"></span>"</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{route('back.orders.returnQuantity')}}" method="POST">
            @csrf

            <div class="modal-body">
                <div class="form-group">
                    <label>Current Quantity</label>
                    <input type="text" class="return_current form-control form-control-sm" readonly>
                </div>
                <div class="form-group">
                    <label>Return Quantity</label>
                    <input type="number" name="quantity" value="1" required class="return_return_qty form-control form-control-sm">
                </div>
                <input type="hidden" class="return_order_product" name="order_product">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success btn-sm">Submit</button>
            </div>
        </form>
      </div>
    </div>
</div>

@include('back.orders.print', [
    'for' => 'admin'
])
@if(($settings_g['print_double_invoice'] ?? 'Yes') == 'Yes')
<p  style="border-bottom: 2px dotted #000"></p>
@include('back.orders.print', [
    'for' => 'admin'
])
@endif
@endsection

@section('footer')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

    <script>
        let stock_out_can_order = "{{$settings_g['stock_out_can_order'] ?? 'No'}}";

        // Select2
        // $('.selectpicker').select2({
        //     dropdownParent: $("#exampleModal")
        // });
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

        // Variation select
        $(document).on('change', '.variation_select', function(){
            let product_data_id = $(this).val();

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
                },
                error: function(){
                    console.log('Something wrong!');
                }
            });
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
        });

        // Remove Item
        $(document).on('click', '.remove_item', function(){
            if(confirm('Are you sure to remove?')){
                $(this).closest('tr').remove();

                grandCalculation();
            }
        });

        // Add Quantity
        $(document).on('click', '.addQuantity', function(){
            let op_id = $(this).data('id');
            let op_quantity = $(this).data('current');
            let op_title = $(this).data('title');

            $('.add_more_order_product').val(op_id);
            $('.add_more_current').val(op_quantity);
            $('.add_more_qty').val('1');
            $('.add_more_title').html(op_title);
        });

        // Return Quantity
        $(document).on('click', '.returnQuantity', function(){
            let op_id = $(this).data('id');
            let op_quantity = $(this).data('current');
            let op_title = $(this).data('title');

            $('.return_order_product').val(op_id);
            $('.return_current').val(op_quantity);
            $('.return_return_qty').val('1');
            $('.return_title').html(op_title);
        });
    </script>

    @if(($order->status == 'Confirmed') && !$order->courier_invoice)
    @if(($courier_config['pathao_enabled'] ?? '') == 'Yes')
    <div id="pathaoModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="pathaoModalLabel"
    aria-hidden="true" style="padding-bottom: 40px;">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pathaoModalLabel">Send to Pathao</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('orders.sendPathaoOrder', $order->id)}}" method="POST">
                    @csrf

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><b>Store*</b></label>
                                    <select class="form-control pathao_stores" name="store" required>
                                        <option value="">Select Store</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="pathao_loactions"></div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label><b>Shipping Address*</b></label>
                                    <input type="text" name="address" value="{{$order->shipping_street}}" class="form-control" required>
                                    <p><small>Address should be in English only!</small></p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><b>Shipping Mobile Number*</b></label>
                                    <input type="text" class="form-control pathao_phone_number" name="phone_number" value="{{$order->shipping_mobile_number}}">
                                    <p><small>Mobile Number should be in English only & please remove +88 from mobile number!</small></p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label><b>Shipping Note</b></label>
                                    <input type="text" class="form-control pathao_shipping_note" value="{{$order->note}}" name="note">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><b>Collect Amount*</b></label>
                                    <input type="number" step="any" class="form-control pathao_collect_amount" value="{{$order->due}}" name="collect_amount" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><b>Weight*</b></label>
                                    <select name="weight" class="form-control" name="weight" required>
                                        <option value="0.5">0.5Kg</option>
                                        <option value="1">1Kg</option>
                                        <option value="2">2</option>
                                        <option value="3">3Kg</option>
                                        <option value="4">4Kg</option>
                                        <option value="5">5Kg</option>
                                        <option value="6">6Kg</option>
                                        <option value="7">7Kg</option>
                                        <option value="8">8Kg</option>
                                        <option value="9">9Kg</option>
                                        <option value="10">10Kg</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).on('click', '.pathaoModalBtn', function(){
            let type = $(this).data('type');

            cLoader();
            $.ajax({
                url: '{{route("orders.getPathaoInfo")}}',
                method: 'POST',
                dataType: 'json',
                data: {_token: "{{csrf_token()}}", type},
                success: function(result){
                    cLoader('hide');

                    if(result.status){
                        $('.pathao_stores').html(result.stores);
                        $('.pathao_loactions').html(result.locations_html);

                        $('#pathaoModal').modal('show');
                    }else{
                        cAlert('error', 'Error from Pathao API!');
                    }
                },
                error: function(){
                    cLoader('hide');

                    cAlert('error', 'Error from Pathao API!');
                }
            });
        });

        $(document).on('change', '.city_select', function(){
            let city_id = $(this).val();

            $('.zone_select').html('<option value="">Select Zone</option>');
            $('.area_select').html('<option value="">Select Area</option>');

            cLoader();

            $.ajax({
                url: '{{route("orders.getPathaoZone")}}',
                method: "POST",
                data: {city_id, _token: "{{csrf_token()}}"},
                success: function(response){
                    cLoader('hide');

                    if(response == 'false'){
                        cAlert('error', 'Error from Pathao API!');
                    }else{
                        $('.zone_select').html(response);
                    }
                },
                error: function(){
                    cLoader('hide');

                    cAlert('error', 'Error from Pathao API!');
                }
            });
        });

        $(document).on('change', '.zone_select', function(){
            let zone_id = $(this).val();

            $('.area_select').html('<option value="">Select Area</option>');

            cLoader();

            $.ajax({
                url: '{{route("orders.getPathaoAreas")}}',
                method: "POST",
                data: {zone_id, _token: "{{csrf_token()}}"},
                success: function(response){
                    cLoader('hide');

                    if(response == 'false'){
                        cAlert('error', 'Error from Pathao API!');
                    }else{
                        $('.area_select').html(response);
                    }
                },
                error: function(){
                    cLoader('hide');

                    cAlert('error', 'Error from Pathao API!');
                }
            });
        });
    </script>
    @endif

    @if(($courier_config['redx_enabled'] ?? '') == 'Yes')
    <div id="redexModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="redexModalLabel"
    aria-hidden="true" style="padding-bottom: 40px;">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="redexModalLabel">Submit to REDX</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('orders.sendRedexOrder', $order->id)}}" method="POST">
                    @csrf

                    <div class="modal-body">
                        <div class="redex_info_html"></div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><b>Customer Mobile Number*</b></label>
                                    <input type="text" class="form-control" name="phone_number" value="{{ $order->shipping_mobile_number }}">
                                    <p><small>Mobile Number should be in English only</small></p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><b>Weight*</b></label>
                                    <select name="weight" class="form-control" name="weight" required>
                                        <option value="500">0.5Kg</option>
                                        <option value="1000">1Kg</option>
                                        <option value="2000">2</option>
                                        <option value="3000">3Kg</option>
                                        <option value="4000">4Kg</option>
                                        <option value="5000">5Kg</option>
                                        <option value="6000">6Kg</option>
                                        <option value="7000">7Kg</option>
                                        <option value="8000">8Kg</option>
                                        <option value="9000">9Kg</option>
                                        <option value="10000">10Kg</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><b>Collect Amount*</b></label>
                                    <input type="number" step="any" class="form-control pathao_collect_amount" value="{{$order->due}}" name="collect_amount" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><b>Shipping Address*</b></label>
                            <input type="text" name="address" class="form-control" value="{{ $order->shipping_street }}" required>
                            <p><small>Address should be in English only</small></p>
                        </div>

                        <div class="form-group">
                            <label><b>Shipping Note</b></label>
                            <input type="text" class="form-control" name="note" value="{{ $order->note }}">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).on('click', '.redxModalBtn', function(){
            cLoader();
            $.ajax({
                url: '{{route("orders.getRedexInfo")}}',
                method: 'POST',
                dataType: 'json',
                data: {_token: "{{csrf_token()}}"},
                success: function(result){
                    cLoader('hide');

                    if(result.status){
                        $('.redex_info_html').html(result.html);

                        $('#redexModal').modal('show');
                    }else{
                        cAlert('error', 'Error from API!');
                    }
                },
                error: function(){
                    cLoader('hide');

                    cAlert('error', 'Error from API!');
                }
            });
        });
    </script>
    @endif

    @if(($courier_config['steadfast_enabled'] ?? '') == 'Yes')
    <div id="steadfastModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="steadfastModalLabel"
    aria-hidden="true" style="padding-bottom: 40px;">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="steadfastModalLabel">Submit to Steadfast</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('orders.sendSteadfastOrder', $order->id)}}" method="POST">
                    @csrf

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><b>Collect Amount*</b></label>
                                    <input type="number" step="any" class="form-control pathao_collect_amount" value="{{$order->due}}" name="collect_amount" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><b>Customer Mobile Number*</b></label>
                                    <input type="text" class="form-control" name="phone_number" value="{{ $order->shipping_mobile_number }}">
                                    <p><small>Mobile Number should be in English only</small></p>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form-group">
                                    <label><b>Shipping Address*</b></label>
                                    <input type="text" name="address" class="form-control" value="{{ $order->shipping_street }}" required>
                                    <p><small>Address should be in English only</small></p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><b>Shipping Note</b></label>
                            <input type="text" class="form-control" name="note" value="{{ $order->note }}">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    @if(($courier_config['ecourier_enabled'] ?? '') == 'Yes')
    <div id="eCourierModal" class="modal fade" role="dialog" aria-labelledby="eCourierModalLabel"
    aria-hidden="true" style="padding-bottom: 40px;">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eCourierModalLabel">Submit to eCourier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('courier.sendECourierOrder', $order->id)}}" method="POST">
                    @csrf

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-4 form-group">
                                <label>Phone*</label>
                                <input type="text" class="form-control modal-content" name="phone_number" value="{{$order->shipping_mobile_number}}" required>
                            </div>
                            <div class="col-12 form-group">
                                <label>Location*</label>
                                <select class="form-control modal-content" id="eCourier_location" name="location" required>
                                </select>
                            </div>
                            <div class="col-lg-8 form-group">
                                <label>Address*</label>
                                <input type="text" class="form-control modal-content" name="address" value="{{$order->shipping_street}}" required>
                            </div>
                            <div class="col-lg-4 form-group">
                                <label>Payment Method*</label>
                                <select class="form-control modal-content" name="payment_method" required>
                                    <option value="COD">Cash On Delivery - COD</option>
                                    <option value="POS">Point of Sale - POS</option>
                                    <option value="MPAY">Mobile Payment - MPAY</option>
                                    <option value="CCRD">Card Payment - CCRD</option>
                                </select>
                            </div>
                            <div class="col-lg-8 form-group">
                                <label>Package*</label>
                                <select class="form-control modal-content" id="eCourier_package2" name="package" required>
                                </select>
                            </div>
                            <div class="col-lg-4 form-group">
                                <label>Collect Amount*</label>
                                <input type="text" class="form-control modal-content" id="eCourier_product_total2" value="{{$order->due}}" name="collect_amount" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><b>Shipping Note</b></label>
                            <input type="text" class="form-control" name="note" value="{{ $order->note }}">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).on('click', '.eCourierModalBtn', function(){
            cLoader();
            $.ajax({
                url: '{{route("orders.getECourierInfo")}}',
                method: 'POST',
                dataType: 'json',
                data: {_token: "{{csrf_token()}}"},
                success: function(result){
                    cLoader('hide');

                    if(result.status){
                        // $('#eCourier_package').html(result.packages);
                        // $('#eCourier_cities').html(result.cities);
                        // $('#eCourier_thana').html(result.thana);

                        $('#eCourier_package2').html(result.packages);

                        $('#eCourierModal').modal('show');
                    }else{
                        cAlert('error', 'Error from eCourier API!');
                    }
                },
                error: function(){
                    cLoader('hide');

                    cAlert('error', 'Error from eCourier API!');
                }
            });
        });

        $('#eCourier_location').select2({
            placeholder: "Search Location by Name",
            minimumInputLength: 1,
            ajax: {
                url: '{{ route("courier.eCourierSearchLocation") }}',
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
    </script>
    @endif

    @if(($courier_config['paperfly_enabled'] ?? '') == 'Yes')
    <div id="paperflyModal" class="modal fade" role="dialog" aria-labelledby="paperflyModalLabel"
    aria-hidden="true" style="padding-bottom: 40px;">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paperflyModalLabel">Submit to Paperfly</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('courier.sendPaperflyOrder', $order->id)}}" method="POST">
                    @csrf

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-4 form-group">
                                <label>Phone*</label>
                                <input type="text" class="form-control modal-content" name="phone_number" value="{{$order->shipping_mobile_number}}" required>
                            </div>
                            <div class="col-lg-4 form-group">
                                <label>Thana*</label>
                                <input type="text" class="form-control modal-content" name="thana" required>
                            </div>
                            <div class="col-lg-4 form-group">
                                <label>District*</label>
                                <input type="text" class="form-control modal-content" name="district" required>
                            </div>

                            <div class="col-lg-12 form-group">
                                <label>Address*</label>
                                <input type="text" class="form-control modal-content" name="address" value="{{$order->shipping_street}}" required>
                            </div>

                            <div class="col-lg-3 form-group">
                                <label>Collect Amount*</label>
                                <input type="text" class="form-control modal-content" id="eCourier_product_total2" value="{{$order->due}}" name="collect_amount" required>
                            </div>

                            <div class="col-lg-3 form-group">
                                <label>Size*</label>
                                <select class="form-control modal-content" name="size" required>
                                   <option value="standard">Standard</option>
                                   <option value="large">Large</option>
                                   <option value="special">Special</option>
                                </select>
                             </div>

                            <div class="col-lg-3 form-group">
                                <label>Delivery Option*</label>
                                <select class="form-control modal-content" name="delivery_option" required>
                                    <option value="regular">Regular</option>
                                    <option value="express">Express</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Weight*</label>
                                    <select name="weight" class="form-control" name="weight" required>
                                        <option value="0.5">0.5Kg</option>
                                        <option value="1">1Kg</option>
                                        <option value="2">2</option>
                                        <option value="3">3Kg</option>
                                        <option value="4">4Kg</option>
                                        <option value="5">5Kg</option>
                                        <option value="6">6Kg</option>
                                        <option value="7">7Kg</option>
                                        <option value="8">8Kg</option>
                                        <option value="9">9Kg</option>
                                        <option value="10">10Kg</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Shipping Note</label>
                            <input type="text" class="form-control" name="note" value="{{ $order->note }}">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    @endif

    <script>
        $(document).on('change', '.order_status', function(){
            let status = $(this).val();
            if(status == 'Canceled' || status == 'Returned'){
                $('.cancel_return_reason_wrap').show();
            }else{
                $('.cancel_return_reason_wrap').hide();
            }
            customCancelReson();
        });
        $(document).on('change', '.cancel_return_reason', function(){
            customCancelReson();
        });

        function customCancelReson(){
            let cancel_type = $('.cancel_return_reason').val();
            if(cancel_type == 'Custom'){
                $('.custom_cancel_return_reason_wrap').show();
            }else{
                $('.custom_cancel_return_reason_wrap').hide();
            }
        }
    </script>
@endsection
