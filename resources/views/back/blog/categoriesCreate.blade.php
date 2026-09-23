@extends('back.layouts.master')
@section('title', 'Create Category')

@section('master')
<form action="{{route('back.blogs.categories.store')}}" id="dynamicForm" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="for" value="blog">

    <div class="row">
        <div class="col-md-8">
            <div class="card border-light mt-3 shadow-sm">
                <div class="card-header no_icon">
                    <a href="{{route('back.blogs.categories')}}" class="btn btn-primary btn-sm"><i class="fas fa-angle-double-left"></i> View All</a>
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
                        <label><b>Description</b></label>

                        <textarea id="editor" class="form-control form-control-sm" name="description" cols="30" rows="3">{{old('description')}}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-light mt-3 shadow-sm">
                <div class="card-body">
                    <div class="text-center">
                        <div class="img_group">
                            <img class="img-thumbnail uploaded_img" src="{{asset('img/default-img.png')}}">

                            <div class="form-group text-center">
                                <label><b>Category Image</b></label>
                                <div class="custom-file text-left">
                                    <input type="file" class="custom-file-input image_upload" name="image">
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
