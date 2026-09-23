@extends('back.layouts.master')
@section('title', 'Locations')

@section('master')
<div class="card border-light mt-3 shadow">
    <div class="card-header">
        <h5 class="d-inline-block">Districts</h5>

        {{-- <a href="{{route('back.customers.create')}}" class="btn btn-success btn-sm float-right"><i class="fas fa-plus"></i> Create new</a> --}}
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-sm" id="dataTable">
            <thead>
              <tr>
                <th scope="col">SL.</th>
                <th scope="col">Name</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($states as $state)
                    <tr>
                        <th scope="row">
                            {{$loop->index + 1}}
                        </th>
                        <td>
                            <form action="{{route('back.locations.statesUpdate', $state->id)}}" method="POST">
                                @csrf

                                <div class="input-group">
                                    <input class="form-control form-control-sm" type="text" name="name" value="{{$state->name}}" required>

                                    <div class="input-group-append">
                                        <span class="input-group-btn">
                                            <button class="update_price btn btn-info btn-sm" type="submit" title="Update Name">
                                            <i class="fas fa-upload"></i>
                                        </button></span>
                                    </div>
                                </div>
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
    $('#dataTable').DataTable({
        order: [[0, "asc"]],
    });
</script>
@endsection
