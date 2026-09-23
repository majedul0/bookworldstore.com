@extends('back.layouts.master')
@section('title', 'Recycle Bin')

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.css"/>
@endsection

@section('master')
<div class="card border-light mt-3 shadow">
    <div class="card-header">
        <h5 class="d-inline-block">Deleted Products</h5>
    </div>

    <div class="card-body table-responsive">
        <table class="table table-bordered table-sm" id="dataTable">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Image</th>
                <th scope="col">Type</th>
                <th scope="col">Regular Price</th>
                <th scope="col">Sale Price</th>
                <th scope="col" class="text-right">Action</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{$product->id}}</td>
                        <td>{{$product->title}}</td>
                        <td><img src="{{$product->img_paths['small']}}" style="width:35px"></td>
                        <td>{{$product->type}}</td>
                        <td>{{$product->regular_price}}</td>
                        <td>{{$product->sale_price}}</td>
                        <td class="text-right">
                            <a href="{{route('back.recycleBun.restoreProduct', $product->id)}}" class="btn btn-sm btn-success mb-1" onclick="return confirm('Are you sure to Restore?');"><i class="fas fa-undo-alt"></i> Restore</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="card border-light mt-3 shadow">
    <div class="card-header">
        <h5 class="d-inline-block">Deleted Categories</h5>
    </div>

    <div class="card-body table-responsive">
        <table class="table table-bordered table-sm" id="dataTableCategory">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Title</th>
                <th scope="col" class="text-right">Action</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td>{{$category->id}}</td>
                        <td>{{$category->title}}</td>
                        <td class="text-right">
                            <a href="{{route('back.recycleBun.restoreCategory', $category->id)}}" class="btn btn-sm btn-success mb-1" onclick="return confirm('Are you sure to Restore?');"><i class="fas fa-undo-alt"></i> Restore</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="card border-light mt-3 shadow">
    <div class="card-header">
        <h5 class="d-inline-block">Deleted Brands</h5>
    </div>

    <div class="card-body table-responsive">
        <table class="table table-bordered table-sm" id="dataTableBrand">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Title</th>
                <th scope="col" class="text-right">Action</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($brands as $brand)
                    <tr>
                        <td>{{$brand->id}}</td>
                        <td>{{$brand->title}}</td>
                        <td class="text-right">
                            <a href="{{route('back.recycleBun.restoreBrand', $brand->id)}}" class="btn btn-sm btn-success mb-1" onclick="return confirm('Are you sure to Restore?');"><i class="fas fa-undo-alt"></i> Restore</a>
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
        order: [[0, "asc"]],
    });
    $('#dataTableCategory').DataTable({
        order: [[0, "asc"]],
    });
    $('#dataTableBrand').DataTable({
        order: [[0, "asc"]],
    });
</script>
@endsection
