@extends('back.layouts.master')
@section('title', 'Brands')

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.css"/>
@endsection

@section('master')
<div class="card border-light mt-3 shadow">
    <div class="card-header">
        <h5 class="d-inline-block">Brand list</h5>

        <a href="{{route('back.brands.create')}}" class="btn btn-success btn-sm float-right"><i class="fas fa-plus"></i> Create new</a>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-sm" id="dataTable">
            <thead>
              <tr>
                {{-- <th scope="col">#</th> --}}
                <th scope="col">Brand name</th>
                <th scope="col">Brand logo</th>
                <th scope="col">Status</th>
                <th scope="col" class="text-right">Action</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($brands as $brand)
                    <tr>
                        {{-- <th scope="row">{{$brand->id}}</th> --}}
                        <td>{{$brand->title}}</td>
                        <td><img src="{{$brand->img_paths['small']}}" style="width: 35px" alt=""></td>
                        <td>{{$brand->status == 1 ? 'Active' : 'Disabled'}}</td>
                        <td class="text-right">
                            <div class="d-inline-block" style="width: 80px">
                                <a class="btn btn-success btn-sm" href="{{route('back.brands.edit', $brand->id)}}"><i class="fas fa-edit"></i></a>
                                <form class="d-inline-block" action="{{route('back.brands.destroy', $brand->id)}}" method="POST">
                                    @method('DELETE')
                                    @csrf

                                    <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Are you sure to remove?')"><i class="fas fa-trash"></i></button>
                                </form>
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
    $(document).ready( function () {
        $('#dataTable').DataTable({
            order: [[0, "asc"]],
        });
    });
</script>
@endsection
