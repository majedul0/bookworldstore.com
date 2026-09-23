$(document).ready(function(){
	// Disable anchor tag
	$("a[href='#']").click(function (e) { e.preventDefault(); });
	// $(".has_sub_menu a").click(function (e) {
	// 	e.preventDefault();
	// });
	// $(".main-sidebar li a").click(function (e) {
	// 	// $(this).addClass('temp_class');
	// 	// // $('.main-sidebar ul li').removeClass('active');
	// 	// $('.temp_class').addClass('active');
	// 	// $(this).removeClass('temp_class');

	// 	// if($(this).hasClass("active")){
	// 	// 	$(this).removeClass('temp_class');
	// 	// }else{
	// 	// 	$(this).addClass('temp_class');
	// 	// }
	// 	$('.main-sidebar ul li a').removeClass('active');
	// 	$('.main-sidebar ul li').removeClass('active');
	// 	// $('.temp_class').addClass('active');
	// 	// $('.temp_class').removeClass('temp_class');
	// 	$(this).addClass('active');
	// 	$(this).parents('li').addClass('active');
	// });

	// $(".has_sub_menu.active").click(function (e) {
	// 	alert(123);
	// 	$(this).removeClass('active');
	// });

	// $('.main-sidebar ul li a.active').parents('li').addClass('active');
});

// Mobile menu trigger
function menuTrigger(){
	if($('.main-sidebar').hasClass('mobile_active')){
		$('.main-sidebar').removeClass('mobile_active');
	}else{
		$('.main-sidebar').addClass('mobile_active');
	}
}

// Alert Script
const Toast = Swal.mixin({
    toast: true,
    position: 'center-center',
    showConfirmButton: false,
    background: '#E5F3FE',
    timer: 4000
});
function cAlert(type, text){
    Toast.fire({
        icon: type,
        title: text
    });
}

// Uploaded image get url
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $(input).closest('.img_group').find('.uploaded_img').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$(document).on('change', ".image_upload", function(){
    readURL(this);
});

// $(".image_upload").click(function(){
//     // readURL(this);
//     alert(245);
// });

// Custom loader
function cLoader(type = 'show'){
    if(type == 'show'){
        $('.loader').show();
    }else{
        $('.loader').hide();
    }
}

// Tooltips
$('.c_tooltip').tooltip();

// Show Gallery
$(document).on('click', '.show_gallery_btn', function(){
    let btn_type = $(this).data('type');
    let skip = 0;
    let gallery_skip = 0;
    if(btn_type == 'new'){
        skip = 0;
        $('.gallery_skip').val(skip);

        $('.gallery_load_more_btn').show();
    }else{
        gallery_skip = $('.gallery_skip').val();

        skip = parseInt(gallery_skip) + 1;

        $('.gallery_skip').val(skip);
    }

    let get_gallery_url = application_root + '/adminx/media/get-gallery'
    cLoader();
    $.ajax({
        url: get_gallery_url,
        method: 'POST',
        data: {skip, _token},
        success: function(result){
            cLoader('hide');

            if(btn_type == 'new'){
                $('.gallery_items').html(result);
            }else{
                $('.gallery_items').append(result);
            }

            if(result == ''){
                $('.gallery_load_more_btn').hide();
            }
        },
        error: function(){
            cLoader('hide');
            cAlert('error', 'Something wrong!');
        }
    });
});

// Select Media Image
$(document).on('click', '.select_media_image', function(){
    let img_full_path = $(this).data('image');
    let media_id = $(this).data('id');

    $('.selected_img').attr('src', img_full_path);

    $('.choice_image_group').show();
    $('.upload_image_group').hide();

    $('#mediaModal').modal('hide');
    $('.media_id').val(media_id);

    if(upload_required){
        $('.image_upload').removeAttr('required', 'required');
    }
});

// Enable Upload
$(document).on('click', '.upload_image_btn', function(){
    $('.choice_image_group').hide();
    $('.upload_image_group').show();

    if(upload_required){
        $('.image_upload').attr('required', 'required');
    }
    $('.media_id').val('');
});
