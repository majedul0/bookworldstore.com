@extends('back.layouts.master')
@section('title', 'Edit Category')

@section('master')
<div class="card">
    <div class="card-header no_icon">
        <a href="{{route('back.categories.index')}}" class="btn btn-success btn-sm"><i class="fas fa-angle-double-left"></i> View All</a>
        <a href="{{route('back.categories.create')}}" class="btn btn-info btn-sm"><i class="fas fa-plus"></i> Create</a>

        <form class="d-inline-block" action="{{route('back.categories.destroy', $category->id)}}" method="POST">
            @method('DELETE')
            @csrf

            <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('Are you sure to remove?')"><i class="fas fa-trash"></i> Delete</button>
        </form>
    </div>
</div>

<form action="{{route('back.categories.update', $category->id)}}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    <div class="row">
        <div class="col-md-8">
            <div class="card border-light mt-3 shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><b>Title*</b></label>
                                <input type="text" class="form-control form-control-sm" name="title" value="{{old('title') ?? $category->title}}" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><b>Short Description</b></label>

                                <textarea class="form-control form-control-sm" name="short_description" cols="30" rows="3">{{old('short_description') ?? $category->short_description}}</textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label><b>Description</b></label>

                                <textarea id="editor" class="form-control form-control-sm" name="description" cols="30" rows="3">{{old('description') ?? $category->description}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-light mt-3 shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="text-center">
                                <div class="img_group">
                                    <img class="img-thumbnail uploaded_img" src="{{$category->img_paths['small']}}">

                                    @if($category->media_id)
                                    <a href="{{route('back.categories.removeImage', $category->id)}}" onclick="return confirm('Are you sure to remove?');" class="btn btn-sm btn-danger remove_image" title="Remove image"><i class="fas fa-times"></i></a>
                                    @endif

                                    <div class="form-group text-center">
                                        <label><b>Category Image</b></label>
                                        <div class="custom-file text-left">
                                            <input type="file" class="custom-file-input image_upload" name="image" accept="image/*">
                                            <label class="custom-file-label">Choose file...</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="col-md-12">
                            <div class="form-group">
                                <label><b>Icon</b></label>

                                <input type="text" class="form-control form-control-sm" name="icon" value="{{old('icon', $category->icon)}}">
                                <p class="mb-0">Find the icon from <a href="https://fontawesome.com/search?o=r&m=free" target="_blank">Font Awesome</a></p>
                            </div>
                        </div> --}}

                        <div class="col-md-12">
                            <div class="form-group">
                                <label><b>Meta description</b></label>

                                <input type="text" class="form-control form-control-sm" name="meta_description" value="{{old('meta_description') ?? $category->meta_description}}">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label><b>Meta tags</b></label>

                                <input type="text" class="form-control form-control-sm" name="meta_tags" value="{{old('meta_tags') ?? $category->meta_tags}}">
                            </div>
                        </div>

                        {{-- @if($category->feature)
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><b>Feature Position</b></label>

                                <select name="feature_position" class="form-control form-control-sm">
                                    <option value="">Select fiture position</option>
                                    <option value="1" {{$category->feature_position == 1 ? 'selected' : ''}}>1</option>
                                    <option value="2" {{$category->feature_position == 2 ? 'selected' : ''}}>2</option>
                                    <option value="3" {{$category->feature_position == 23 ? 'selected' : ''}}>3</option>
                                </select>
                            </div>
                        </div>
                        @endif --}}
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-success">Update</button>

                    <a href="{{route('back.categories.delete', $category->id)}}" onclick="return confirm('Are you sure to remove?')" class="btn btn-danger">Delete</a>
                    <br>
                    <small><b>NB: *</b> marked are required field.</small>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@section('footer')
    <!-- CK Editor -->
    <script src="{{asset('back/ckeditor4-4.15.1/ckeditor.js')}}"></script>

    <script>
        // CKEditor
        $(function () {
            CKEDITOR.replace('editor', {
                height: 400
            });
        });
    </script>
@endsection
