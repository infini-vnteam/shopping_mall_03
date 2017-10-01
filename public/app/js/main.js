'use strict';

const Main = {};

Main.settings = {};

Main.init = function () {
    this.bindUIActions();
    Vue.use(VueMaterial);
    Vue.use(VeeValidate);
    Vue.material.registerTheme('default', {
        primary: 'blue',
        background: 'white',
    });
};

Main.bindUIActions = function () {
    $(document).ready(function () {
        $('.dropdown').hover(
            function () {
                $('.dropdown-menu', this).fadeIn('fast');
            },
            function () {
                $('.dropdown-menu', this).fadeOut('fast');
            });

        // Product page
        //Handles the carousel thumbnails
        $('[id^=carousel-selector-]').click(function () {
            let id_selector = $(this).attr('id');
            try {
                let id = /-(\d+)$/.exec(id_selector)[1];
                console.log(id_selector, id);
                jQuery('#myCarousel').carousel(parseInt(id));
            } catch (e) {
                console.log('Regex failed!', e);
            }
        });
        // When the carousel slides, auto update the text
        $('#myCarousel')
            .on('slid.bs.carousel', function (e) {
                let id = $('.item.active').data('slide-number');
                $('#carousel-text').html($('#slide-content-' + id).html());
            })
            .carousel({
                interval: false,
            });

        $('.list').click(function () {

            $('.hover_div_outer').toggleClass('displayblock');
        });

        $('.card').mouseleave(function () {

            $('.hover_div_outer').removeClass('displayblock');
        });

        $('[data-toggle="tooltip"]').tooltip();

        $('.js-gotop').on('click', function(event){

            event.preventDefault();

            $('html, body').animate({
                scrollTop: $('html').offset().top
            }, 500, 'easeInOutExpo');

            return false;
        });

        $(window).scroll(function(){

            let $win = $(window);
            if ($win.scrollTop() > 200) {
                $('.js-top').addClass('active');
            } else {
                $('.js-top').removeClass('active');
            }

        });
    });

    let $window = $(window);
    if ($window.width() < 640) {
        $('.megamenu').insertAfter('#hlink');
    } else {
        // change functionality for larger screens
    }
};

function megamenu(menu_name) {
    $('.sub_menu').hide();

    $('#' + menu_name).show();
}

$(function () {
    Main.init();
});
