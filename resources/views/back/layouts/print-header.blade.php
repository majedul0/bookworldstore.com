<!-- title row -->
<div class="row">
    <div class="col-md-4">
        <img src="{{$settings_g['logo'] ?? asset('img/default-img.png')}}" style="width: 182px;max-width: 100%;" alt="logo" class="whp">
    </div>

    <div class="col-md-4 text-center">
        <h3><b>{{$settings_g['title'] ?? env('APP_NAME')}}</b></h3>
        <p class="mb-0">{{$settings_g['email'] ?? ''}}</p>
        <p class="mb-0">{{$settings_g['mobile_number'] ?? ''}}</p>
        <p class="mb-0">{{$settings_g['street'] ?? 'street'}}</p>
        <p class="mb-0">{{$settings_g['city'] ?? 'city'}}-{{$settings_g['zip'] ?? 'postal_code'}}, {{$settings_g['state'] ?? 'state'}}</p>
    </div>

    <div class="col-md-4 text-right">
        <br>
        <br>
        <br>
        <p class="mb-0"><b>Print Date:</b> {{date('d-M-Y')}}</p>
    </div>
</div>
<!-- info row -->

<hr>
