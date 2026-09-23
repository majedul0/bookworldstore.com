
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label><b>Pickup Store*</b></label>
            <select class="form-control redex_stores" name="store" required>
                @foreach ($stores as $store)
                    <option value="{{$store['id']}}">{{$store['name']}}, {{$store['area_name']}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label><b>Delivery Area*</b></label>
            <select class="form-control redex_areas select_search" name="area" required>
                <option value="">Select Area</option>
                @foreach ($areas as $area)
                    <option value="{{$area['id']}}::{{$area['name']}}">{{$area['name']}}, {{$area['district_name']}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<script>
$('.select_search').selectpicker({
    liveSearch: true
});
</script>
