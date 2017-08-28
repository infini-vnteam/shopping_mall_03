;(function () {

    'use strict';

    var mobileMenuOutsideClick = function () {

        $(document).click(function (e) {
            var container = $("#fh5co-offcanvas, .js-fh5co-nav-toggle");
            if (!container.is(e.target) && container.has(e.target).length === 0) {

                if ($('body').hasClass('offcanvas')) {

                    $('body').removeClass('offcanvas');
                    $('.js-fh5co-nav-toggle').removeClass('active');

                }


            }
        });

    };


    var offcanvasMenu = function () {

        $('#page').prepend('<div id="fh5co-offcanvas" />');
        $('#page').prepend('<a href="#" class="js-fh5co-nav-toggle fh5co-nav-toggle fh5co-nav-white"><i></i></a>');
        var clone1 = $('.menu-1 > ul').clone();
        $('#fh5co-offcanvas').append(clone1);
        var clone2 = $('.menu-2 > ul').clone();
        $('#fh5co-offcanvas').append(clone2);

        $('#fh5co-offcanvas .has-dropdown').addClass('offcanvas-has-dropdown');
        $('#fh5co-offcanvas')
            .find('li')
            .removeClass('has-dropdown');

        // Hover dropdown menu on mobile
        $('.offcanvas-has-dropdown').mouseenter(function () {
            var $this = $(this);

            $this
                .addClass('active')
                .find('ul')
                .slideDown(500, 'easeOutExpo');
        }).mouseleave(function () {

            var $this = $(this);
            $this
                .removeClass('active')
                .find('ul')
                .slideUp(500, 'easeOutExpo');
        });


        $(window).resize(function () {

            if ($('body').hasClass('offcanvas')) {

                $('body').removeClass('offcanvas');
                $('.js-fh5co-nav-toggle').removeClass('active');

            }
        });
    };


    var burgerMenu = function () {

        $('body').on('click', '.js-fh5co-nav-toggle', function (event) {
            var $this = $(this);


            if ($('body').hasClass('overflow offcanvas')) {
                $('body').removeClass('overflow offcanvas');
            } else {
                $('body').addClass('overflow offcanvas');
            }
            $this.toggleClass('active');
            event.preventDefault();

        });
    };


    var contentWayPoint = function () {
        var i = 0;
        $('.animate-box').waypoint(function (direction) {

            if (direction === 'down' && !$(this.element).hasClass('animated-fast')) {

                i++;

                $(this.element).addClass('item-animate');
                setTimeout(function () {

                    $('body .animate-box.item-animate').each(function (k) {
                        var el = $(this);
                        setTimeout(function () {
                            var effect = el.data('animate-effect');
                            if (effect === 'fadeIn') {
                                el.addClass('fadeIn animated-fast');
                            } else if (effect === 'fadeInLeft') {
                                el.addClass('fadeInLeft animated-fast');
                            } else if (effect === 'fadeInRight') {
                                el.addClass('fadeInRight animated-fast');
                            } else {
                                el.addClass('fadeInUp animated-fast');
                            }

                            el.removeClass('item-animate');
                        }, k * 200, 'easeInOutExpo');
                    });

                }, 100);

            }

        }, {offset: '85%'});
    };


    var dropdown = function () {

        $('.has-dropdown').mouseenter(function () {

            var $this = $(this);
            $this
                .find('.dropdown')
                .css('display', 'block')
                .addClass('animated-fast fadeInUpMenu');

        }).mouseleave(function () {
            var $this = $(this);

            $this
                .find('.dropdown')
                .css('display', 'none')
                .removeClass('animated-fast fadeInUpMenu');
        });

    };


    var tabs = function () {

        // Auto adjust height
        $('.fh5co-tab-content-wrap').css('height', 0);
        var autoHeight = function () {

            setTimeout(function () {

                var tabContentWrap = $('.fh5co-tab-content-wrap'),
                    tabHeight = $('.fh5co-tab-nav').outerHeight(),
                    formActiveHeight = $('.tab-content.active').outerHeight(),
                    totalHeight = parseInt(tabHeight + formActiveHeight + 90);

                tabContentWrap.css('height', totalHeight);

                $(window).resize(function () {
                    var tabContentWrap = $('.fh5co-tab-content-wrap'),
                        tabHeight = $('.fh5co-tab-nav').outerHeight(),
                        formActiveHeight = $('.tab-content.active').outerHeight(),
                        totalHeight = parseInt(tabHeight + formActiveHeight + 90);

                    tabContentWrap.css('height', totalHeight);
                });

            }, 100);

        };

        autoHeight();


        // Click tab menu
        $('.fh5co-tab-nav a').on('click', function (event) {

            var $this = $(this),
                tab = $this.data('tab');

            $('.tab-content')
                .addClass('animated-fast fadeOutDown')
                .removeClass('active');

            $('.fh5co-tab-nav li').removeClass('active');

            $this
                .closest('li')
                .addClass('active')

            $this
                .closest('.fh5co-tabs')
                .find('.tab-content[data-tab-content="' + tab + '"]')
                .removeClass('animated-fast fadeOutDown')
                .addClass('animated-fast active fadeIn');


            autoHeight();
            event.preventDefault();

        });
    };

    var goToTop = function () {

        $('.js-gotop').on('click', function (event) {

            event.preventDefault();

            $('html, body').animate({
                scrollTop: $('html').offset().top
            }, 500, 'easeInOutExpo');

            return false;
        });

        $(window).scroll(function () {

            var $win = $(window);
            if ($win.scrollTop() > 200) {
                $('.js-top').addClass('active');
            } else {
                $('.js-top').removeClass('active');
            }

        });

    };


    var fixedCategoryBar = function () {
        $(window).scroll(function () {

            var $win = $(window);
            if ($win.scrollTop() > 92) {
                $('.js-category-bar').addClass('navbar-fixed-top');
            } else {
                $('.js-category-bar').removeClass('navbar-fixed-top');
            }

        });

    };


    // Loading page
    var loaderPage = function () {
        $(".fh5co-loader").fadeOut("slow");
    };

    var counter = function () {
        $('.js-counter').countTo({
            formatter: function (value, options) {
                return value.toFixed(options.decimals);
            },
        });
    };

    var counterWayPoint = function () {
        if ($('#fh5co-counter').length > 0) {
            $('#fh5co-counter').waypoint(function (direction) {

                if (direction === 'down' && !$(this.element).hasClass('animated')) {
                    setTimeout(counter, 400);
                    $(this.element).addClass('animated');
                }
            }, {offset: '90%'});
        }
    };

    var sliderMain = function () {

        $('#fh5co-hero .flexslider').flexslider({
            animation: "fade",
            slideshowSpeed: 5000,
            directionNav: true,
            start: function () {
                setTimeout(function () {
                    $('.slider-text').removeClass('animated fadeInUp');
                    $('.flex-active-slide').find('.slider-text').addClass('animated fadeInUp');
                }, 500);
            },
            before: function () {
                setTimeout(function () {
                    $('.slider-text').removeClass('animated fadeInUp');
                    $('.flex-active-slide').find('.slider-text').addClass('animated fadeInUp');
                }, 500);
            }

        });

        $('#fh5co-hero .flexslider .slides > li').css('height', $(window).height());
        $(window).resize(function () {
            $('#fh5co-hero .flexslider .slides > li').css('height', $(window).height());
        });

    };

    var testimonialCarousel = function () {

        var owl = $('.owl-carousel-fullwidth');
        owl.owlCarousel({
            items: 1,
            loop: true,
            margin: 0,
            nav: false,
            dots: true,
            smartSpeed: 800,
            autoHeight: true
        });

    };

    var cartController = function () {
        var cart = readCookie('Cart');
        cart = JSON.parse(cart);
        if (!cart || !cart.token || cart.token !== 'shopping123') {
            cart = {
                token: 'shopping123',
                totalItem: 0,
                items: [],
                totalPrice: 0
            };
        }

        console.log(cart);
        updateNumberCart(cart.totalItem);

        $('.addToCart').on('click', function () {
            var item = {};
            var $this = $(this);
            item.id = parseInt($this.attr('data-cart-add'));
            item.name = $this.attr('data-cart-name');
            item.quantity = parseInt($this.attr('data-cart-quantity'));
            item.img = $this.attr('data-cart-img');
            item.price = parseFloat($this.attr('data-cart-price'));
            var exist = false;
            cart.items.every(function (item1) {
                if (item1.id === item.id) {
                    exist = true;
                    return false;
                }
                return true;
            });
            if (!exist) {
                cart.totalItem += 1;
                cart.items.push(item);
                cart.totalPrice += item.quantity * item.price;
                updateNumberCart(cart.totalItem);
            }

            writeCookie('Cart', JSON.stringify(cart), 7);
            $('#flash-message').append('<div class="alert alert-success"><strong>Success!</strong> Add product to cart successfully.</div>');
            $('.alert').delay(3000).slideUp();
        });

        if (cart.totalPrice > 0) {
            var ml = cart.items.map(function (item) {
                return '<div class="item" id="item' + item.id + '">'
                    + '<div class="buttons">'
                    + '<span class="delete-btn" data-cart-add="' + item.id + '"></span>'
                    + '<span class="like-btn"></span>'
                    + '</div>'

                    + '<div class="image">'
                    + '<img src="' + item.img + '" alt=""/>'
                    + '</div>'

                    + '<div class="description">'
                    + '<span>' + item.name + '</span>'
                    // + '<span>Brushed Scarf</span>'
                    // + '<span>Brown</span>'
                    + '</div>'

                    + '<div class="quantity">'
                    + '<button class="minus-btn" type="button" name="button" data-cart-add="' + item.id + '">'
                    + '<img src="/img/minus.svg" alt=""/>'
                    + '</button>'
                    + '<input type="text" name="name" value="' + item.quantity + '">'
                    + '<button class="plus-btn" type="button" name="button" data-cart-add="' + item.id + '">'
                    + '<img src="/img/plus.svg" alt=""/>'
                    + '</button>'
                    + '</div>'

                    + '<div class="total-price" id="total-price' + item.id + '">$' + item.quantity * item.price + '</div>'
                    + '</div>'
            }).join('\n');

            $('.shopping-cart-content').html(
                '<h2>Your cart</h2>'
                + '<div class="divider"></div>'
                + ml
                + '<div class="row">'
                + '<div class="col-md-5 text-left">'
                + '<div class="col-md-6 text-left">'
                + '<h3>Total price: </h3>'
                + '</div>'
                + '<div class="col-md-6 text-left">'
                + '<h3 id="total-price-all">$' + cart.totalPrice + '</h3>'
                + '</div>'
                + '</div>'
                + '<div class="col-md-6 text-right" style="padding-top: 10px">'
                + '<form action="/cart/add" method="get">'
                + '<input type="submit" value="Checkout" id="checkout" class="btn btn-warning">'
                + '</form>'
                + '</div>'
                + '</div>'
            );

        } else {
            var ml = '<div class="row text-center">'
                + '<h1>Your cart is empty :"></h1>'
                + '<button class="btn btn-primary btn-lg"'
                + 'onclick="location.href = \' / \';"> Continue shopping'
                + '</button>'
                + '</div>';

            $('.shopping-cart-content').html(ml);
        }


        $('.like-btn').on('click', function () {
            $(this).toggleClass('is-active');
        });

        $('.like-btn2').on('click', function () {
            $(this).toggleClass('is-active');
        });

        setTimeout(function() {$('.like-btn2').click();}, 800);

        $('.minus-btn').on('click', function (e) {
            e.preventDefault();
            var $this = $(this);
            var $input = $this.closest('div').find('input');
            var value = parseInt($input.val());
            var id = parseInt($this.attr('data-cart-add'));

            if (value > 1) {
                value = value - 1;

                $input.val(value);


                var idx;
                var item = cart.items.find(function (ele, i) {
                    idx = i;
                    return ele.id == id;
                });

                if (item) {
                    cart.totalPrice -= item.price;
                    cart.items[idx].quantity -= 1;
                    updateItemPrice(id, cart.items[idx].quantity * item.price);
                    updateTotalPrice(cart.totalPrice);

                    writeCookie('Cart', JSON.stringify(cart), 7);
                }
            } else {
                var item_id = 'item' + id;
                deleteElement(item_id);

                var idx;
                var item = cart.items.find(function (ele, i) {
                    idx = i;
                    return ele.id == id;
                });

                if (item) {
                    cart.totalItem -= 1;
                    cart.totalPrice -= item.price * item.quantity;
                    cart.items.splice(idx, 1);
                }

                updateNumberCart(cart.totalItem);
                updateTotalPrice(cart.totalPrice);
                writeCookie('Cart', JSON.stringify(cart), 7);

                if (cart.totalPrice == 0) {
                    var ml = '<div class="row text-center">'
                        + '<h1>Your cart is empty :"></h1>'
                        + '<button class="btn btn-primary btn-lg"'
                        + 'onclick="location.href = \' / \';"> Continue shopping'
                        + '</button>'
                        + '</div>';

                    $('.shopping-cart-content').html(ml);
                }
            }
        });

        // $('#checkout').click(function (e) {
        //     e.preventDefault();
        //     var stuff = {};
        //     cart.items.forEach(function (item) {
        //         var content = {
        //             id: item.id,
        //             quantity: item.quantity
        //         };
        //         stuff[item.id] = content;
        //     });
        //     console.log(stuff);
        //     $.ajax({
        //         type    : 'POST',
        //         url     : '/cart/add',
        //         data    : stuff,
        //         success : function(response) {
        //             console.log(response);
        //         }
        //     });
        // });

        $('.plus-btn').on('click', function (e) {
            e.preventDefault();
            var $this = $(this);
            var $input = $this.closest('div').find('input');
            var value = parseInt($input.val());

            if (value < 100) {
                value = value + 1;
            } else {
                value = 100;
            }

            $input.val(value);

            var id = parseInt($this.attr('data-cart-add'));
            var idx;
            var item = cart.items.find(function (ele, i) {
                idx = i;
                return ele.id == id;
            });

            if (item) {
                cart.totalPrice += item.price;
                cart.items[idx].quantity += 1;
                updateItemPrice(id, cart.items[idx].quantity * item.price);
                updateTotalPrice(cart.totalPrice);

                writeCookie('Cart', JSON.stringify(cart), 7);
            }
        });

        $('.delete-btn').on('click', function (e) {
            e.preventDefault();
            var id = $(this).parent().parent().attr('id');
            deleteElement(id);

            var $this = $(this);
            id = parseInt($this.attr('data-cart-add'));
            var idx;
            var item = cart.items.find(function (ele, i) {
                idx = i;
                return ele.id == id;
            });

            if (item) {
                cart.totalItem -= 1;
                cart.totalPrice -= item.price * item.quantity;
                cart.items.splice(idx, 1);
            }

            updateNumberCart(cart.totalItem);
            updateTotalPrice(cart.totalPrice);
            writeCookie('Cart', JSON.stringify(cart), 7);

            if (cart.totalPrice == 0) {
                var ml = '<div class="row text-center">'
                    + '<h1>Your cart is empty :"></h1>'
                    + '<button class="btn btn-primary btn-lg"'
                    + 'onclick="location.href = \' / \';"> Continue shopping'
                    + '</button>'
                    + '</div>';

                $('.shopping-cart-content').html(ml);
            }
        })
    };

    var updateNumberCart = function (number) {
        $('#number_cart_item').text(number.toString());
    };

    var updateItemPrice = function (id, number) {
        $('#total-price' + id).text('$' + number.toString());
    };

    var updateTotalPrice = function (number) {
        $('#total-price-all').text('$' + number.toString());
    };

    var deleteElement = function (id) {
        var elem = $('#' + id);
        elem.remove();
    };

    var writeCookie = function (name, value, days) {
        var date, expires;
        if (days) {
            date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toGMTString();
        } else {
            expires = "";
        }
        document.cookie = name + "=" + value + expires + "; path=/";
    };

    var readCookie = function (name) {
        var i, c, ca, nameEQ = name + "=";
        ca = document.cookie.split(';');
        for (i = 0; i < ca.length; i++) {
            c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1, c.length);
            }
            if (c.indexOf(nameEQ) == 0) {
                return c.substring(nameEQ.length, c.length);
            }
        }
        return null;
    };

    var menuCategory = function () {
      $('#menu-home').addClass('menu-category-active');

      // $('.menu-category').click(function (e) {
      //     e.preventDefault();
      //     $('.menu-category-bar').removeClass('menu-category-active');
      //     $(this).addClass('menu-category-active');
      // });
    };

    $(function () {
        mobileMenuOutsideClick();
        offcanvasMenu();
        burgerMenu();
        contentWayPoint();
        dropdown();
        tabs();
        goToTop();
        fixedCategoryBar();
        loaderPage();
        counterWayPoint();
        sliderMain();
        testimonialCarousel();
        cartController();
        menuCategory();
    });


}());