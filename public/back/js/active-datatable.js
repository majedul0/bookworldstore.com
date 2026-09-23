$(document).ready(function() {
    $('#dataTable').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copy',
                className: 'btn-sm',
                footer: true,
                exportOptions: {
                    columns: export_columns
                }
            },
            {
                extend: 'csv',
                className: 'btn-sm',
                footer: true,
                title: datatable_filename,
                exportOptions: {
                    columns: export_columns,
                    modifier: {
                        search: 'applied',
                        order: 'applied'
                    }
                }
            },
            {
                extend: 'excel',
                footer: true,
                className: 'btn-sm',
                title: datatable_filename,
                exportOptions: {
                    columns: export_columns,
                    modifier: {
                        search: 'applied',
                        order: 'applied'
                    }
                }
            },
            {
                extend: 'pdf',
                footer: true,
                className: 'btn-sm',
                title: datatable_filename,
                exportOptions: {
                    columns: export_columns,
                    modifier: {
                        search: 'applied',
                        order: 'applied'
                    }
                }
            },
            {
                extend: 'print',
                footer: true,
                className: 'btn-sm',
                footer: true,
                exportOptions: {
                    columns: export_columns,
                    modifier: {
                        search: 'applied',
                        order: 'applied'
                    }
                }
            }
        ],
        paging: datatable_paging,
        searching: datatable_searching,
        // ordering: datatable_paging,
        order: datatable_paging ? [[0, datatable_dir]] : [],
    });
});
