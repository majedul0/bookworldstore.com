<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{"Invoce: #$order->id-$order->status"}}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="shortcut icon" href="{{$settings_g['favicon'] ?? ''}}">

    <style media="print">
        @page  {
            margin: 20px;
        }

        .print{display: block !important;}
        .noPrint{display: none !important;}
        .content-wrapper{margin-left: 0;}
    </style>
</head>
<body>
    <div class="row">
        <div class="col-md-4">
            <div class="card" style="filter: grayscale(100%);">
                <div class="card-header">
                    <img src="{{$settings_g['logo'] ?? ''}}" style="height: 50px;">
                </div>

                <div class="card-body">
                    <h5 style="background: #000;color: #fff;display: inline-block;padding: 2px 0;padding-right:20px;padding-left:5px"><b>TO</b></h5>

                    <p class="mb-0"><b>{{$order->full_name}}</b></p>
                    <p class="mb-0">{{$order->street}}</p>
                    <p class="mb-0">{{"$order->city-$order->zip, $order->state, $order->country"}}</p>
                    @if($order->mobile_number)
                    <p class="mb-0"><b>Phone</b>: {{$order->mobile_number ?? 'N/A'}}</p>
                    @endif
                    <p class="mb-0"><b>Email</b>: {{$order->email ?? 'N/A'}}</p>

                    <hr>

                    <h6 style="background: #000;color: #fff;display: inline-block;padding: 2px 0;padding-right:20px;padding-left:5px;font-size:14px">FROM</h6>

                    <p class="mb-0">{{$settings_g['title'] ?? env('APP_NAME')}}</p>
                    <p class="mb-0">272 1/2 Coxwell Ave</p>
                    <p class="mb-0">Toronto- M4L 3B6, Ontario, Canada</p>
                    <p class="mb-0"><b>Phone</b>: {{$settings_g['mobile_number'] ?? ''}}</p>
                    <p class="mb-0"><b>Email</b>: {{$settings_g['email'] ?? ''}}</p>

                    <hr>

                    <div class="text-center">
                        <img src="data:image/png;base64,{{DNS1D::getBarcodePNG("#$order->id", "C128", 3, 60, array(1, 1, 1), true)}}" alt="barcode" style="max-width: 100%" />
                    </div>
                </div>
            </div>

            <div class="text-center">
                <button onclick="return window.print();" class="noPrint btn btn-success mt-4">Print</button>
                <a href="{{route('dashboard')}}" class="noPrint btn btn-info mt-4">Back To Dashboard</a>
            </div>
        </div>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
