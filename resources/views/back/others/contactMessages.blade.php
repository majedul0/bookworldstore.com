@extends('back.layouts.master')
@section('title', 'Contact Messages')

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.css"/>
@endsection

@section('master')
<div class="card border-light mt-3 shadow">
    <div class="card-header">
        <h5 class="d-inline-block">Contact Messages</h5>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-sm" id="dataTable">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Date</th>
                <th scope="col">Name</th>
                <th scope="col">Phone</th>
                <th scope="col">Email</th>
                <th scope="col">Message</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($messages as $message)
                    <tr>
                        <td scope="row">{{$message->id}}</td>
                        <td scope="row">{{$message->created_at->format('d M Y, h:i A')}}</td>
                        <td scope="row">{{$message->name}}</td>
                        <td scope="row">
                            @if($message->phone)
                                <a href="tel:{{$message->phone}}">{{$message->phone}}</a>
                            @endif
                        </td>
                        <td scope="row">
                            @if($message->email)
                                <a href="mailto:{{$message->email}}">{{$message->email}}</a>
                            @endif
                        </td>
                        <td scope="row" style="white-space: pre-line;">{{$message->message}}</td>
                        <td scope="row">
                            <a href="{{route('back.contactMessageDelete', $message->id)}}" class="btn btn-sm btn-danger" onclick="return confirm('Delete this message?')"><i class="fas fa-trash"></i></a>
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
