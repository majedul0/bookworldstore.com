<style>
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
