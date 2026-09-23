@extends('back.layouts.master')
@section('title', 'Attribute items')

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.css"/>
@endsection

@section('master')
<div class="row">
    <div class="col-md-8">
        <div class="card border-light mt-3 shadow">
            <div class="card-header">
                <h5 class="d-inline-block">Attribute items of "{{$attribute->name}}"</h5>

                <a href="{{route('back.attributes.index')}}" class="btn btn-success btn-sm float-right"><i class="fas fa-list"></i> All attribute</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="dataTable">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($attribute->AttributeItems as $attribute_items)
                            <tr>
                                <th scope="row">{{$attribute_items->id}}</th>
                                <td>{{$attribute_items->name}}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          Action
                                        </button>
                                        <div class="dropdown-menu">
                                          <a class="dropdown-item" href="{{route('back.attributes.itemEdit', $attribute_items->id)}}"><i class="fas fa-edit"></i> Edit</a>
                                          <a class="dropdown-item" href="{{route('back.attributes.itemDestroy', $attribute_items->id)}}" onclick="return confirm('Are you sure to remove?');"><i class="fas fa-trash text-danger"></i> Delete</a>
                                          {{-- <a class="dropdown-item" href="{{route('back.attributes.itemDestroy', $attribute->id)}}"><i class="fas fa-trash text-danger"> Edit</a> --}}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-light mt-3 shadow">
            <div class="card-header">
                <h5 class="d-inline-block">Create attribute item</h5>
            </div>

            <form action="{{route('back.attributes.itemStore', $attribute->id)}}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><b>Item Name*</b></label>
                                <input type="text" class="form-control" name="name" value="{{old('name')}}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-success">Submit</button>
                    <br>
                    <small><b>NB: *</b> marked are required field.</small>
                </div>
            </form>
        </div>
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
