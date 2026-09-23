@extends('back.layouts.master')
@section('title', 'Edit Role')

@section('master')
<div class="card mb-1">
    <div class="card-header with-border">
        <h5 class="d-inline-block mt-1">Edit Role</h5>

        <a href="{{route('back.roles.index')}}" class="btn btn-sm btn-info float-right">Back</a>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form role="form" method="post" action="{{route('back.roles.update', $role->id)}}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label">Role Name</label>
                        <input type="text" class="form-control" name="name" value="{{old('name') ?? $role->name}}" placeholder="Role Name" required>
                    </div>
                </div>
            </div>

            @foreach ($items as $item)
                <h5>{{$item['group_name']}}</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>Menu Name</th>
                                <th>Checkbox</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($item['routes'] as $key => $route)
                                <tr>
                                    <td>{{$key + 1}}</td>
                                    <td>{{$route['name']}}</td>
                                    <td class="text-right" style="width: 80px">
                                        <input type="checkbox" class="row_checkbox" name="route[]" value="{{$item['group_name'] . '::' . $route['route']}}" {{in_array($route['route'], $role->routes_arr) ? 'checked' : ''}} style="transform: scale(1.8);">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
        <!-- /.box-body -->

        <div class="card-footer">
            <button class="btn btn-success" name="btn_type" value="Save">Save</button>
            <br>
            <small><b>NB: *</b> marked are required field.</small>
        </div>
    </form>
</div>
@endsection
