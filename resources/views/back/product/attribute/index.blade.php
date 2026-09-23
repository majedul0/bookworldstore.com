@extends('back.layouts.master')
@section('title', 'Attributes')

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.css"/>

<link rel="stylesheet" href="{{asset('back/bootstrap-4-tag-Input/tagsinput.css')}}">

<style>
    table li{padding: 8px 0;
    border-bottom: 1px solid #ddd;}
    .bootstrap-tagsinput .badge{margin: 2px 2px;}
</style>
@endsection

@section('master')
<div class="row">
    <div class="col-md-4">
        <div class="card border-light mt-3 shadow">
            <div class="card-header">
                <h5 class="d-inline-block">Create attribute</h5>
            </div>

            <form action="{{route('back.attributes.store')}}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><b>Name*</b></label>
                                <input type="text" class="form-control form-control-sm" name="name" value="{{old('name')}}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-success btn-sm">Create</button>
                    <br>
                    <small><b>NB: *</b> marked are required field.</small>
                </div>
            </form>
        </div>

        <div class="card border-light mt-3 shadow">
            <div class="card-header">
                <h5 class="d-inline-block">Add Attribute Item</h5>
            </div>

            <form action="{{route('back.attributes.itemStore')}}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card-body">
                    <div class="form-group">
                        <label><b>Select Attribute*</b></label>
                        <select name="attribute" class="form-control form-control-sm" required>
                            <option value="">Select Attribute</option>

                            @foreach ($attributes as $attribute)
                                <option value="{{$attribute->id}}">{{$attribute->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-0">
                        <label><b>Item Name*</b></label>
                        <input type="text" data-role="tagsinput" name="items" value="{{old('items')}}" class="form-control">

                        {{-- <input type="text" class="form-control form-control-sm" name="name" value="{{old('name')}}" required> --}}
                        <p class="mb-0"><b>NB:</b> You can add multiple by Comma(,)</p>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-success btn-sm">Add Item</button>
                    <br>
                    <small><b>NB: *</b> marked are required field.</small>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-8">
        <h4>Attribute Items</h4>
        @foreach ($attributes as $attribute)
        <div class="card mt-3 shadow">
            <div class="card-header">
                <div class="form-group">
                    <label><b>Update Attribute</b></label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm attribute_name" placeholder="Attribute" value="{{$attribute->name}}">
                        <div class="input-group-append">
                          <button class="btn btn-info btn-sm update_attribute" data-id="{{$attribute->id}}" data-toggle="tooltip" data-placement="top" title="Update Attribute" type="button"><i class="fas fa-save"></i></button>
                        </div>
                        <form class="input-group-append" action="{{route('back.attributes.destroy', $attribute->id)}}" method="POST">
                            @method('DELETE')
                            @csrf

                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to remove?')" data-toggle="tooltip" data-placement="top" title="Delete Attribute"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body pl-5">
                <h5><b>Attribute Items</b></h5>
                @foreach ($attribute->AttributeItems as $attribute_item)
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm attribute_item_name" placeholder="Attribute Item" value="{{$attribute_item->name}}">
                        <div class="input-group-append">
                          <button class="btn btn-success btn-sm update_attribute_item" data-id="{{$attribute_item->id}}" data-toggle="tooltip" data-placement="top" title="Update Attribute Item" type="button"><i class="fas fa-save"></i></button>
                        </div>
                        <a class="btn btn-danger btn-sm" href="{{route('back.attributes.itemDestroy', $attribute_item->id)}}" onclick="return confirm('Are you sure to remove?');" data-toggle="tooltip" data-placement="top" title="Delete Attribute Item"><i class="fas fa-trash"></i></a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Edit Modal -->
{{-- <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit attribute</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{route('back.attributes.updateModal')}}" method="POST">
            @csrf
            <input type="hidden" name="id" value="" class="edit_id">

            <div class="modal-body">
                <div class="form-group">
                    <label><b>Name*</b></label>
                    <input type="text" class="form-control form-control-sm edit_name" name="name" value="{{old('name')}}" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Update</button>
            </div>
        </form>
      </div>
    </div>
</div> --}}

<!-- Edit Modal -->
{{-- <div class="modal fade" id="editItemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit attribute item</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{route('back.attributes.itemUpdate')}}" method="POST">
            @csrf
            <input type="hidden" name="id" value="" class="edit_item_id">

            <div class="modal-body">
                <div class="form-group">
                    <label><b>Name*</b></label>
                    <input type="text" class="form-control form-control-sm edit_item_name" name="name" value="{{old('name')}}" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Update</button>
            </div>
        </form>
      </div>
    </div>
</div> --}}
@endsection

@section('footer')
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.js"></script>

<script src="{{asset('back/bootstrap-4-tag-Input/tagsinput.js')}}"></script>

<script>
    $(document).ready( function () {
        $('#dataTable').DataTable({
            order: [[0, "asc"]],
        });
    });

    $(document).on('click', '.edit_btn', function(){
        let name = $(this).data('name');
        let id = $(this).data('id');

        $('.edit_id').val(id);
        $('.edit_name').val(name);
    });

    $(document).on('click', '.edit_item_btn', function(){
        let name = $(this).data('name');
        let id = $(this).data('id');

        $('.edit_item_id').val(id);
        $('.edit_item_name').val(name);
    });

    $(document).on('click', '.update_attribute', function(){
        let id = $(this).data('id');
        let name = $(this).closest('.input-group').find('.attribute_name').val();
        cLoader();

        $.ajax({
            url: '{{route("back.attributes.updateAjax")}}',
            method: 'POST',
            data: {id, name, _token: "{{csrf_token()}}"},
            success: function(result){
                cLoader('hide');
                if(result == 'true'){
                    cAlert('success', 'Attribute updated!');
                }else{
                    cAlert('success', 'Attribute updated failed!');
                }
            },
            error: function(){
                cLoader('hide');
                cAlert('success', 'Attribute updated failed!');
            }
        });
    });

    $(document).on('click', '.update_attribute_item', function(){
        let id = $(this).data('id');
        let name = $(this).closest('.input-group').find('.attribute_item_name').val();
        cLoader();

        $.ajax({
            url: '{{route("back.attributes.updateItemAjax")}}',
            method: 'POST',
            data: {id, name, _token: "{{csrf_token()}}"},
            success: function(result){
                cLoader('hide');
                if(result == 'true'){
                    cAlert('success', 'Attribute item updated!');
                }else{
                    cAlert('success', 'Attribute item updated failed!');
                }
            },
            error: function(){
                cLoader('hide');
                cAlert('success', 'Attribute item updated failed!');
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#color-input').tagsinput();
    });
  </script>
@endsection
