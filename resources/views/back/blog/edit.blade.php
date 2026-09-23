@extends('back.layouts.master')
@section('title', 'Edit Blog')

@section('head')
    <!-- Select 2 -->
    <link rel="stylesheet" href="{{asset('back/bootstrap-select/dist/css/bootstrap-select.min.css')}}">
@endsection

@section('master')
<div class="card">
    <div class="card-header no_icon">
        <a href="{{route('back.blogs.index')}}" class="btn btn-primary btn-sm"><i class="fas fa-angle-double-left"></i> View All</a>
        <a href="{{route('back.blogs.create')}}" class="btn btn-info btn-sm"><i class="fas fa-plus"></i> Create</a>

        <form class="d-inline-block" action="{{route('back.blogs.destroy', $blog->id)}}" method="POST">
            @method('DELETE')
            @csrf

            <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('Are you sure to remove?')"><i class="fas fa-trash"></i> Delete</button>
        </form>
    </div>
</div>

<form action="{{route('back.blogs.update', $blog->id)}}" id="dynamicForm" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    <div class="row">
        <div class="col-md-8">
            <div class="card border-light mt-3 shadow">
                <div class="card-body">
                    <div class="form-group">
                        <label><b>Title*</b></label>
                        <input type="text" class="form-control" name="title" value="{{old('title') ?? $blog->title}}" required>
                    </div>
                    <div class="form-group">
                        <label><b>Short Description</b></label>

                        <textarea class="form-control" name="short_description" cols="30" rows="3">{{old('short_description') ?? $blog->short_description}}</textarea>
                    </div>
                    <div class="form-group">
                        <label><b>Description*</b></label>

                        <textarea id="editor" class="form-control" name="description" cols="30" rows="3">{{old('description') ?? $blog->description}}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-light mt-3 shadow">
                <div class="card-body">
                    <div class="form-group">
                        <label>Blog category</label>
                        <select name="categories[]" class="form-control form-control-sm selectpicker" multiple data-live-search="true">
                            <option value="" disabled>Select category</option>

                            @foreach ($categories as $category)
                                <option value="{{$category->id}}" {{in_array($category->id, $blog_categories) ? 'selected' : ''}}>{{$category->title}}</option>

                                @foreach ($category->Categories as $sub_category)
                                    <option value="{{$sub_category->id}}" {{in_array($sub_category->id, $blog_categories) ? 'selected' : ''}}>--{{$sub_category->title}}</option>

                                    @foreach ($sub_category->Categories as $sub_category)
                                        <option value="{{$sub_category->id}}" {{in_array($sub_category->id, $blog_categories) ? 'selected' : ''}}>----{{$sub_category->title}}</option>
                                    @endforeach
                                @endforeach
                            @endforeach
                        </select>
                    </div>

                    <div class="text-center">
                        <div class="img_group">
                            <img class="img-thumbnail uploaded_img" src="{{$blog->img_paths['small']}}">

                            @if($blog->media_id)
                            <a href="{{route('back.blogs.removeImage', $blog->id)}}" onclick="return confirm('Are you sure to remove?');" class="btn btn-sm btn-danger remove_image" title="Remove image"><i class="fas fa-times"></i></a>
                            @endif

                            <div class="form-group text-center">
                                <label><b>Featured image</b></label>
                                <div class="custom-file text-left">
                                    <input type="file" class="custom-file-input image_upload" name="image" accept="image/*">
                                    <label class="custom-file-label">Choose file...</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><b>Meta description</b></label>

                        <input type="text" class="form-control" name="meta_description" value="{{old('meta_description') ?? $blog->meta_description}}">
                    </div>
                    <div class="form-group">
                        <label><b>Meta tags</b></label>

                        <input type="text" class="form-control" name="meta_tags" value="{{old('meta_tags') ?? $blog->meta_tags}}">
                    </div>
                </div>

                <div class="card-footer">
                    <button class="btn btn-primary btn-block" type="submit">Update</button>
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

    <!-- Select 2 -->
    <script src="{{asset('back/bootstrap-select/dist/js/bootstrap-select.min.js')}}"></script>

    <script>
        // CKEditor
        $(function () {
            CKEDITOR.replace('editor', {
                height: 400,
                filebrowserUploadUrl: "{{route('imageUpload')}}?"
            });
            CKEDITOR.replace('bn_editor', {
                height: 400,
                filebrowserUploadUrl: "{{route('imageUpload')}}?"
            });
        });

        // Select2
        $('.selectpicker').selectpicker();
    </script>
@endsection

