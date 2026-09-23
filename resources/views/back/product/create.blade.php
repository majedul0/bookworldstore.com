@extends('back.layouts.master')
@section('title', 'Create Product')

@section('head')
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Select 2 -->
    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">

    <link rel="stylesheet" href="{{asset('back/css/dropzone.css')}}">
@endsection

@section('master')
<form action="{{route('back.products.store')}}" method="POST" enctype="multipart/form-data" id="productForm">
    @csrf

    <div class="row">
        <div class="col-md-8">
            <div class="card border-light mt-3 shadow">
                <div class="card-header no_icon">
                    <a href="{{route('back.products.index')}}" class="btn btn-success btn-sm"><i class="fas fa-angle-double-left"></i> View All</a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><b>Product Title*</b></label>
                                <input type="text" class="form-control form-control-sm" name="title" value="{{old('title')}}" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><b>Short Description</b></label>
                                <textarea class="form-control form-control-sm" name="short_description" id="short_description" cols="30" rows="4">{{old('short_description')}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><b>Description*</b></label>

                                <textarea id="editor" class="form-control" name="description" cols="30" rows="3" required>{{old('description')}}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="form-group">
                        <label><b>Youtube Video Embed Code</b></label>

                        <textarea name="youtube_video_embed" id="form-control form-control-sm" class="form-control" cols="30" rows="6">{{old('youtube_video_embed')}}</textarea>
                    </div> --}}
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="card border-light mt-3 shadow">
                        <div class="card-header">
                            <h6 class="d-inline-block">Product type*</h6>
                        </div>

                        <div class="card-body">
                            <div class="form-group">
                                <select name="type" class="form-control form-control-sm d-inline-block product_type" name="type" required>
                                    <option value="Simple">Simple</option>
                                    {{-- <option value="Bundle">Bundle</option> --}}
                                    <option value="Variable">Variable</option>
                                </select>
                            </div>

                            <div class="variable_attributes" style="display:none">
                                <label>Variable attribute</label>
                                <div class="form-group select_box category_select_box">
                                    @foreach ($attributes as $attribute)
                                        <div class="custom-control custom-checkbox mr-sm-2" id="attributeId_3">
                                            <input name="variable_attributes[]" value="{{$attribute->id}}" type="checkbox" class="custom-control-input variable_attribute_vals" id="attribute_{{$attribute->id}}">
                                            <label class="custom-control-label" for="attribute_{{$attribute->id}}">{{$attribute->name}}</label>
                                        </div>
                                    @endforeach
                                </div>

                                <button class="btn btn-sm btn-success apply_attribute" type="button">Add Attributes</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="card border-light mt-3 shadow">
                        <div class="card-header">
                            <h6 class="d-inline-block">Product Data</h6>
                        </div>
                        <div class="card-body">
                            <div class="general_information">
                                @include('back.product.extra.info-input', [
                                    'type' => 'simple'
                                ])
                            </div>

                            <div class="product_variable_items" style="display: none">

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-light mt-3 shadow bindle_wrap" style="display: none">
                <div class="card-header">
                    <h6 class="d-inline-block">Bundle Product Items</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><b>Select Product(Only Simple Product)</b></label>
                                <select class="form-control form-control-sm selectpicker_products" style="width: 100%">
                                    <option value="">Select Product</option>

                                    {{-- @foreach ($products as $product)
                                        <option value="{{$product->id}}">{{$product->title}}</option>
                                    @endforeach --}}
                                </select>
                            </div>

                            <button class="btn btn-info btn-sm add_bundle_product" type="button">Add Product to List</button>
                        </div>
                    </div>

                    <hr>

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Sale Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody class="bundle_listed_items">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-light mt-3 shadow">
                <div class="card-header">
                    <h6 class="d-inline-block">Additional information</h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Product category*</label>
                        <select name="category[]" class="form-control form-control-sm category selectpicker" multiple required>
                            {{-- <option value="">Select category</option> --}}

                            @foreach ($categories as $category)
                                <option value="{{$category->id}}">{{$category->title}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Product sub category</label>
                        <select name="sub_category[]" class="form-control form-control-sm sub_category selectpicker" multiple>
                            <option value="">Select sub category</option>

                            {{-- @foreach ($sub_categories as $sub_category)
                                <option value="{{$sub_category->id}}" {{$sub_category->id == $sub_category_id ? 'selected' : ''}}>{{$sub_category->title}}</option>
                            @endforeach --}}
                        </select>
                    </div>
                    {{-- @if(env('APP_SUB_CATEGORY'))
                    @endif --}}

                    <div class="form-group">
                        <label>Product brand</label>
                        <select name="brand" class="form-control form-control-sm">
                            <option value="">Select brand</option>
                            @foreach ($brands as $brand)
                                <option value="{{$brand->id}}">{{$brand->title}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Additional Attributes</label>
                        <select name="simpl_attributes[]" class="form-control form-control-sm selectpicker" multiple data-live-search="true">
                            <option value="" disabled>Select attribute</option>

                            @foreach ($attributes as $attribute)
                                <option value="{{$attribute->id}}">{{$attribute->name}}</option>
                            @endforeach
                        </select>
                        <p class="mb-0"><small><b>NB:</b> Use this attribute only for keep note. It will not effect on stock!</small></p>
                    </div>

                    <div class="form-group">
                        <label>Related Products</label>
                        <select name="related_products[]" class="form-control form-control-sm selectpicker_related_products" multiple style="width: 100%"></select>
                        <p class="mb-0"><small><b>NB:</b> Keep it empty to show automatic related products by category.</small></p>
                    </div>

                    {{-- <div class="form-group">
                        <label>Custom Label</label>

                        <input type="text" class="form-control form-control-sm" name="custom_label" value="{{old('custom_label')}}">
                    </div> --}}
                </div>
            </div>

            <div class="card border-light mt-3 shadow">
                <div class="card-body">
                    <div class="text-center">
                        <div class="img_group">
                            <img class="img-thumbnail uploaded_img" src="{{asset('img/default-img.png')}}">

                            <div class="form-group text-center">
                                <label><b>Product Image*</b></label>
                                <div class="custom-file text-left">
                                    <input type="file" class="custom-file-input image_upload" name="image" accept="image/*" required="">
                                    <label class="custom-file-label">Choose file...</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-light mt-3 shadow">
                <div class="card-header">
                    <h6 class="d-inline-block">Product gallery</h6>
                </div>
                <div class="card-body">
                    <div class="dropzone">
                        <div class="fallback">
                            <input name="file" accept="image/*" type="file" multiple />
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-light mt-3 shadow">
                <div class="card-header">
                    <h6 class="d-inline-block">SEO Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Meta description</label>

                                <input type="text" class="form-control form-control-sm" name="meta_description" value="{{old('meta_description')}}">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Meta tags</label>

                                <input type="text" class="form-control form-control-sm" name="meta_tags" value="{{old('meta_tags')}}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button class="btn btn-success btn-block create_btn">Create</button>
                    <small><b>NB: *</b> marked are required field.</small>
                </div>
            </div>
        </div>
    </div>
</form>

<div id="preview-template">
    <div class="dz-preview dz-image-preview" id="dz-preview-template">
        <div class="dz-image">
            <img data-dz-thumbnail>
        </div>
      <div class="dz-details">
        <div class="dz-filename"><span data-dz-name></span></div>
        <div class="dz-size" data-dz-size></div>
      </div>
      <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
      <div class="dz-success-mark"><span></span></div>
      <div class="dz-error-mark"><span></span></div>
      <div class="dz-error-message"><span data-dz-errormessage></span></div>
      {{-- <a class="dz-remove" href="#" data-dz-remove="">Remove file</a> --}}
      {{-- <input type="text" placeholder="Title"> --}}
    </div>
</div>
@endsection

@section('footer')
    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>

    <script>
        // Select2
        $('.selectpicker_products').select2({
            placeholder: "Search Product",
            minimumInputLength: 1,
            ajax: {
                url: '{{ route("back.products.selectList") }}?type=Simple',
                dataType: 'json',
                data: function (params) {
                    return {
                        q: $.trim(params.term)
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        // Related products (searchable multi select)
        $('.selectpicker_related_products').select2({
            placeholder: "Search product",
            minimumInputLength: 1,
            ajax: {
                url: '{{ route("back.products.selectList") }}',
                dataType: 'json',
                data: function (params) {
                    return {
                        q: $.trim(params.term)
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    </script>

    <!-- Select 2 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

    <!-- CK Editor -->
    <script src="{{asset('back/ckeditor4-4.15.1/ckeditor.js')}}"></script>

    <!-- dropzone -->
    <script src="{{asset('back/js/dropzone.js')}}"></script>

    <script>
        // CKEditor
        $(function () {
            CKEDITOR.replace('editor', {
                height: 400,
                filebrowserUploadUrl: "{{route('imageUpload')}}?"
            });

            CKEDITOR.replace('short_description', {
                height: 150,
                filebrowserUploadUrl: "{{route('imageUpload')}}?",
                disableNativeSpellChecker : false,
                allowedContent : true,
                toolbar: []
            });
        });

        // Select2
        $('.selectpicker').selectpicker();

        // dropzone
        $(".dropzone").dropzone({
            addRemoveLinks: true,
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            // previewTemplate: document.getElementById('preview-template').innerHTML,
            url: "{{route('back.media.upload')}}",
            success: function(file, response){
                // alert(response);
                // console.log(file.previewElement);
                // file.previewElement.formData.append("name", value);
                $('.dropzone div:last-child').append('<input type="hidden" name="gallery_id[]" value="'+ response.success.media_id +'">');
                // file.previewElement.id = 'input_id_' + response.success.media_id;
                // file.previewElement.formData('dfgfdg', 'dfgfdfg');
            }
        });

        // Switch types
        $(document).on('change', '.product_type', function(){
            let product_type = $(this).val();
            if(product_type == 'Simple' || product_type == 'Bundle'){
                $('.variable_attributes').hide();
                $('.general_information').show();
                $('.product_variable_items').hide();

                // Required validation
                $('.general_information .sale_price').attr('required', '');
                $('.general_information .unit').attr('required', '');
                $('.general_information .unit_amount').attr('required', '');

                // Remove variable boxes
                $('.product_variable_items').html('');
            }else{
                $('.variable_attributes').show();
                $('.general_information').hide();
                $('.product_variable_items').show();

                // Required validation
                $('.general_information .sale_price').removeAttr('required', '');
                $('.general_information .unit').removeAttr('required', '');
                $('.general_information .unit_amount').removeAttr('required', '');
            }

            if(product_type == 'Bundle'){
                $('.bindle_wrap').show();
            }else{
                $('.bindle_wrap').hide();
            }
        });

        // Remove Variable box
        $(document).on('click', '.removeVariationBox', function(){
            $(this).closest('.variationBox').remove();
        });

        // Apply attribute
        $(document).on('click', '.apply_attribute', function(){
            let attributes = $('.variable_attribute_vals:checked').map(function () {
                return $(this).val();
            });

            if(attributes.length == 0){
                cAlert('error', 'Please select some attribute!');
            }else{
                cLoader();
                $.ajax({
                    url: "{{route('back.products.attributeApply')}}",
                    method: "POST",
                    data: {_token: "{{csrf_token()}}", attr_ids: attributes.get()},
                    success: function(result){
                        cLoader('h');
                        $('.product_variable_items').append(result);
                    },
                    error: function(){
                        cLoader('h');
                        cAlert('error', 'Something wrong!');
                    }
                });
            }
        });

        // Attribute selection
        $(document).on('change', '.attribute_selection', function(){
            let attribute_items = $(this).closest('.variationBox').find('.attribute_selection').map(function () {
                return $(this).data('id') + ':' + $(this).val();
            });

            let attribute_item_ids = '';
            $.each(attribute_items, function (index, item) {
                if(item){
                    attribute_item_ids += item + ',';
                }
            });
            $(this).closest('.variationBox').find('.attribute_item_ids').val(attribute_item_ids);
            // console.log(attribute_item_ids);
        });

        // Check empty price
        let empty_variable_price = false;
        $('#productForm').submit(function () {
            let product_type = $('.product_type').val();
            if(product_type == 'Variable'){
                empty_variable_price = false;

                let variable_prices = $('.product_variable_items').find('.sale_price').map(function () {
                    return $(this).val();
                });

                if(variable_prices.length == 0){
                    cAlert('error', 'Please add some variant!');
                    event.preventDefault();
                }

                $.each(variable_prices, function (index, price) {
                    if(price == ''){
                        empty_variable_price = true;
                    }
                });

                if(empty_variable_price){
                    cAlert('error', 'Please input all variable prices.');
                    event.preventDefault();
                }
            }
        });

        $(document).on('click', '.add_bundle_product', function(){
            let product = $('.selectpicker_products').val();

            if(!product){
                cAlert('error', 'Please select a product.');
            }else{
                cLoader();
                $.ajax({
                    url: '{{route("back.products.getBundleItem")}}',
                    method: 'POST',
                    data: {product, _token: "{{csrf_token()}}"},
                    success: function(result){
                        cLoader('hide');
                        $('.bundle_listed_items').append(result);
                    },
                    error: function(){
                        cLoader('hide');
                        cAlert('error', 'Something wrong!')
                    }
                });
            }
        });

        $(document).on('click', '.removeBundle', function(){
            $(this).closest('tr').remove();
        });
    </script>

    <script>
        // Get Sub Category
        $(document).on('change', '.category', function(){
            let categories_id = $(this).val();
            $('select.sub_category').html('<option value="">Select sub category</option>');
            $('select.sub_sub_category').html('<option value="">Select sub sub category</option>');
            $('.selectpicker').selectpicker('refresh');

            if(categories_id.length){
                cLoader();

                $.ajax({
                    url: '{{route("back.categories.getSubOptions")}}',
                    metod: 'POST',
                    data: {_token: '{{csrf_token()}}', categories_id},
                    success: function(result){
                        cLoader('h');

                        $('select.sub_category').html(result);
                        $('.selectpicker').selectpicker('refresh');
                    },
                    error: function(){
                        cLoader('h');
                        cAlert('Something wrong!');
                    }
                });
            }
        });
    </script>
    {{-- @if(env('APP_SUB_CATEGORY'))
    @endif --}}
@endsection
