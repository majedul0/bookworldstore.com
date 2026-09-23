<input type="hidden" name="slider" value="{{$slider->id}}">

<div class="form-group">
    <label><b>Text*</b></label>
    <input type="text" class="form-control form-control-sm" name="text" value="{{$slider->text}}" required>
</div>
<div class="form-group">
    <label><b>URL*</b></label>
    <input type="url" class="form-control form-control-sm" name="url" value="{{$slider->url}}" required>
</div>
