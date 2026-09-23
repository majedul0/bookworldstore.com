@extends('back.layouts.master')
@section('title', 'Frontend menu')

@section('head')
  <link rel="stylesheet" href="{{asset('back/css/nestable.css')}}">

  <style>
    .edit_item_btn{position: absolute;
    top: 5px;
    right: 26px;}
    .edit_item_btn:hover{cursor: pointer;}
  </style>
@endsection

@section('master')
    <div class="row">
        <div class="col-md-4">
            <div class="card border-light mt-3 shadow">
                <div class="card-header">
                    <h6 class="d-inline-block">Select Menu</h6>
                </div>

                <form action="{{route('back.menus.index')}}" method="GET">
                    <div class="card-body">
                        <div class="form-group">
                            <select name="menu" class="form-control form-control-sm">
                                <option value="" selected>--Select Menu--</option>
                                @foreach ($menus as $menu)
                                    <option value="{{$menu->id}}" {{request('menu') == $menu->id ? 'selected' : ''}}>{{$menu->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button class="btn btn-success btn-sm">Select menu</button>
                    </div>
                </form>
            </div>

            <div class="card border-light mt-3 shadow">
                <div class="card-header">
                    <h6 class="d-inline-block">Add Menu Item</h6>
                </div>

                <div class="card-body">
                    <div class="accordion accordionFrontMenu" id="accordionFrontMenu">
                        <div class="card">
                          <div class="card-header no_icon" id="headingOne">
                            <h2 class="mb-0">
                              <button class="btn btn-block text-left btn-sm" type="button" data-toggle="collapse" data-target="#collapseFMPages" aria-expanded="true" aria-controls="collapseFMPages">
                                <b>Pages</b>
                              </button>
                            </h2>
                          </div>

                          <div id="collapseFMPages" class="collapse show" aria-labelledby="headingFMPages" data-parent="#accordionFrontMenu">
                            <form action="{{route('back.menus.storeMenuItem')}}" method="POST">
                                @csrf
                                <input type="hidden" name="relation_with" value="page">
                                <input type="hidden" name="menu_id" value="{{request('menu')}}">

                                <div class="card-body">
                                    <div class="form-group select_box category_select_box">
                                        @if(count($pages))
                                            @foreach ($pages as $page)
                                                <div class="custom-control custom-checkbox mr-sm-2">
                                                    <input name="relation_id[]" value="{{$page->id}}" type="checkbox" class="custom-control-input" id="page_id_{{$page->id}}">
                                                    <label class="custom-control-label" for="page_id_{{$page->id}}">{{$page->title}}</label>
                                                </div>
                                            @endforeach
                                        @else
                                            <p>No page created</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button class="btn btn-success btn-sm">Add to Menu <i class="fas fa-arrow-right"></i></button>
                                </div>
                            </form>
                          </div>
                        </div>

                        <div class="card">
                          <div class="card-header no_icon" id="headingProductCategory">
                            <h2 class="mb-0">
                              <button class="btn btn-block btn-sm text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseProductCategory" aria-expanded="false" aria-controls="collapseProductCategory">
                                <b>Product Categories</b>
                              </button>
                            </h2>
                          </div>
                          <div id="collapseProductCategory" class="collapse" aria-labelledby="headingProductCategory" data-parent="#accordionFrontMenu">
                            <form action="{{route('back.menus.storeMenuItem')}}" method="POST">
                                @csrf
                                <input type="hidden" name="relation_with" value="category">
                                <input type="hidden" name="menu_id" value="{{request('menu')}}">

                                <div class="card-body">
                                    <div class="form-group select_box category_select_box">
                                        @if(count($product_categories))
                                            @foreach ($product_categories as $product_category)
                                                <div class="custom-control custom-checkbox mr-sm-2">
                                                    <input name="relation_id[]" value="{{$product_category->id}}" type="checkbox" class="custom-control-input" id="category_id_{{$product_category->id}}">
                                                    <label class="custom-control-label" for="category_id_{{$product_category->id}}">{{$product_category->title}}</label>
                                                </div>

                                                @foreach ($product_category->Categories as $sub_category)
                                                    <div class="custom-control custom-checkbox mr-sm-2">
                                                        <input name="relation_id[]" value="{{$sub_category->id}}" type="checkbox" class="custom-control-input" id="category_id_{{$sub_category->id}}">
                                                        <label class="custom-control-label" for="category_id_{{$sub_category->id}}">--{{$sub_category->title}}</label>
                                                    </div>

                                                    @foreach ($sub_category->Categories as $sub_sub_category)
                                                        <div class="custom-control custom-checkbox mr-sm-2">
                                                            <input name="relation_id[]" value="{{$sub_sub_category->id}}" type="checkbox" class="custom-control-input" id="category_id_{{$sub_sub_category->id}}">
                                                            <label class="custom-control-label" for="category_id_{{$sub_sub_category->id}}">----{{$sub_sub_category->title}}</label>
                                                        </div>
                                                    @endforeach
                                                @endforeach
                                            @endforeach
                                        @else
                                            <p>No category created</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button class="btn btn-success btn-sm">Add to Menu <i class="fas fa-arrow-right"></i></button>
                                </div>
                            </form>
                          </div>
                        </div>

                        <div class="card">
                          <div class="card-header no_icon" id="headingBrand">
                            <h2 class="mb-0">
                              <button class="btn btn-block btn-sm text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseBrand" aria-expanded="false" aria-controls="collapseBrand">
                                <b>Brands</b>
                              </button>
                            </h2>
                          </div>
                          <div id="collapseBrand" class="collapse" aria-labelledby="headingBrand" data-parent="#accordionFrontMenu">
                            <form action="{{route('back.menus.storeMenuItem')}}" method="POST">
                                @csrf
                                <input type="hidden" name="relation_with" value="brand">
                                <input type="hidden" name="menu_id" value="{{request('menu')}}">

                                <div class="card-body">
                                    <div class="form-group select_box category_select_box">
                                        @if(count($brands))
                                            @foreach ($brands as $brand)
                                                <div class="custom-control custom-checkbox mr-sm-2">
                                                    <input name="relation_id[]" value="{{$brand->id}}" type="checkbox" class="custom-control-input" id="brand_id_{{$brand->id}}">
                                                    <label class="custom-control-label" for="brand_id_{{$brand->id}}">{{$brand->title}}</label>
                                                </div>
                                            @endforeach
                                        @else
                                            <p>No brand created</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button class="btn btn-success btn-sm">Add to Menu <i class="fas fa-arrow-right"></i></button>
                                </div>
                            </form>
                          </div>
                        </div>

                        <div class="card">
                            <div class="card-header no_icon" id="headingFMCustom">
                                <h2 class="mb-0">
                                    <button class="btn btn-sm btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseFMCustom" aria-expanded="false" aria-controls="collapseFMCustom">
                                        <b>Custom Link</b>
                                    </button>
                                </h2>
                            </div>

                            <div id="collapseFMCustom" class="collapse" aria-labelledby="headingFMCustom" data-parent="#accordionFrontMenu">
                              <form action="{{route('back.menus.storeMenuItem')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="relation_with" value="custom_link">
                                    <input type="hidden" name="menu_id" value="{{request('menu')}}">

                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>URL</label>
                                            <input type="text" name="url" class="form-control form-control-sm" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Link text</label>
                                            <input type="text" name="text" class="form-control form-control-sm" required>
                                        </div>
                                    </div>

                                    <div class="card-footer">
                                        <button class="btn btn-success btn-sm">Add to Menu <i class="fas fa-arrow-right"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-light mt-3 shadow">
                <div class="card-header no_icon">
                    {{-- <h6 class="d-inline-block">Menu Items</h6> --}}

                    <form action="{{route('back.menus.store')}}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="menu_name" class="form-control form-control-sm" placeholder="Menu Name">
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-success btn-sm">Create menu</button>
                            </div>
                        </div>
                    </form>
                </div>

                <form action="{{route('back.menus.menuItemPosition')}}" method="POST">
                    @csrf

                    <div class="card-body">
                        @if(request('menu'))
                            <input id="nestable-output" name="menu_item_json" type="hidden">

                            <div class="dd" id="nestable">
                                <ol class="dd-list">
                                    @foreach ($menu_items as $menu_item)
                                        <li class="dd-item" data-id="{{$menu_item->id}}">
                                            <div class="dd-handle">{{$menu_item->menu_info['text'] ?? ''}} </div>

                                            @if(!$menu_item->relation_with && $menu_item->url)
                                            <span class="text-success edit_item_btn" data-id="{{$menu_item->id}}"><i class="fas fa-edit"></i></span>
                                            @endif

                                            <a href="{{route('back.menus.destroyItem', $menu_item->id)}}" onclick="return confirm('Are you sure to remove?');"><i class="fas fa-trash text-danger float-right"></i></a>

                                            @if (count($menu_item->Items))
                                                <ol class="dd-list">
                                                    @foreach ($menu_item->Items as $menu_item)
                                                        <li class="dd-item" data-id="{{$menu_item->id}}">
                                                            <div class="dd-handle">{{$menu_item->menu_info['text'] ?? ''}}</div>

                                                            <a href="{{route('back.menus.destroyItem', $menu_item->id)}}" onclick="return confirm('Are you sure to remove?');"><i class="fas fa-trash text-danger float-right"></i></a>

                                                            @if(!$menu_item->relation_with && $menu_item->url)
                                                            <span class="text-success edit_item_btn" data-id="{{$menu_item->id}}"><i class="fas fa-edit"></i></span>
                                                            @endif

                                                            @if (count($menu_item->Items))
                                                                <ol class="dd-list">
                                                                    @foreach ($menu_item->Items as $menu_item)
                                                                        <li class="dd-item" data-id="{{$menu_item->id}}">
                                                                            <div class="dd-handle">{{$menu_item->menu_info['text'] ?? ''}}</div>

                                                                            <a href="{{route('back.menus.destroyItem', $menu_item->id)}}" onclick="return confirm('Are you sure to remove?');"><i class="fas fa-trash text-danger float-right"></i></a>

                                                                            @if(!$menu_item->relation_with && $menu_item->url)
                                                                            <span class="text-success edit_item_btn" data-id="{{$menu_item->id}}"><i class="fas fa-edit"></i></span>
                                                                            @endif

                                                                            @if (count($menu_item->Items))
                                                                                <ol class="dd-list">
                                                                                    @foreach ($menu_item->Items as $menu_item)
                                                                                        <li class="dd-item" data-id="{{$menu_item->id}}">
                                                                                            <div class="dd-handle">{{$menu_item->menu_info['text'] ?? ''}}</div>

                                                                                            <a href="{{route('back.menus.destroyItem', $menu_item->id)}}" onclick="return confirm('Are you sure to remove?');"><i class="fas fa-trash text-danger float-right"></i></a>

                                                                                            @if(!$menu_item->relation_with && $menu_item->url)
                                                                                            <span class="text-success edit_item_btn" data-id="{{$menu_item->id}}"><i class="fas fa-edit"></i></span>
                                                                                            @endif

                                                                                            @if (count($menu_item->Items))
                                                                                                <ol class="dd-list">
                                                                                                    @foreach ($menu_item->Items as $menu_item)
                                                                                                        <li class="dd-item" data-id="{{$menu_item->id}}">
                                                                                                            <div class="dd-handle">{{$menu_item->menu_info['text'] ?? ''}}</div>

                                                                                                            <a href="{{route('back.menus.destroyItem', $menu_item->id)}}" onclick="return confirm('Are you sure to remove?');"><i class="fas fa-trash text-danger float-right"></i></a>

                                                                                                            @if(!$menu_item->relation_with && $menu_item->url)
                                                                                                            <span class="text-success edit_item_btn" data-id="{{$menu_item->id}}"><i class="fas fa-edit"></i></span>
                                                                                                            @endif
                                                                                                        </li>
                                                                                                    @endforeach
                                                                                                </ol>
                                                                                            @endif
                                                                                        </li>
                                                                                    @endforeach
                                                                                </ol>
                                                                            @endif
                                                                        </li>
                                                                    @endforeach
                                                                </ol>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ol>
                                            @endif
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                        @else
                            <p>Please select a menu!</p>
                        @endif
                    </div>

                    @if(request('menu'))
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-6">
                                    <a href="{{route('back.menus.destroy', $menu->id)}}" onclick="return confirm('Are you sure to remove?');" class="btn btn-danger btn-sm">Delete</a>
                                </div>

                                <div class="col-6 text-right">
                                    <button class="btn btn-success btn-sm">Save Menu</button>
                                </div>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Item Modal -->
    <div class="modal fade" id="editItemModal" tabindex="-1" role="dialog" aria-labelledby="editItemModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editItemModalLabel">Edit Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('back.menus.updateItem')}}" method="POST">
                    @csrf

                    <div class="modal-body">
                        <input type="hidden" class="menu_item_id" name="item_id">
                        <div class="form-group">
                            <label>URL</label>
                            <input type="text" name="url" class="form-control form-control-sm url_input" required>
                        </div>
                        <div class="form-group">
                            <label>Link Text</label>
                            <input type="text" name="text" class="form-control form-control-sm link_text_input" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success update_menu_item">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script src="{{asset('back/js/jquery.nestable.js')}}"></script>

    <script>
        $(document).ready(function() {
            var updateOutput = function(e) {
                var list   = e.length ? e : $(e.target),
                    output = list.data('output');

                if (window.JSON) {
                    output.val(window.JSON.stringify(list.nestable('serialize')));
                }
            };

            // activate Nestable for list 1
            $('#nestable').nestable({
                group: 1
            })
            .on('change', updateOutput);


            // output initial serialised data to textarea
            updateOutput(
                $('#nestable').data('output',
                $('#nestable-output'))
            );
        });

        // Edit Menu Item
        $(document).on('click', '.edit_item_btn', function(){
            let item_id = $(this).data('id');
            cLoader();

            $.ajax({
                url: '{{route("back.menus.editItemAjax")}}',
                method: 'POST',
                data: {item_id, _token: '{{csrf_token()}}'},
                dataType: 'JSON',
                success: function(result){
                    if(result.status){
                        $('.url_input').val(result.data.url);
                        $('.link_text_input').val(result.data.text);
                        $('.menu_item_id').val(result.data.id);
                        $('#editItemModal').modal('show');
                    }else{
                        cAlert();
                    }
                    cLoader('h');
                },
                error: function(){
                    cLoader('h');
                    cAlert();
                }
            });
        });
    </script>
@endsection
