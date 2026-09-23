<div class="form-group">
    <label><b>Locations*</b></label>
    <select class="form-control redex_areas select_search" name="location" required>
        <option value="">Select Location</option>
        @foreach ($locations as $location)
            <option value="{{$location['area_id']}}::{{$location['zone_id']}}::{{$location['city_id']}}">{{$location['area_name']}} > {{$location['zone_name']}} > {{$location['city_name']}}</option>
        @endforeach
    </select>
</div>

<script>
$('.select_search').selectpicker({
    liveSearch: true
});
</script>
