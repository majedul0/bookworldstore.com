@extends('back.layouts.master')
@section('title', 'Edit Testimonial')

@section('head')
    <!-- Select 2 -->
    <link rel="stylesheet" href="{{asset('back/bootstrap-select/dist/css/bootstrap-select.min.css')}}">
@endsection

@section('master')
<div class="card">
    <div class="card-header no_icon">
        <a href="{{route('back.testimonials.index')}}" class="btn btn-primary btn-sm"><i class="fas fa-angle-double-left"></i> View All</a>
        <a href="{{route('back.testimonials.create')}}" class="btn btn-info btn-sm"><i class="fas fa-plus"></i> Create</a>

        <form class="d-inline-block" action="{{route('back.testimonials.destroy', $testimonial->id)}}" method="POST">
            @method('DELETE')
            @csrf

            <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('Are you sure to remove?')"><i class="fas fa-trash"></i> Delete</button>
        </form>
    </div>
</div>

<form action="{{route('back.testimonials.update', $testimonial->id)}}" id="dynamicForm" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    <div class="row">
        <div class="col-md-8">
            <div class="card border-light mt-3 shadow">
                <div class="card-body">
                    <div class="form-group">
                        <label><b>Client Name*</b></label>
                        <input type="text" class="form-control form-control-sm" name="name" value="{{old('name', $testimonial->client_name)}}" required>
                    </div>
                    <div class="form-group">
                        <label><b>Client Desiccation*</b></label>
                        <input type="text" class="form-control form-control-sm" name="desiccation" value="{{old('desiccation', $testimonial->client_desiccation)}}" required>
                    </div>
                    <div class="form-group">
                        <label><b>Testimonial*</b></label>

                        <textarea class="form-control form-control-sm" name="testimonial" cols="30" rows="3" required>{{old('testimonial', $testimonial->testimonial)}}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-light mt-3 shadow">
                <div class="card-body">
                    <div class="text-center">
                        <div class="img_group">
                            <img class="img-thumbnail uploaded_img" src="{{$testimonial->img_paths['small']}}">

                            <div class="form-group text-center">
                                <label><b>Client Logo</b></label>
                                <div class="custom-file text-left">
                                    <input type="file" class="custom-file-input image_upload" name="image" accept="image/*">
                                    <label class="custom-file-label">Choose file...</label>
                                </div>
                            </div>
                        </div>
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

