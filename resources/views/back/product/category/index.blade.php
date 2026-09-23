@extends('back.layouts.master')
@section('title', 'Categories')

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.css"/>
<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />

<style>
ul.category{}
ul.category li{position: relative;    display: table;}
ul.category li button{border: none;background: none;position: absolute}
ul.category li button:focus{outline: none}
ul.category li button:hover{color: var(--primary_color)}
ul.category li button i{}
ul.category li a{    padding-right: 10px;}

ul.category li ul{}
ul.category li ul li{}
ul.category li ul li a{}

ul.category li ul li ul{padding-left: 15px;}
ul.category li ul li ul li{}
ul.category li ul li ul li a{}
ul.category li ul li ul li a i{font-size: 14px}

.show_product_btn{
     /* margin-left: 26px; */
     }
.show_product_btn i{
    /* transform: rotate(90deg); */
    transition: .3s}
.show_product_btn.collapsed i{
    /* transform: rotate(0deg) */
}
.addProductBtn{
     /* margin-left: 45px; */
     }

.select2-container{width: 100% !important}
</style>
@endsection

@section('master')

<div class="card border-light mt-3 shadow">
    <div class="card-header">
        <h5 class="d-inline-block">Category list</h5>

        <a href="{{route('back.categories.create')}}" class="btn btn-success btn-sm float-right"><i class="fas fa-plus"></i> Create new</a>
    </div>
    <div class="card-body table-responsive" id="category_collapse">
        <table class="table table-bordered table-sm" id="dataTable">
            <thead>
              <tr>
                {{-- <th scope="col">#</th> --}}
                <th scope="col">Title</th>
                <th scope="col">Sub Categories</th>
                <th scope="col">Feature</th>
                <th scope="col">Status</th>
                <th scope="col">Block</th>
                <th scope="col" class="text-right">Action</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr id="main_category_{{$category->id}}">
                        <td>{{$category->title}}</td>
                        <td>
                            <table class="table table-bordered table-sm">
                                <tbody>
                                    @foreach ($category->Categories as $sub_category)
                                    <tr>
                                        <td><a href="{{route('back.categories.edit', $sub_category->id)}}">{{$sub_category->title}}</a></td>
                                        <td class="text-right">
                                            @include('switcher::switch', [
                                                'table' => 'categories',
                                                'data' => $sub_category
                                            ])
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                        <td>
                            @include('switcher::switch', [
                                'table' => 'categories',
                                'data' => $category,
                                'column' => 'feature'
                            ])
                        </td>
                        <td>
                            @include('switcher::switch', [
                                'table' => 'categories',
                                'data' => $category,
                            ])
                        </td>
                        <td>
                            @include('switcher::switch', [
                                'table' => 'categories',
                                'data' => $category,
                                'column' => 'home_block'
                            ])
                        </td>
                        <td class="text-right">
                            <div class="d-inline-block">
                                <a class="btn btn-success btn-sm" href="{{route('back.categories.edit', $category->id)}}"><i class="fas fa-edit"></i></a>
                                <form class="d-inline-block" action="{{route('back.categories.destroy', $category->id)}}" method="POST">
                                    @method('DELETE')
                                    @csrf

                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to remove?')"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('back.categories.changeParentCategory')}}" method="POST">
                @csrf
                <input type="hidden" name="category" class="category_id_input">

                <div class="modal-body">
                    <div class="form-group">
                        <label>Parent category</label>
                        <select name="parent_category" class="form-control form-control-sm">
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{$category->id}}">{{$category->title}}</option>

                                @foreach ($category->Categories as $sub_category)
                                    <option value="{{$sub_category->id}}">-- {{$sub_category->title}}</option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Change</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('back.categories.changeProductCategory')}}" method="POST">
                @csrf
                <input type="hidden" name="category" class="category_id_input_prodict">

                <div class="text-center mt-4 pb-4">
                    <button class="btn btn-success add_existing_btn" type="button">Add Existing</button>
                    <a href="{{route('back.products.create')}}" class="btn btn-info">Create New</a>
                </div>

                <div class="add_existing_area" style="display: none">
                    <div class="modal-body">
                        <div class="form-group" style="width: 100%">
                            <label class="d-block"><b>Select Product</b></label>
                            <select class="form-control form-control-sm selectpicker_products" name="product" required></select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.js"></script>
<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>

<script>
    $(document).ready( function () {
        $('#dataTable').DataTable({
            order: [[0, "asc"]],
        });
    });

    // Edit Category
    $(document).on('click', '.editCategoryBtn', function(){
        let id = $(this).data('id');

        $('.category_id_input').val(id);
    });

    // Search Product
    $('.selectpicker_products').select2({
        placeholder: "Search Product",
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

    $(document).on('click', '.addProductBtn', function(){
        let id = $(this).data('id');
        $('.category_id_input_prodict').val(id);

        $('.add_existing_area').hide();
    });
    $(document).on('click', '.add_existing_btn', function(){
        $('.add_existing_area').show();
    });
</script>
@endsection
