@extends('back.layouts.master')
@section('title', 'Edit Widget')

@section('master')
<div class="card-header no_icon">
    <a href="{{route('back.footer-widgets.index')}}" class="btn btn-success btn-sm"><i class="fas fa-angle-double-left"></i> Back</a>
    <form class="d-inline-block" action="{{route('back.footer-widgets.destroy', $widget->id)}}" method="POST">
        @method('DELETE')
        @csrf

        <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Are you sure to remove?')"><i class="fas fa-trash"></i> Delete</button>
    </form>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card border-light mt-3 shadow">
            <form action="{{route('back.footer-widgets.update', $widget->id)}}" method="POST">
                @csrf
                @method('PATCH')

                <div class="card-body">
                    <div class="form-group">
                        <label><b>Widget Title*</b></label>
                        <input type="text" class="form-control form-control-sm" name="title" value="{{old('title') ?? $widget->title}}" required>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label><b>Widget Type*</b></label>

                                <select name="type" class="form-control form-control-sm type_input" required>
                                    <option value="Text" {{$widget->type == 'Text' ? 'selected' : ''}}>Text</option>
                                    <option value="Menu" {{$widget->type == 'Menu' ? 'selected' : ''}}>Menu</option>
                                    <option value="Social Links" {{$widget->type == 'Social Links' ? 'selected' : ''}}>Social Links</option>
                                    <option value="Info" {{$widget->type == 'Info' ? 'selected' : ''}}>Info</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label><b>Position*</b></label>

                                <input type="number" class="form-control form-control-sm" name="position" value="{{old('position') ?? $widget->position}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group menu_input" style="{{$widget->type == 'Menu' ? '' : 'display: none'}}">
                        <label><b>Select Menu*</b></label>

                        <select name="menu" class="form-control form-control-sm" {{$widget->type == 'Menu' ? 'required' : ''}}>
                            <option value="">Select Menu</option>
                            @foreach ($menus as $menu)
                                <option value="{{$menu->id}}" {{$widget->menu_id == $menu->id ? 'selected' : ''}}>{{$menu->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group text_input" style="{{$widget->type == 'Menu' ? 'display: none' : ''}}">
                        <label><b>Text*</b></label>

                        <textarea name="text" cols="30" rows="5" class="form-control form-control-sm" {{$widget->type == 'Text' ? 'required' : ''}}>{{old('text') ?? $widget->text}}</textarea>
                    </div>
                </div>

                <div class="card-footer">
                    <button class="btn btn-success">Update</button>
                    <br>
                    <small><b>NB: *</b> marked are required field.</small>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script>
    $(document).on('change', '.type_input', function(){
        let type = $(this).val();
        if(type == "Menu"){
            $('.menu_input').show();
            $('.menu_input').find('select').attr('required', 'required');

            $('.text_input').hide();
            $('.text_input').find('textarea').removeAttr('required', 'required');
        }else{
            $('.menu_input').hide();
            $('.menu_input').find('select').removeAttr('required', 'required');

            $('.text_input').show();
            $('.text_input').find('textarea').attr('required', 'required');
        }
    });
</script>
@endsection
