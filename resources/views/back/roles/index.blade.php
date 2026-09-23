@extends('back.layouts.master')
@section('title', 'Role List')

@section('master')
<div class="card mb-1">
    <div class="card-header with-border">
        <h5 class="d-inline-block mt-1">Role List</h5>
        <a href="{{route('back.roles.create')}}" class="btn btn-sm btn-info float-right">Add Role</a>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <div class="card-body table-responsive">
        <table id="dataTable" class="table table-bordered table-sm table-hover">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Role Name</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $key => $role)
                <tr>
                    <td>{{$key + 1}}</td>
                    <td>{{$role->name}}</td>
                    <td>
                        <div class="dropdown text-right">
                            <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action <i class="fa fa-angle-down"></i>
                            </button>

                            <div class="dropdown-menu">
                                @if(in_array('back.roles.edit', $role_routes))
                                <a class="dropdown-item" href="{{route('back.roles.edit', $role->id)}}"><i class="fa fa-edit text-info"></i> Edit</a>
                                @endif

                                @if(in_array('back.roles.destroy', $role_routes))
                                <div class="dropdown-item">
                                    <form action="{{route('back.roles.destroy', $role->id)}}" method="post">
                                        @method('DELETE')
                                        @csrf
                                        <button class="dc"><i class="fa fa-trash text-danger"></i> Delete</button>
                                    </form>
                                </div>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>
@endsection

@section('footer')
<script>
    $('#dataTable').DataTable({
        order: [[0, "asc"]],
    });
</script>
@endsection
