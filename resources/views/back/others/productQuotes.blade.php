@extends('back.layouts.master')
@section('title', 'Product Quotes')

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.css"/>
@endsection

@section('master')
<div class="card border-light mt-3 shadow">
    <div class="card-header">
        <h5 class="d-inline-block">Quote List</h5>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-sm" id="dataTable">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Product</th>
                <th scope="col">Quantity</th>
                <th scope="col">Name</th>
                <th scope="col">Address</th>
                <th scope="col">Description</th>
                <th scope="col">Mobile Number</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($quotes as $quote)
                    <tr>
                        <td scope="row">{{$quote->id}}</td>
                        <td scope="row"><a href="{{$quote->Product->route}}" target="_blank">{{$quote->Product->title}}</a></td>
                        <td scope="row">{{$quote->quantity}}</td>
                        <td scope="row">{{$quote->name}}</td>
                        <td scope="row">{{$quote->address}}</td>
                        <td scope="row">{{$quote->description}}</td>
                        <td scope="row">{{$quote->mobile_number}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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
