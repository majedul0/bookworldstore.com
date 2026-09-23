@extends('back.layouts.master')
@section('title', 'Product Reviews')

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.css"/>
@endsection

@section('master')
<div class="card border-light mt-3 shadow">
    <div class="card-header">
        <h5 class="d-inline-block">Review list</h5>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-sm" id="dataTable">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Address</th>
                <th scope="col">Product</th>
                <th scope="col">Rating</th>
                <th scope="col">Review</th>
                <th scope="col">Status</th>
                <th scope="col" class="text-right">Action</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($reviews as $key => $review)
                    <tr>
                        <td>{{$key + 1}}</td>
                        <td>{{$review->name}}</td>
                        <td>{{$review->email}}</td>
                        <td>{{$review->address}}</td>
                        <td><a target="_blank" href="{{$review->Product->route ?? '#'}}">{{$review->Product->title ?? 'N/A'}}</a></td>
                        <td>{{$review->rating}}</td>
                        <td>{{$review->review}}</td>
                        <td>{{$review->status_string}}</td>
                        <td class="text-right">
                            <div class="d-inline-block" style="width: 120px">
                                @if($review->status == 2)
                                <a onclick="return confirm('Are you sure to Approve?');" href="{{route('back.products.reviewAction', ['review' => $review->id, 'action' => 1])}}" class="btn btn-success btn-sm c_tooltip" data-toggle="tooltip" data-placement="top" title="Approve"><i class="fas fa-check"></i></a>
                                <a onclick="return confirm('Are you sure to Reject?');" href="{{route('back.products.reviewAction', ['review' => $review->id, 'action' => 0])}}" class="btn btn-warning btn-sm c_tooltip" data-placement="top" title="Reject"><i class="fas fa-times"></i></a>
                                @endif
                                <a onclick="return confirm('Are you sure to Delete?');" href="{{route('back.products.reviewDelete', $review->id)}}" class="btn btn-danger btn-sm c_tooltip" data-placement="top" title="Delete"><i class="fas fa-trash"></i></a>
                            </div>
                        </td>
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
    $('#dataTable').DataTable({
        "order": [[0, "desc"]]
    });
</script>
@endsection
