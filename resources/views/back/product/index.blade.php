@extends('back.layouts.master')
@section('title', 'Products')

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.css"/>
@endsection

@section('master')
<div class="card border-light mt-3 shadow">
    <div class="card-header">
        <h5 class="d-inline-block">Product list</h5>
        <input type="number" class="form-control form-control-sm d-inline-block ml-5 search_id" style="width: 200px" placeholder="Search by Only ID">

        <a href="{{route('back.products.create')}}" class="btn btn-success btn-sm float-right"><i class="fas fa-plus"></i> Create new</a>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-sm" id="dataTable">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Image</th>
                <th scope="col">Type</th>
                <th scope="col">Regular Price</th>
                <th scope="col">Sale Price</th>
                <th scope="col">Available Stock</th>
                <th scope="col">Featured</th>
                <th scope="col">Status</th>
                <th scope="col" class="text-right">Action</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('footer')
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.js"></script>

<script>
    function getDataTable(id_search = ''){
        $('#dataTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{route('back.products.table')}}",
                "dataType": "json",
                "type": "POST",
                "data": {_token: "{{csrf_token()}}", id_search}
            },
            "columns": [
                {"data": "id"},
                {"data": "name"},
                {"data": "image"},
                {"data": "type"},
                {"data": "regular_price"},
                {"data": "sale_price"},
                {"data": "stock"},
                {"data": "featured"},
                {"data": "status"},
                {"data": "action"}
            ],
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "order": [[0, "desc"]],
            "columnDefs": [
                { orderable: true, className: 'reorder', targets: [0] },
                { orderable: false, targets: '_all' }
            ]
        });
    }
    getDataTable();

    $(document).on('change', '.c_switcher_switch input', function(){
        cLoader();
        let id = $(this).data('id');

        $.ajax({
            url: "{{route('back.products.changeFeatured')}}",
            method: 'POST',
            data: {_token: "{{csrf_token()}}", id},
            success: function(){
                cLoader('h');
                cAlert('success', 'Featured updated successful.');
            },
            error: function(){
                cLoader('h');
                cAlert('error', 'Something wrong!');
            },
        });
    });

    // Search ID
    $(document).on('keyup', '.search_id', function(){
        let search_id = $(this).val();
        $('#dataTable').DataTable().destroy();
        getDataTable(search_id);
    });


    // duplicate product start


    // Duplicate Product - Use event delegation to bind click event
$(document).on('click', '.duplicate-btn', function (event) {
    event.preventDefault(); // Prevent default action

    let productId = $(this).data('id'); // Get product ID

    if (confirm("Are you sure you want to duplicate this item?")) {
        $.ajax({
            url: "{{route('back.products.duplicate_product')}}",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                id:productId
            },
            success: function (response) {
                if (response.success) {
                    cAlert('success', response.message);
                    $('#dataTable').DataTable().ajax.reload(); // Reload DataTable to show the new product
                } else {
                    $('#dataTable').DataTable().ajax.reload();
                }
            },
            error: function (xhr, status, error) {
                $('#dataTable').DataTable().ajax.reload();
                cAlert('error', 'Something wrong!');
            }
        });
    }
});



    // duplicate product end
</script>
@endsection
