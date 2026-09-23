@extends('back.layouts.master')
@section('title', 'Add new purchase')

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.css"/>
@endsection

@section('master')

<create-purchase></create-purchase>

{{-- <div class="row">
    <div class="col-md-12">
        <form action="{{route('back.stocks.store')}}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="card border-light mt-3 shadow">
                <div class="card-header">
                    <h6 class="d-inline-block">Listed Product</h6>
                </div>

                <div class="card-body table-responsive">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><b>Date*</b></label>
                                <input type="date" class="form-control form-control-sm" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><b>Reference No</b></label>
                                <input type="text" class="form-control form-control-sm">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label><b>Status*</b></label>
                                <select name="supplier" class="form-control form-control-sm" required>
                                    <option value="Received">Received</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Order">Order</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label><b>Attachments</b></label>
                                <input type="file" class="form-control form-control-sm">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label><b>Supplier*</b></label>
                                <select name="supplier" class="form-control form-control-sm" required>
                                    <option value="">Select supplier</option>
                                    @foreach ($users as $user)
                                        <option value="{{$user->id}}">{{$user->full_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <br>

                    <div class="form-group">
                        <h3>Seatch Product</h3>
                        <input type="text" class="form-control form-control-sm" placeholder="Search by Product name, id, sku code">
                    </div>

                    <table class="table table-bordered table-sm">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Image</th>
                            <th scope="col">Type</th>
                            <th scope="col">Variation</th>
                            <th scope="col">Tax</th>
                            <th scope="col">Discount</th>
                            <th scope="col" style="width: 120px">Unit Cost</th>
                            <th scope="col" style="width: 120px">Quantity</th>
                            <th scope="col" style="width: 120px">Subtotal</th>
                            <th scope="col" class="text-right"><i class="fas fa-trash"></i></th>
                        </tr>
                        </thead>
                        <tbody class="listed_items">
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-md-4">

                        </div>
                    </div>

                    <div class="form-group">
                        <label><b>Note</b></label>
                        <textarea name="" cols="30" rows="5" class="form-control form-control-sm"></textarea>
                    </div>
                </div>

                <div class="card-footer">
                    <button class="btn btn-success">Submit</button>
                    <br>
                    <small><b>NB: *</b> marked are required field.</small>
                </div>
            </div>
        </form>
    </div>
</div> --}}
@endsection

@section('footer')
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.js"></script>

<script>
    $('#dataTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "{{route('back.stocks.productTable')}}",
            "dataType": "json",
            "type": "POST",
            "data": {_token: "{{csrf_token()}}"}
        },
        "columns": [
            {"data": "id"},
            {"data": "name"},
            {"data": "image"},
            {"data": "type"},
            {"data": "action"}
        ],
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "order": [[0, "desc"]],
        "columnDefs": [
            { orderable: true, className: 'reorder', targets: [0] },
            { orderable: false, targets: '_all' }
        ]
    });

    // Add Item
    $(document).on('click', '.add_item', function(){
        let id = $(this).data('id');
        cLoader();

        $.ajax({
            url: '{{route("back.stocks.addItem")}}',
            method: 'POST',
            data: {id, _token: '{{csrf_token()}}'},
            success: function(result){
                cLoader('h');

                $('.listed_items').append(result);
            },
            error: function(){
                cLoader('h');
            }
        });
    });

    // Remove Item
    $(document).on('click', '.remove_item', function(){
        if(confirm('Are you sure to remobe?')){
            $(this).closest('tr').remove();
        }
    });
</script>
@endsection
