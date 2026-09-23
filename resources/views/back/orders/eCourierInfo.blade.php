@foreach ($packages as $package)
    <option value="{{$package['package_code']}}">{{$package['shipping_charge']}} tk - {{$package['coverage_id']}} - {{$package['weight']}} - {{$package['delivery_time']}}</option>
@endforeach
