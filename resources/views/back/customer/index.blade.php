@extends('back.layouts.master')
@section('title', 'Customers')

@section('master')
<div class="card border-light mt-3 shadow">
    <div class="card-header">
        <h5 class="d-inline-block">Customers list</h5>

        <a href="{{route('back.customers.create')}}" class="btn btn-success btn-sm float-right"><i class="fas fa-plus"></i> Create new</a>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-sm" id="dataTable">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Mobile Number</th>
                {{-- <th scope="col">Balance</th> --}}
                <th scope="col">Status</th>
                <th scope="col" class="text-right">Action</th>
              </tr>
            </thead>
            <tbody>
                {{-- @foreach ($users as $user)
                    <tr>
                        <th scope="row">
                            <a href="{{route('back.customers.show', $user->id)}}">{{$user->id}}</a>
                        </th>
                        <td><a href="{{route('back.customers.show', $user->id)}}">{{$user->full_name}}</a></td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->mobile_number}}</td>
                        <td>{{amount($user->balance)}}</td>
                        <td>{{$user->status_string}}</td>
                        <td class="text-right">
                            <a class="btn btn-success btn-sm" href="{{route('back.customers.edit', $user->id)}}"><i class="fas fa-edit"></i></a>

                            <form class="d-inline-block" action="{{route('back.customers.destroy', $user->id)}}" method="POST">
                                @method('DELETE')
                                @csrf

                                <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Are you sure to remove?')"><i class="fas fa-trash"></i></button>
                            </form>

                            <a class="btn btn-primary btn-sm" href="{{route('back.customers.show', $user->id)}}">Ledger</a>
                        </td>
                    </tr>
                @endforeach --}}
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('footer')
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.js"></script>

<script>
    function getDataTable(){
        $('#dataTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{route('back.customers.table')}}",
                "dataType": "json",
                "type": "POST",
                "data": {_token: "{{csrf_token()}}"}
            },
            "columns": [
                {"data": "id"},
                {"data": "name"},
                {"data": "email"},
                {"data": "mobile_number"},
                {"data": "status"},
                {"data": "action"}
            ],
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "order": [[0, "desc"]],
            "columnDefs": [
                { orderable: true, className: 'reorder', targets: [0] },
                { orderable: false, targets: '_all' }
            ]
        });
    }
    getDataTable();
</script>
@endsection

{{-- @section('footer')
<script>
    let export_columns = [0, 1, 2, 3, 4, 5];
    datatable_dir = 'desc';
    datatable_filename = 'Customer List';
    datatable_paging = false;
</script>

@include('back.layouts.datatableJS')
@endsection --}}
