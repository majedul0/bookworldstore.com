@extends('back.layouts.master')
@section('title', 'Create blog')

@section('head')
    <!-- Select 2 -->
    <link rel="stylesheet" href="{{asset('back/bootstrap-select/dist/css/bootstrap-select.min.css')}}">
@endsection

@section('master')
<form action="{{route('back.blogs.store')}}" id="dynamicForm" method="POST" enctype="multipart/form-data">
@csrf
<div class="row">
    <div class="col-md-8">
        <div class="card border-light mt-3 shadow">
            <div class="card-header no_icon">
                <a href="{{route('back.blogs.index')}}" class="btn btn-primary btn-sm"><i class="fas fa-angle-double-left"></i> View All</a>
            </div>

            <div class="card-body">
                <div class="form-group">
                    <label><b>Title*</b></label>
                    <input type="text" class="form-control form-control-sm" name="title" value="{{old('title')}}" required>
                </div>
                <div class="form-group">
                    <label><b>Short Description</b></label>

                    <textarea class="form-control form-control-sm" name="short_description" cols="30" rows="3">{{old('short_description')}}</textarea>
                </div>
                <div class="form-group">
                    <label><b>Description*</b></label>

                    <textarea id="editor" class="form-control form-control-sm" name="description" cols="30" rows="3">{{old('description')}}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-light mt-3 shadow">
            <div class="card-body">
                <div class="form-group">
                    <label><b>Blog categories</b></label>
                    <select name="categories[]" class="form-control form-control-sm selectpicker" multiple data-live-search="true">
                        <option value="" disabled>Select category</option>

                        @foreach ($categories as $category)
                            <option value="{{$category->id}}">{{$category->title}}</option>

                            @foreach ($category->Categories as $sub_category)
                                <option value="{{$sub_category->id}}">--{{$sub_category->title}}</option>

                                @foreach ($sub_category->Categories as $sub_category)
                                    <option value="{{$sub_category->id}}">----{{$sub_category->title}}</option>
                                @endforeach
                            @endforeach
                        @endforeach
                    </select>
                </div>

                <div class="text-center">
                    <div class="img_group">
                        <img class="img-thumbnail uploaded_img" src="{{asset('img/default-img.png')}}">

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

                    <input type="text" class="form-control form-control-sm" name="meta_description" value="{{old('meta_description')}}">
                </div>
                <div class="form-group">
                    <label><b>Meta tags</b></label>

                    <input type="text" class="form-control form-control-sm" name="meta_tags" value="{{old('meta_tags')}}">
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary btn-block" type="submit">Create</button>
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
