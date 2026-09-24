@extends('back.layouts.master')
@section('title', 'Create Category')

@section('master')
<form action="{{route('back.categories.store')}}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row">
        <div class="col-md-8">
            <div class="card border-light mt-3 shadow">
                <div class="card-header no_icon">
                    <a href="{{route('back.categories.index')}}" class="btn btn-success btn-sm"><i class="fas fa-angle-double-left"></i> View All</a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><b>Title*</b></label>
                                <input type="text" class="form-control form-control-sm" name="title" value="{{old('title')}}" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><b>Short Description</b></label>

                                <textarea class="form-control form-control-sm" name="short_description" cols="30" rows="3">{{old('short_description')}}</textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label><b>Description</b></label>

                                <textarea id="editor" class="form-control form-control-sm" name="description" cols="30" rows="3">{{old('description')}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-light mt-3 shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="text-center">
                                <div class="img_group">
                                    <img class="img-thumbnail uploaded_img" src="{{asset('img/default-img.png')}}">

                                    <div class="form-group text-center">
                                        <label><b>Category Image</b></label>
                                        <div class="custom-file text-left">
                                            <input type="file" class="custom-file-input image_upload" name="image" accept="image/*">
                                            <label class="custom-file-label">Choose file...</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label><b>Parent category</b></label>

                                <select name="category_id" id="category_id_select" class="form-control form-control-sm">
                                    <option value="">Select parent category</option>

                                    @foreach ($categories as $category)
                                        <option value="{{$category->id}}">{{$category->title}}</option>

                                        @foreach ($category->Categories as $sub_category)
                                            <option value="{{$sub_category->id}}">-- {{$sub_category->title}}</option>

                                            {{-- @foreach ($sub_category->Categories as $sub_category)
                                                <option value="{{$sub_category->id}}">-----{{$sub_category->title}}</option>
                                            @endforeach --}}
                                        @endforeach
                                    @endforeach
                                </select>

                                <div id="existing_subcategories" class="mt-2 small" style="display:none;">
                                    <b>Existing subcategories under this category:</b>
                                    <ul id="existing_subcategories_list" class="mb-0 pl-3"></ul>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="col-md-12">
                            <div class="form-group">
                                <label><b>Icon</b></label>

                                <input type="text" class="form-control form-control-sm" name="icon" value="{{old('icon')}}">
                                <p class="mb-0">Find the icon from <a href="https://fontawesome.com/search?o=r&m=free" target="_blank">Font Awesome</a></p>
                            </div>
                        </div> --}}

                        <div class="col-md-12">
                            <div class="form-group">
                                <label><b>Meta description</b></label>

                                <input type="text" class="form-control form-control-sm" name="meta_description" value="{{old('meta_description')}}">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label><b>Meta tags</b></label>

                                <input type="text" class="form-control form-control-sm" name="meta_tags" value="{{old('meta_tags')}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-success btn-block">Create</button>
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
        });

        // Show existing subcategories of the selected parent category
        var subcategoriesMap = {
            @foreach ($categories as $category)
                {{ $category->id }}: [
                    @foreach ($category->Categories as $sub_category)
                        @json($sub_category->title),
                    @endforeach
                ],
            @endforeach
        };

        $('#category_id_select').on('change', function () {
            var subs = subcategoriesMap[$(this).val()] || [];
            var $box = $('#existing_subcategories');
            var $list = $('#existing_subcategories_list');

            $list.empty();

            if (subs.length) {
                subs.forEach(function (title) {
                    $list.append($('<li>').text(title));
                });
                $box.show();
            } else {
                $box.hide();
            }
        });
    </script>
@endsection
