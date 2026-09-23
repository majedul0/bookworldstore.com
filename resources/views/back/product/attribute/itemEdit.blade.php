@extends('back.layouts.master')
@section('title', 'Edit Attribute Item')

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.css"/>
@endsection

@section('master')
<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card border-light mt-3 shadow">
            <div class="card-header">
                <h5 class="d-inline-block">Edit attribute item</h5>

                <a href="{{route('back.attributes.show', $attribute_item->attribute_id)}}" class="btn btn-success btn-sm float-right"><i class="fas fa-arrow-left"></i> Go back</a>
            </div>

            <form action="{{route('back.attributes.itemEdit', $attribute_item->id)}}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><b>Name*</b></label>
                                <input type="text" class="form-control" name="name" value="{{old('name') ?? $attribute_item->name}}" required>
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
