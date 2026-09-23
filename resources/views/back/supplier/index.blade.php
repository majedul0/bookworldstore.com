@extends('back.layouts.master')
@section('title', 'Suppliers')

@section('master')
<div class="card border-light mt-3 shadow">
    <div class="card-header">
        <h5 class="d-inline-block">Suppliers list</h5>

        <a href="{{route('back.suppliers.create')}}" class="btn btn-success btn-sm float-right"><i class="fas fa-plus"></i> Create new</a>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-sm" id="dataTable">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Mobile Number</th>
                <th scope="col">Supplier Payable</th>
                <th scope="col" class="text-right">Action</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <th scope="row">{{$user->id}}</th>
                        <td>
                            <a href="{{route('back.suppliers.show', $user->id)}}">{{$user->full_name}}</a>
                        </td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->mobile_number}}</td>
                        <td>{{$user->balance < 0 ? amount(abs($user->balance)) : amount(0 - $user->balance)}}</td>
                        <td class="text-right">
                            <a class="btn btn-info btn-sm" href="{{route('back.suppliers.show', $user->id)}}"><i class="fas fa-eye"></i></a>

                            <a class="btn btn-success btn-sm" href="{{route('back.suppliers.edit', $user->id)}}"><i class="fas fa-edit"></i></a>

                            <form class="d-inline-block" action="{{route('back.suppliers.destroy', $user->id)}}" method="POST">
                                @method('DELETE')
                                @csrf

                                <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Are you sure to remove?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('footer')
<script>
    let export_columns = [0, 1, 2, 3, 4];
    datatable_dir = 'desc';
    datatable_filename = 'Supplier List';
    datatable_paging = false;
</script>

@include('back.layouts.datatableJS')
@endsection
