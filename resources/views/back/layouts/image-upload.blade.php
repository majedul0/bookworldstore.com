<script>
    upload_required = {{$required ? 1 : 0}};
</script>

<div class="text-center img_group">
    <div class="upload_image_group">
        <img class="img-thumbnail uploaded_img" style="width: 70%" src="{{$image['medium'] ?? asset('img/default-img.png')}}">

        <div class="form-group">
            <label><b>Image{{$required ? '*' : ''}}</b></label>
            <div class="custom-file text-left">
                <input type="file" class="custom-file-input image_upload" name="image" accept="image/*" {{$required ? 'required' : ''}}>
                <label class="custom-file-label">Choose file...</label>
            </div>
        </div>
    </div>

    <div class="choice_image_group" style="display: none">
        <a href="#" class="d-block show_gallery_btn" data-type="new" data-toggle="modal" data-target="#mediaModal">
            <img class="img-thumbnail selected_img" style="width: 70%" src="{{asset('img/default-img.png')}}">
        </a>
        <label><b>Image{{$required ? '*' : ''}}</b></label>
        <input type="hidden" name="media_id" class="media_id">

        <div class="form-group">
            <p>Or <a href="#" class="upload_image_btn">Upload Image</a></p>
        </div>
    </div>
</div>
