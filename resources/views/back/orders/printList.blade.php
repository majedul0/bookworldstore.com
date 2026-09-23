<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Order List</title>

    <style>
        :root{
            --primary_color: #343A40;
            --primary_print_color: {{$settings_g['primary_color'] ?? '#c04000'}};
            --primary_hover_color: #494E53;
            --secondary_color: #222D32;
            --secondary_hover_color: #1E282C;
            --background_color:#ECF0F5;
        }
      </style>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <script src="https://kit.fontawesome.com/9c65216417.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="{{asset('back/css/style.css')}}">
    <link href="{{asset('back/css/print.css')}}" media="print" rel="stylesheet">

    <style>
        /* Advance Invoice Style */
        .invoice_wrap{font-family: 'Montserrat', sans-serif;}
        .i_header{border-bottom: 20px solid var(--primary_color);}
        .i_logo{}
        .i_logo img{max-width: 100%;
            width: 370px;
            margin-bottom: 15px;}

        .i_company_info{margin-bottom: 30px;}
        .ici_address{}
        .ici_address h2{font-weight: 600;
            font-size: 28px;
            margin-top: 20px;
            border-bottom: 2px solid var(--primary_color);
            display: inline-block;}
        .ici_address p{}

        .ici_order_info{    background: #fff;
            position: absolute;
            top: -43px;
            z-index: 9;
            padding: 5px;}
        .ici_order_info h2{    font-weight: 600;
            font-size: 40px;}
        .ici_order_info p{font-weight: 500;
            margin-bottom: 0;}
        .ici_order_info p span{    width: 120px;
            display: inline-block;font-weight: 600;}

        .i_customer_info{}
        .ic_box{}
        .ic_box h2{font-weight: 600;
            font-size: 22px;
            color: var(--primary_color);
            border-bottom: 1px solid #000;}
        .ic_box p{margin-bottom: 0;font-size: 14px;overflow: hidden;}
        .ic_box p span{width: 130px;
                float: left}
        .ic_box p span.icb_address{width: calc(100% - 130px);display: inline-block;}
        .ic_box p span.icb_address span{    width: unset;margin-right: 3px;}

        .i_product_info{}
        .i_product_info .table{    border: 2px solid #dee2e6;}
        /* .i_product_info tr#table_head, .i_product_info tr.table_head{
            background: var(--primary_color) !important;
            background-color: #34673D !important;
            color: #fff !important;} */
        .i_product_info tr.table_head th{
            background-color: #ff0000 !important}
        .return_tr{
            background-color: #ff000017 !important}
        .return_tr td{
            background-color: #ff000017 !important}
        .return_tr th{
            background-color: #ff000017 !important}

        @media print {
            .i_product_info tr#table_head th, .i_product_info tr.table_head th{
                background: var(--primary_print_color) !important;
                background-color: var(--primary_print_color) !important;
                color: #fff !important;}
            .i_product_info tr.table_head th{
                background-color: #ff0000 !important}
            .return_tr{
                background-color: #ff000017 !important}
            .return_tr td{
                    background-color: #ff000017 !important}
            .i_product_info tr.odd td{
                background: rgba(0,0,0,.05) !important;
                background-color: rgba(0,0,0,.05) !important;}
        }

        .i_footer{}
        .if_summary{}
        .i_footer p{margin-bottom: 0;}
        .if_summary_total{    border-top: 2px solid var(--primary_color);
            margin-top: 8px;}
        .if_summary_total h6{    font-weight: 600;
            font-size: 18px;
            margin-top: 5px;}
        .i_sign{margin-top: 75px;
            border-top: 2px solid var(--primary_color);
            display: inline-block;
            padding: 0 25px;}
        .i_sign h6{    margin-top: 5px;}
        /* End Advance Invoice Style */

        .in_header{width: 100%;height: 45px;position: relative;margin-bottom: 25px}
        .in_header .yellow-side{width: 60%;
        background-color: {{$settings_g['primary_color'] ?? '#c04000'}} !important;background: {{$settings_g['primary_color'] ?? '#c04000'}} !important;height: 100%;}
        .in_header .separator{    position: absolute;
        top: -4px;
        left: 60%;
        bottom: 0;
        width: 60px;
        margin-left: -30px;
        background-color: white !important;
        background: white !important;
        transform: skewX(-43deg);
        z-index: 1;
        height: 52px;}
            .i_logo img {
        max-width: 100%;
        height: 72px;
        margin-bottom: 0;
        object-fit: contain;width: auto;
    }
    tr#table_head{
        background: {{$settings_g['primary_color'] ?? '#c04000'}} !important;
        background-color: {{$settings_g['primary_color'] ?? '#c04000'}} !important;
        color: white !important;
    }
    .i_product_info .table{border: none}

    @media print {
        tr#table_head{
            background: {{$settings_g['primary_color'] ?? '#c04000'}} !important;
            background-color: {{$settings_g['primary_color'] ?? '#c04000'}} !important;
            color: white !important;
        }
    }
    </style>
</head>
<body>
    <div class="text-center noPrint my-4">
        <button class="btn btn-info print_now">Print</button>
    </div>

    @foreach ($orders as $order)
    @include('back.orders.print', [
        'for' => 'multiple'
    ])
    <p style="border-bottom: 2px dotted #000"></p>
    @include('back.orders.print', [
        'for' => 'multiple'
    ])

    <div style="page-break-after: always"></div>
    {{-- @if ($loop->iteration % 2 === 0)
    <div style="page-break-after: always"></div>
    @else
    <p style="border-bottom: 2px dotted #000"></p>
    @endif --}}
    @endforeach

    <div class="text-center noPrint my-4">
        <button class="btn btn-info print_now">Print</button>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){
            window.print();
        });
        $(document).on('click', '.print_now', function () {
            window.print();
        });
    </script>
</body>
</html>
