@foreach ($items as $item)
<div class="col-md-2">
    <img src="{{$item->paths['small']}}" class="media_gallery select_media_image" data-image="{{$item->paths['medium']}}" data-id="{{$item->id}}">
</div>
@endforeach
