@extends('back.layouts.master')
@section('title', 'Sub Categories')

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.css"/>
@endsection

@section('master')
<div class="row">
    <div class="col-md-7">
        <div class="card border-light mt-3 shadow">
            <div class="card-header">
                <h5 class="d-inline-block">Sub Categories of "fdgfdgfdg"</h5>

                <a href="{{route('back.categories.index')}}" class="btn btn-success btn-sm float-right"><i class="fas fa-plus"></i> All category</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="dataTable">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($category->Categories as $sub_category)
                            <tr>
                                <th scope="row">{{$sub_category->id}}</th>
                                <td>{{$sub_category->title}}</td>
                                <td>{{$sub_category->status == 1 ? 'Active' : 'Disabled'}}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          Action
                                        </button>
                                        <div class="dropdown-menu">
                                          <a class="dropdown-item" href="{{route('back.categories.edit', $sub_category->id)}}"><i class="fas fa-edit"></i> Edit/Details</a>
                                          <a class="dropdown-item" href="{{route('back.categories.show', $sub_category->id)}}"><i class="fas fa-eye"></i> Sub Categories</a>

                                          <div class="dropdown-item">
                                            <form action="{{route('back.categories.destroy', $sub_category->id)}}" method="POST">
                                                @method('DELETE')
                                                @csrf

                                                <button type="submit" onclick="return confirm('Are you sure to remove?')"><i class="fas fa-trash text-danger"></i> Delete</button>
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
    </div>

    <div class="col-md-5">
        <div class="card border-light mt-3 shadow">
            <div class="card-header">
                <h5 class="d-inline-block">Create Sub Categories</h5>
            </div>

            <form action="{{route('back.categories.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="category_id" value="{{$category->id}}">

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><b>Title*</b></label>
                                <input type="text" class="form-control" name="title" value="{{old('title')}}" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><b>Short Description</b></label>

                                <textarea class="form-control" name="short_description" cols="30" rows="3">{{old('short_description')}}</textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label><b>Description</b></label>

                                <textarea id="editor" class="form-control" name="description" cols="30" rows="3">{{old('description')}}</textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label><b>Image</b></label>
                                <input type="file" class="form-control" name="image" accept="image/*">
                            </div>

                            <hr>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label><b>Meta title</b></label>

                                <input type="text" class="form-control" name="meta_title" value="{{old('meta_title')}}">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label><b>Meta description</b></label>

                                <input type="text" class="form-control" name="meta_description" value="{{old('meta_description')}}">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label><b>Meta tags</b></label>

                                <input type="text" class="form-control" name="meta_tags" value="{{old('meta_tags')}}">
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

<!-- CK Editor -->
    <script src="{{asset('back/ckeditor4-4.15.1/ckeditor.js')}}"></script>

<script>
    $(document).ready( function () {
        $('#dataTable').DataTable({
            order: [[0, "desc"]],
        });
    });

    // CKEditor
    $(function () {
        CKEDITOR.replace('editor', {
            height: 400
        });
    });
</script>
@endsection
