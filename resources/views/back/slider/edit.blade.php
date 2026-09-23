@extends('back.layouts.master')
@section('title', 'Edit Slider')

@section('master')
<div class="card-header no_icon">
    <a href="{{route('back.sliders.index')}}" class="btn btn-success btn-sm"><i class="fas fa-angle-double-left"></i> Back</a>
    <a href="{{route('back.sliders.delete', $slider->id)}}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to remove?')"><i class="fas fa-trash"></i> Delete</a>
</div>

<form action="{{route('back.sliders.update', $slider->id)}}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    <div class="card border-light mt-3 shadow">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><b>Text*</b></label>
                                <input type="text" class="form-control form-control-sm" name="text_1" value="{{old('text_1') ?? $slider->text_1}}" required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="text-center img_group">
                                <div class="upload_image_group">
                                    <img class="img-thumbnail uploaded_img" style="width: 70%" src="{{$slider->img_paths['medium'] ?? asset('img/default-img.png')}}">

                                    <div class="form-group">
                                        <label><b>Desktop Image</b></label>
                                        <div class="custom-file text-left">
                                            <input type="file" class="custom-file-input image_upload" name="image" accept="image/*">
                                            <label class="custom-file-label">Choose file...</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="text-center img_group">
                                <div class="upload_image_group">
                                    <img class="img-thumbnail uploaded_img" style="width: 70%" src="{{$slider->img_paths2['medium'] ?? asset('img/default-img.png')}}">

                                    <div class="form-group">
                                        <label><b>Mobile Image</b></label>
                                        <div class="custom-file text-left">
                                            <input type="file" class="custom-file-input image_upload" name="mobile_image" accept="image/*">
                                            <label class="custom-file-label">Choose file...</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button class="btn btn-success">Update</button>
            <br>
            <small><b>NB: *</b> marked are required field.</small>
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
                height: 400,
                filebrowserUploadUrl: "{{route('imageUpload')}}?"
            });
        });
    </script>
@endsection
