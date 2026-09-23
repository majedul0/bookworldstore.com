@extends('back.layouts.master')
@section('title', 'Payable Suppliers')

@section('master')
<div class="card border-light mt-3 shadow">
    <div class="card-header">
        <h5 class="d-inline-block">Payable Suppliers list</h5>

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
                {{-- <th scope="col" class="text-right">Action</th> --}}
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
                        {{-- <td class="text-right">
                            <a class="btn btn-info btn-sm" href="{{route('back.suppliers.show', $user->id)}}"><i class="fas fa-dollar-sign"></i> Make Payment</a>
                        </td> --}}
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
    datatable_filename = 'Payable Supplier List';
    datatable_paging = false;
</script>

@include('back.layouts.datatableJS')
@endsection
