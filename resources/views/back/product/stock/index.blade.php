@extends('back.layouts.master')
@section('title', 'Purchase')

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.css"/>
@endsection

@section('master')
<div class="card border-light mt-3 shadow">
    <div class="card-header">
        <h5 class="d-inline-block">List</h5>

        <a href="{{route('back.stocks.create')}}" class="btn btn-success btn-sm float-right"><i class="fas fa-plus"></i> Add new purchase</a>
    </div>
    <div class="card-body table-responsive">
        {{-- <table class="table table-bordered table-sm" id="dataTable">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Product Name</th>
                <th scope="col">Supplier</th>
                <th scope="col">Variant</th>
                <th scope="col">Purchase Price</th>
                <th scope="col">Purchase Quantity</th>
                <th scope="col">Current Quantity</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($stocks as $stock)
                    <tr>
                        <th scope="row">{{$stock->id}}</th>
                        <td>{{$stock->Product->title ?? 'N/A'}}</td>
                        <td>{{$stock->User->full_name ?? 'N/A'}}</td>
                        <td>{{$stock->ProductData->attribute_items_string ?? 'N/A'}}</td>
                        <td>{{$stock->purchase_price}}</td>
                        <td>{{$stock->purchase_quantity}}</td>
                        <td>{{$stock->current_quantity}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table> --}}
    </div>
</div>
@endsection

@section('footer')
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.js"></script>

<script>
    $(document).ready( function () {
        $('#dataTable').DataTable({
            order: [[0, "desc"]],
        });
    });
</script>
@endsection
