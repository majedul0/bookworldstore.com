@extends('back.layouts.master')
@section('title', 'Slider')

@section('master')
<div class="row">
    <div class="col-md-8">
        <div class="card border-light mt-3 shadow">
            <div class="card-header">
                <h5>Slider List</h5>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Text</th>
                            <th>URL</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($sliders as $key => $slider)
                            <tr>
                                <td>{{$key + 1}}</td>
                                <td>{{$slider->text}}</td>
                                <td>{{$slider->url}}</td>
                                <td class="text-right">
                                    <div class="d-inline-block" style="width: 80px">
                                        <button class="btn btn-sm btn-success edit_slider" data-id="{{$slider->id}}" type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal"><i class="fas fa-edit"></i></button>

                                        <form class="d-inline-block" action="{{route('back.text-sliders.destroy', $slider->id)}}" method="POST">
                                            @method('DELETE')
                                            @csrf

                                            <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('Are you sure to remove?')"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{route('back.text-sliders.updateAjax')}}" method="POST">
                    @csrf

                    <div class="modal-body edit_slider_form">

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <form action="{{route('back.text-sliders.store')}}" method="POST" id="productForm">
            @csrf

            <div class="card border-light mt-3 shadow">
                <div class="card-header">
                    <h5>Create</h5>
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <label><b>Text*</b></label>
                        <input type="text" class="form-control form-control-sm" name="text" value="{{old('text')}}" required>
                    </div>
                    <div class="form-group">
                        <label><b>URL*</b></label>
                        <input type="url" class="form-control form-control-sm" name="url" value="{{old('url')}}" required>
                    </div>
                </div>

                <div class="card-footer">
                    <button class="btn btn-success">Create</button>
                    <br>
                    <small><b>NB: *</b> marked are required field.</small>
                </div>
            </div>
        </form>

        <form action="{{route('back.text-sliders.position')}}" method="post">
            @csrf

            <div class="card border-light mt-3 shadow">
                <div class="card-header">
                    <h5>Change Position</h5>
                </div>

                <div class="card-body">
                    <ul class="moveContent npnls">
                        @foreach($sliders as $slider)
                            <li class="{{$slider->id}}">
                                <i class="fa fa-arrows-alt"></i>
                                {{$slider->text}}

                                <input type="hidden" name="position[]" value="{{$slider->id}}">
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="card-footer">
                    <button class="btn btn-success">Update position</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('footer')
    <script src="{{asset('back/js/jquery-sortable.js')}}"></script>
    <script>
        $(function () {
            $(".moveContent").sortable();
        });

        $(document).on('click', '.edit_slider', function(){
            let id = $(this).data('id');
            cLoader();

            $.ajax({
                url: '{{route("back.text-sliders.editAjax")}}',
                method: 'POST',
                data: {id, _token: '{{csrf_token()}}'},
                success: function(result){
                    cLoader('hide');

                    $('.edit_slider_form').html(result);
                },
                error: function(){
                    cLoader('hide');
                }
            });
        })
    </script>
@endsection
