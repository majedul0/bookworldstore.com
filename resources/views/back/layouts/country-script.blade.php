<script>
    $(document).on('change', '.country_select', function(){
        let canada_states = '@include("back.shipping.canada-states", ["any" => ($any ?? false)])';
        let usa_states = '@include("back.shipping.usa-states", ["any" => ($any ?? false)])';
        let country = $(this).val();

        if(country == 'USA'){
            $('.states').html(usa_states);
        }else{
            $('.states').html(canada_states);
        }
    });
</script>
