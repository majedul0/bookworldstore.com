@extends('back.layouts.master')
@section('title', 'Edit Product Category')

@section('master')
<div class="card">
    <div class="card-header no_icon">
        <a href="{{route('back.blogs.categories.destroy', $category->id)}}" class="btn btn-primary btn-sm"><i class="fas fa-angle-double-left"></i> View All</a>
        <a href="{{route('back.blogs.categories.create')}}" class="btn btn-info btn-sm"><i class="fas fa-plus"></i> Create</a>

        <form class="d-inline-block" action="{{route('back.blogs.categories.destroy', $category->id)}}" method="POST">
            @method('DELETE')
            @csrf

            <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('Are you sure to remove?')"><i class="fas fa-trash"></i> Delete</button>
        </form>

        <div class="nav-item dropdown float-right">
            <button type="button" class="btn btn-sm btn-info" id="languageDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-globe-europe"></i> <span class="input_switch_btn">English</span> <i class="fas fa-angle-down"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="languageDropdown">
                <a class="dropdown-item switch_lang_input" href="#" data-lang="en">English</a>
                <a class="dropdown-item switch_lang_input" href="#" data-lang="bn">বাংলা</a>
            </div>
        </div>
    </div>
</div>

<form action="{{route('back.blogs.categories.update', $category->id)}}" id="dynamicForm" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row">
        <div class="col-md-8">
            <div class="card border-light mt-3 shadow">
                <div class="card-body">
                    <div class="form-group">
                        <label><b>Title*</b></label>
                        <input type="text" class="form-control form-control-sm" name="title" value="{{old('title') ?? $category->title}}" required>
                    </div>

                    <div class="form-group">
                        <label><b>Short Description</b></label>

                        <textarea class="form-control form-control-sm" name="short_description" cols="30" rows="3">{{old('short_description') ?? $category->short_description}}</textarea>
                    </div>

                    <div class="form-group">
                        <label><b>Description</b></label>

                        <textarea id="editor" class="form-control form-control-sm" name="description" cols="30" rows="3">{{old('description') ?? $category->description}}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-light mt-3 shadow">
                <div class="card-body">
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

                    <div class="form-group">
                        <label><b>Meta description</b></label>

                        <input type="text" class="form-control form-control-sm" name="meta_description" value="{{old('meta_description') ?? $category->meta_description}}">
                    </div>

                    <div class="form-group">
                        <label><b>Meta tags</b></label>

                        <input type="text" class="form-control form-control-sm" name="meta_tags" value="{{old('meta_tags') ?? $category->meta_tags}}">
                    </div>
                </div>

                <div class="card-footer">
                    <button class="btn btn-primary" type="submit">Update</button>

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
            CKEDITOR.replace('bn_editor', {
                height: 400
            });
        });
    </script>
@endsection
