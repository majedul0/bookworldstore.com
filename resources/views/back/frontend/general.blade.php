@extends('back.layouts.master')
@section('title', 'General settings')

@section('head')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/css/bootstrap-colorpicker.min.css" rel="stylesheet">
@endsection

@section('master')
<form action="{{route('back.frontend.general')}}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="card border-light mt-3 shadow mb-5">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="nav flex-column nav-pills left_tab_nav" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" id="v-pills-WebInfo-tab" data-toggle="pill" href="#v-pills-WebInfo" role="tab" aria-controls="v-pills-WebInfo" aria-selected="true">Web Info</a>
                        <a class="nav-link" id="v-pills-LogoFavicon-tab" data-toggle="pill" href="#v-pills-LogoFavicon" role="tab" aria-controls="v-pills-LogoFavicon" aria-selected="false">Logo & Favicon</a>
                        <a class="nav-link" id="v-pills-Shop-tab" data-toggle="pill" href="#v-pills-Shop" role="tab" aria-controls="v-pills-Shop" aria-selected="false">Shop</a>

                        {{-- <a class="nav-link" id="v-pills-SEO-tab" data-toggle="pill" href="#v-pills-SEO" role="tab" aria-controls="v-pills-SEO" aria-selected="false">SEO</a> --}}

                        <a class="nav-link" id="v-pills-SocialLinks-tab" data-toggle="pill" href="#v-pills-SocialLinks" role="tab" aria-controls="v-pills-SocialLinks" aria-selected="false">Social Links</a>
                    </div>
                </div>

                <div class="col-md-8">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-WebInfo" role="tabpanel" aria-labelledby="v-pills-WebInfo-tab">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label col-form-label-sm"><b>Website Title*: </b></label>
                            <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm"name="title" value="{{$settings_g['title'] ?? ''}}" placeholder="Website Title" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label col-form-label-sm"><b>Slogan*: </b></label>
                            <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm" name="slogan" value="{{$settings_g['slogan'] ?? ''}}" placeholder="Website Slogan" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label col-form-label-sm"><b>Headline*: </b></label>
                            <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm" name="headline" value="{{$settings_g['headline'] ?? ''}}" placeholder="Website Headline" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label col-form-label-sm"><b>Top bar announcement: </b></label>
                            <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm" name="announcement_text" value="{{$settings_g['announcement_text'] ?? ''}}" placeholder="e.g. Free shipping on orders over ৳1000! | New arrivals every week!">
                            <small class="text-muted">Scrolls across the black bar at the top of every page. Separate multiple messages with a pipe (|). Leave empty to hide it.</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label col-form-label-sm"><b>Mobile Number*: </b></label>
                            <div class="col-sm-8">
                            <input type="number" class="form-control form-control-sm" placeholder="Mobile Number" name="mobile_number" value="{{$settings_g['mobile_number'] ?? ''}}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label col-form-label-sm"><b>Email Address*: </b></label>
                            <div class="col-sm-8">
                            <input type="email" class="form-control form-control-sm" placeholder="Email Address" name="email" value="{{$settings_g['email'] ?? ''}}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label col-form-label-sm"><b>Copyright*: </b></label>
                            <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm" placeholder="Copyright" name="copyright" value="{{$settings_g['copyright'] ?? ''}}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><b>Street*</b></label>
                                            <input type="text" class="form-control form-control-sm" name="street" value="{{$settings_g['street'] ?? ''}}" required="">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><b>City*</b></label>
                                            <input type="text" class="form-control form-control-sm" name="city" value="{{$settings_g['city'] ?? ''}}" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><b>State*</b></label>
                                            <input type="text" class="form-control form-control-sm" name="state" value="{{$settings_g['state'] ?? ''}}" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><b>Postal Code*</b></label>
                                            <input type="text" class="form-control form-control-sm" name="postal_code" value="{{$settings_g['zip'] ?? ''}}" required="">

                                            <input type="hidden" class="form-control form-control-sm" name="country" value="Canada" >
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-4">
                                        <div class="form-group">
                                            <label><b>Country*</b></label>
                                            <input type="text" class="form-control form-control-sm" name="country" value="{{$settings_g['country'] ?? ''}}" required="">
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label><b>Primary Color</b></label>
                            <input type="text" class="form-control form-control-sm colorpicker" name="primary_color" value="{{$settings_g['primary_color'] ?? '#c04000'}}">
                        </div>

                        <div class="form-group">
                            <label><b>Custom Head Code(In Head Tag)</b></label>
                            <textarea name="custom_head_code" class="form-control form-control-sm" cols="30" rows="8">{{$settings_g['custom_head_code'] ?? ''}}</textarea>
                        </div>

                        <div class="form-group">
                            <label><b>Custom Body Code (In Body Tag Start)</b></label>
                            <textarea name="custom_body_code" class="form-control form-control-sm" cols="30" rows="8">{{$settings_g['custom_body_code'] ?? ''}}</textarea>
                        </div>

                        <div class="form-group">
                            <label><b>Custom Footer Code (In Body Tag End)</b></label>
                            <textarea name="custom_footer_code" class="form-control form-control-sm" cols="30" rows="8">{{$settings_g['custom_footer_code'] ?? ''}}</textarea>
                        </div>

                        {{-- <div class="form-group">
                            <label><b>Primary Light Color</b></label>
                            <input type="text" class="form-control form-control-sm colorpicker" name="primary_light_color" value="{{$settings_g['primary_light_color'] ?? '#ff686e'}}">
                        </div> --}}

                        {{-- <div class="form-group">
                            <label><b>Secondary Color</b></label>
                            <input type="text" class="form-control form-control-sm colorpicker" name="secondary_color" value="{{$settings_g['secondary_color'] ?? '#21cd9c'}}">
                        </div> --}}

                        {{-- <div class="form-group">
                            <label><b>Secondary Dark Color</b></label>
                            <input type="text" class="form-control form-control-sm colorpicker" name="secondary_dark_color" value="{{$settings_g['secondary_dark_color'] ?? '#047857'}}">
                        </div> --}}
                    </div>

                    <div class="tab-pane fade" id="v-pills-LogoFavicon" role="tabpanel" aria-labelledby="v-pills-LogoFavicon-tab">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-4 text-center">
                                        <div class="img_group">
                                            <img class="img-thumbnail uploaded_img" style="width: 70%;" src="{{$settings_g['logo'] ?? asset('img/default-img.png')}}">

                                            <div class="form-group">
                                                <label><b>Logo</b></label>
                                                <div class="custom-file text-left">
                                                    <input type="file" class="custom-file-input image_upload" accept="image/*" name="logo">
                                                    <label class="custom-file-label">Choose file...</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-3 text-center">
                                        <div class="img_group">
                                            <img class="img-thumbnail uploaded_img_footer" style="width: 70%;" src="{{$settings_g['footer_logo'] ?? asset('img/default-img.png')}}">

                                            <div class="form-group">
                                                <label><b>Footer Logo</b></label>
                                                <div class="custom-file text-left">
                                                    <input type="file" class="custom-file-input uploaded_img_footer" accept="image/*" name="footer_logo">
                                                    <label class="custom-file-label">Choose file...</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}

                                    <div class="col-md-4 text-center">
                                        <div class="img_group">
                                            <img class="img-thumbnail uploaded_img_favicon" style="width: 70%;" src="{{$settings_g['favicon'] ?? asset('img/default-img.png')}}">

                                            <div class="form-group">
                                                <label><b>Favicon</b></label>
                                                <div class="custom-file text-left">
                                                    <input type="file" class="custom-file-input image_upload_favicon" accept="image/*" name="favicon">
                                                    <label class="custom-file-label">Choose file...</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 text-center">
                                        <div class="img_group">
                                            <img class="img-thumbnail uploaded_img_og" style="width: 70%;" src="{{$settings_g['og_image'] ?? asset('img/default-img.png')}}">

                                            <div class="form-group">
                                                <label><b>OG Image</b></label>
                                                <div class="custom-file text-left">
                                                    <input type="file" class="custom-file-input image_upload_og" name="og_image">
                                                    <label class="custom-file-label">Choose file...</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="row">
                                    <div class="col-md-4 text-center">
                                        <div class="img_group">
                                            <img class="img-thumbnail uploaded_img_hb_1" style="width: 70%;" src="{{$settings_g['home_banner_1'] ?? asset('img/default-img.png')}}">

                                            <div class="form-group">
                                                <label><b>Home Banner 1</b></label>
                                                <div class="custom-file text-left">
                                                    <input type="file" class="custom-file-input home_banner_1" name="home_banner_1">
                                                    <label class="custom-file-label">Choose file...</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 text-center">
                                        <div class="img_group">
                                            <img class="img-thumbnail uploaded_img_hb_2" style="width: 70%;" src="{{$settings_g['home_banner_2'] ?? asset('img/default-img.png')}}">

                                            <div class="form-group">
                                                <label><b>Home Banner 2</b></label>
                                                <div class="custom-file text-left">
                                                    <input type="file" class="custom-file-input home_banner_2" name="home_banner_2">
                                                    <label class="custom-file-label">Choose file...</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="v-pills-Shop" role="tabpanel" aria-labelledby="v-pills-Shop-tab">
                        <div class="row">
                            <div class="col-md-10">
                                {{-- <div class="form-group row">
                                    <label class="col-sm-2 col-form-label col-form-label-sm"><b>Tax: </b></label>
                                    <div class="col-sm-4">
                                    <input type="number" class="form-control form-control-sm" placeholder="Tax" name="tax" value="{{$settings_g['tax'] ?? ''}}">
                                    </div>

                                    <div class="col-sm-4">
                                        <select name="tax_type" class="form-control form-control-sm">
                                            <option value="Fixed" {{($settings_g['tax_type'] ?? '') == 'Fixed' ? 'selected' : ''}}>Fixed</option>
                                            <option value="Percent" {{($settings_g['tax_type'] ?? '') == 'Percent' ? 'selected' : ''}}>Percent</option>
                                        </select>
                                    </div>
                                </div> --}}

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label col-form-label-sm"><b>Currency: </b></label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control form-control-sm" placeholder="Currency name" name="currency_name" value="{{$settings_g['currency_name'] ?? ''}}">
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control form-control-sm" placeholder="Currency symbol" name="currency_symbol" value="{{$settings_g['currency_symbol'] ?? ''}}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-10">
                                        <label><b>Shipping Charge(Dhaka): </b></label>

                                        <div>
                                            <input type="number" class="form-control form-control-sm" placeholder="Shipping Charge" name="shipping_charge" value="{{$settings_g['shipping_charge'] ?? ''}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-10">
                                        <label><b>Shipping Charge(Out Dhaka): </b></label>
                                        <div>
                                            <input type="number" class="form-control form-control-sm" placeholder="Shipping Charge" name="shipping_charge_out_dhaka" value="{{$settings_g['shipping_charge_out_dhaka'] ?? ''}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label col-form-label-sm"><b>Stock Out Product can Order</b></label>

                                    <div class="">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="stock_out_can_order" id="stock_out_order_yes" value="Yes" {{(($settings_g['stock_out_can_order'] ?? 'No') == 'Yes') ? 'checked' : ''}}>
                                            <label class="form-check-label" for="stock_out_order_yes">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="stock_out_can_order" id="stock_out_order_no" value="No" {{(($settings_g['stock_out_can_order'] ?? 'No') == 'No') ? 'checked' : ''}}>
                                            <label class="form-check-label" for="stock_out_order_no">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="v-pills-SEO" role="tabpanel" aria-labelledby="v-pills-SEO-tab">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label col-form-label-sm"><b>Meta Description: </b></label>
                            <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm" placeholder="Meta Description" name="meta_description" value="{{$settings_g['meta_description'] ?? ''}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label col-form-label-sm"><b>Keywords: </b></label>
                            <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm" placeholder="Keywords" name="keywords" value="{{$settings_g['keywords'] ?? ''}}">
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="v-pills-SocialLinks" role="tabpanel" aria-labelledby="v-pills-SocialLinks-tab">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label col-form-label-sm"><b>Facebook: </b></label>
                            <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm" placeholder="Facebook" name="facebook" value="{{Info::Settings('social', 'facebook') ?? ''}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label col-form-label-sm"><b>Youtube: </b></label>
                            <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm" placeholder="Youtube" name="youtube" value="{{Info::Settings('social', 'youtube') ?? ''}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label col-form-label-sm"><b>Tiktok: </b></label>
                            <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm" placeholder="Tiktok" name="tiktok" value="{{Info::Settings('social', 'tiktok') ?? ''}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label col-form-label-sm"><b>WhatsApp: </b></label>
                            <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm" placeholder="e.g. +8801XXXXXXXXX" name="whatsapp" value="{{Info::Settings('social', 'whatsapp') ?? ''}}">
                            <small class="text-muted">Enter the phone number with country code. Customers will be able to message this number directly via WhatsApp.</small>
                            </div>
                        </div>
                        {{-- <div class="form-group row">
                            <label class="col-sm-2 col-form-label col-form-label-sm"><b>LinkedIn: </b></label>
                            <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm" placeholder="LinkedIn" name="linkedin" value="{{Info::Settings('social', 'linkedin') ?? ''}}">
                            </div>
                        </div> --}}
                    </div>
                </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button class="btn btn-success create_btn">Update</button>
            <br>
            <small><b>NB: *</b> marked are required field.</small>
        </div>
    </div>
</form>
@endsection

@section('footer')
<script>
    // Uploaded image get url
    function readURLFavicon(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.uploaded_img_favicon').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $(".image_upload_favicon").change(function(){
        readURLFavicon(this);
    });

    function readURLFooter(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.uploaded_img_footer').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $(".uploaded_img_footer").change(function(){
        readURLFooter(this);
    });

    // Uploaded image get url
    function readURLFavicon(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.uploaded_img_hb_image').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $(".image_upload_hb_image").change(function(){
        readURLFavicon(this);
    });

    function readURLog(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.uploaded_img_og').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $(".image_upload_og").change(function(){
        readURLog(this);
    });

    function readURLpm(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.uploaded_img_pm').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $(".image_upload_pm").change(function(){
        readURLpm(this);
    });

    function readURhb1(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.uploaded_img_hb_1').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $(".home_banner_1").change(function(){
        readURhb1(this);
    });

    function readURhb2(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.uploaded_img_hb_2').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $(".home_banner_2").change(function(){
        readURhb2(this);
    });
</script>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/js/bootstrap-colorpicker.min.js"></script>

<script>
    $('.colorpicker').colorpicker();
    $("document").ready(function() {
        rd_change();
    });
    function rd_change(){
        var ll=1;
        if($("#color_checked_value").val() =="#") {
            $("#inlineRadio1").attr('checked', true);
           // $( this ).attr( 'checked', true )
            ll = 1;
        }
        else {
            $("#inlineRadio2").attr('checked', true);
            ll = 2;
        }
        if(ll == 1){
            var element2 = document.getElementById("bgg_1");
            element2.classList.remove("hidden");
            var element = document.getElementById("bgg_2");
            element.classList.add("hidden");
        }else{
            var element3 = document.getElementById("bgg_2");
            element3.classList.remove("hidden");
            var element1 = document.getElementById("bgg_1");
            element1.classList.add("hidden");
            $("#color_picker_value").val(null);

        }
    }
    $(".radio_custom").change(function(){
        if(this.value ==1){
            var element2 = document.getElementById("bgg_1");
            element2.classList.remove("hidden");
            var element = document.getElementById("bgg_2");
            element.classList.add("hidden");
        }else{
            var element3 = document.getElementById("bgg_2");
            element3.classList.remove("hidden");
            var element1 = document.getElementById("bgg_1");
            element1.classList.add("hidden");
            $("#color_picker_value").val(null);
        }
    });

</script>
@endsection
