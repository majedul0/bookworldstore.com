import {
    Carousel,
    initTE,
} from "tw-elements";

initTE({ Carousel });

// function toggleSearch(){
//     console.log(123);
// }


$(window).scroll(function() {
    let top_header_height = $('.top_header').height();
    // console.log(top_header_height);
    if ($(window).scrollTop() > top_header_height) {
        $('.main_header').addClass("fixed_header");
    } else {
        $('.main_header').removeClass("fixed_header");
    }
});

$(document).on('click', '.toggle_mobile_menu', function() {
    $('.mobile_search').toggleClass('active');
});
