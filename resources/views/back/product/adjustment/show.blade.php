@extends('back.layouts.master')
@section('title', 'Purchase Details')

@section('master')
<div class="card border-light mt-3 shadow">
    <div class="card-header">
        <h6 class="d-inline-block">Invoice Information</h6>
    </div>

    <div class="card-body">

        <table class="table table-bordered table-sm">
            <tbody>
                <tr>
                    <th class="text-right">ID</th>
                    <td>#{{$adjustment->id}}</td>
                </tr>
                <tr>
                    <th class="text-right">Date</th>
                    <td>{{date('d/m/Y', strtotime($adjustment->created_at))}}</td>
                </tr>
                <tr>
                    <th class="text-right">Supplier</th>
                    <td><a href="{{route('back.suppliers.show', $adjustment->user_id)}}">{{$adjustment->user->full_name ?? 'n/a'}}</a></td>
                </tr>
                <tr>
                    <th class="text-right">Total Amount</th>
                    <td>{{amount($adjustment->grand_total)}}</td>
                </tr>
                <tr>
                    <th class="text-right">Paid Amount</th>
                    <td>{{amount($adjustment->paid_amount)}}</td>
                </tr>
                <tr>
                    <th class="text-right">Due Amount</th>
                    <td>{{amount($adjustment->due)}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card border-light mt-3 shadow">
    <div class="card-header">
        <h6 class="d-inline-block">Purchase Items</h6>
    </div>

    <div class="card-body">

        <table class="table table-bordered table-sm">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Image</th>
                <th scope="col">Variation</th>
                <th scope="col" style="width: 120px">Unit Cost</th>
                <th scope="col" style="width: 120px">Quantity</th>
                <th scope="col" style="width: 120px">Subtotal</th>
            </tr>
            </thead>
            <tbody class="listed_items">
                @foreach($adjustment->purchase_items as $purchase_item)
                    <tr>
                        <th>{{$loop->index + 1}}</th>
                        <td>{{$purchase_item->product->title ?? 'n/a'}}</td>
                        <td><img src="{{$purchase_item->product->img_paths['small']}}" style="width:35px"></td>
                        <td>{{$purchase_item->product_data->attribute_items_string ?? 'n/a'}}</td>
                        <td>{{amount($purchase_item->purchase_price)}}</td>
                        <td>{{$purchase_item->purchase_quantity}}</td>
                        <td>{{amount($purchase_item->purchase_price * $purchase_item->purchase_quantity)}}</td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot>
                <th scope="col" class="text-right" colspan="6">Subtotal</th>
                <th scope="col">{{amount($adjustment->grand_total)}}</th>
            </tfoot>
        </table>
    </div>
</div>
@endsection
