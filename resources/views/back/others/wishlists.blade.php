@extends('back.layouts.master')
@section('title', 'Customer Wishlists')

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.css"/>
@endsection

@section('master')
<div class="card border-light mt-3 shadow">
    <div class="card-header">
        <h5 class="d-inline-block">Wishlists</h5>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-sm" id="dataTable">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Date</th>
                <th scope="col">Customer</th>
                <th scope="col">Product</th>
                <th scope="col" class="text-right">Action</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($wishlists as $key => $wishlist)
                    <tr>
                        <th scope="row">{{$key + 1}}</th>
                        <td>{{date('d/m/Y h:ia', strtotime($wishlist->created_at))}}</td>
                        <td>
                            @if($wishlist->User)
                                <p class="mb-0"><b>{{$wishlist->User->full_name}}</b></p>
                                <p class="mb-0"><small>{{$wishlist->User->mobile_number}}</small></p>
                                <p class="mb-0"><small>{{$wishlist->User->email}}</small></p>
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            <p class="mb-0"><a target="_blank" href="{{$wishlist->Product->route ?? '#'}}">{{$wishlist->Product->title ?? 'N/A'}}</a></p>
                        </td>
                        <td class="text-right">
                            <a href="{{route('back.wishlistDelete', $wishlist->id)}}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to remove?');"><i class="fas fa-trash"></i></a>
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
    $(document).ready( function () {
        $('#dataTable').DataTable({
            order: [[0, "desc"]],
        });
    });
</script>
@endsection
