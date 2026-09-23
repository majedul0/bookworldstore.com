@extends('back.layouts.master')
@section('title', 'Footer Widgets')

@section('master')
<div class="row">
    <div class="col-md-8">
        <div class="card border-light mt-3 shadow">
            <div class="card-header">
                <h5 class="d-inline-block">Widget List</h5>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                      <tr>
                        <th scope="col">Position</th>
                        <th scope="col">Title</th>
                        <th scope="col">Widget Type</th>
                        <th scope="col" class="text-right">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($widgets as $key => $widget)
                            <tr>
                                <td scope="row">{{$widget->position}}</td>
                                <td>{{$widget->title}}</td>
                                <td>{{$widget->type}}</td>
                                <td class="text-right">
                                    <a class="btn btn-success btn-sm" href="{{route('back.footer-widgets.edit', $widget->id)}}"><i class="fas fa-edit"></i></a>

                                    <form class="d-inline-block" action="{{route('back.footer-widgets.destroy', $widget->id)}}" method="POST">
                                        @method('DELETE')
                                        @csrf

                                        <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Are you sure to remove?')"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-light mt-3 shadow">
            <div class="card-header">
                <h5 class="d-inline-block">Create Widget</h5>
            </div>
            <form action="{{route('back.footer-widgets.index')}}" method="POST">
                @csrf

                <div class="card-body">
                    <div class="form-group">
                        <label><b>Widget Title*</b></label>
                        <input type="text" class="form-control form-control-sm" name="title" value="{{old('title')}}" required>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label><b>Widget Type*</b></label>

                                <select name="type" class="form-control form-control-sm type_input" required>
                                    <option value="Text">Text</option>
                                    <option value="Menu">Menu</option>
                                    <option value="Social Links">Social Links</option>
                                    <option value="Info">Info</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label><b>Position*</b></label>

                                <input type="number" class="form-control form-control-sm" name="position" value="{{old('position')}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group menu_input" style="display: none">
                        <label><b>Select Menu*</b></label>

                        <select name="menu" class="form-control form-control-sm">
                            <option value="">Select Menu</option>
                            @foreach ($menus as $menu)
                                <option value="{{$menu->id}}">{{$menu->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group text_input">
                        <label><b>Text*</b></label>

                        <textarea name="text" cols="30" rows="5" class="form-control form-control-sm" required>{{old('text')}}</textarea>
                    </div>
                </div>

                <div class="card-footer">
                    <button class="btn btn-success">Create</button>
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
