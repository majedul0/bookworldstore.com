@extends('back.layouts.master')
@section('title', 'Testimonials')

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.css"/>
@endsection

@section('master')
<div class="card border-light shadow">
    <div class="card-header">
        <h5 class="d-inline-block">Testimonials list</h5>

        <a href="{{route('back.testimonials.create')}}" class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i> Create new</a>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-sm" id="dataTable">
            <thead>
              <tr>
                <th scope="col">Sl.</th>
                <th scope="col">Client</th>
                <th scope="col">Designation</th>
                <th scope="col">Testimonial</th>
                <th scope="col" class="text-right">Action</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($testimonials as $key => $testimonial)
                    <tr>
                        <th scope="row">{{$key + 1}}</th>
                        <td>{{$testimonial->client_name}}</td>
                        <td>{{$testimonial->client_desiccation}}</td>
                        <td>{{$testimonial->testimonial}}</td>
                        <td class="text-right">
                            <div class="dropdown text-right">
                                <button class="btn btn-primary btn-sm dropdown-toggle d-flex" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action
                                </button>

                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{route('back.testimonials.edit', $testimonial->id)}}"><i class="fa fa-edit text-info"></i> Edit</a>

                                    <div class="dropdown-item">
                                        <form action="{{route('back.testimonials.destroy', $testimonial->id)}}" method="post">
                                            @method('DELETE')
                                            @csrf
                                            <button onclick="return confirm('Are you sure to delete?')"><i class="fa fa-trash text-danger"></i> Delete</button>
                                        </form>
                                    </div>
                                </div>
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
            order: [[0, "desc"]],
        });
    });
</script>
@endsection
