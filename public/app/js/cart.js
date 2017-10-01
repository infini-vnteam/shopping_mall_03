'use strict';

const Cart = {};

Cart.setting = {
    cookieName: 'Cart',
    cartEmpty: '.cart-outer',
    cartNonempty: '.cart-inner',
};

Cart.init = function () {
    this.cart = this.readCookie('Cart');
    this.cart = JSON.parse(this.cart);
    if (!this.cart || !this.cart.token || this.cart.token !== 'shopping123') {
        this.cart = {
            token: 'shopping123',
            totalItem: 0,
            items: [],
            totalPrice: 0,
        };
    }

    this.updateUI();

    this.bindUIActions();
};

Cart.bindUIActions = function () {
    const current = this;

    $('.delete-item').on('click', function (e) {
        let id = $(this).attr('data-id');

        let idx = current.cart.items.findIndex(function (ele) {
            return ele.id === id;
        });

        if (idx >= 0) {
            current.cart.totalItem -= 1;
            current.cart.items.splice(idx, 1);
            current.deleteElement('item' + id);
        }

        current.update();
    });

    $('.btn-number').click(function (e) {
        e.preventDefault();

        let fieldName = $(this).attr('data-field');
        let type = $(this).attr('data-type');
        let input = $('input[name=\'' + fieldName + '\']');
        let currentVal = parseInt(input.val());
        if (!isNaN(currentVal)) {
            if (type === 'minus') {

                if (currentVal > input.attr('min')) {
                    input.val(currentVal - 1).change();
                }
                if (parseInt(input.val()) === input.attr('min')) {
                    $(this).attr('disabled', true);
                }

            } else if (type === 'plus') {

                if (currentVal < input.attr('max')) {
                    input.val(currentVal + 1).change();
                }
                if (parseInt(input.val()) === input.attr('max')) {
                    $(this).attr('disabled', true);
                }

            }
        } else {
            input.val(0);
        }

        let id = fieldName;
        let idx = current.cart.items.findIndex((ele) => {
            return ele.id === id;
        });

        current.cart.items[idx].quantity = parseInt(input.val());

        current.updateItemPriceUI(id);

        current.update();
    });

    $('.input-number').focusin(function () {
        $(this).data('oldValue', $(this).val());
    });

    $('.input-number').change(function () {

        let minValue = parseInt($(this).attr('min'));
        let maxValue = parseInt($(this).attr('max'));
        let valueCurrent = parseInt($(this).val());
        let name = $(this).attr('name');
        if (valueCurrent >= minValue) {
            $('.btn-number[data-type=\'minus\'][data-field=\'' + name + '\']').removeAttr('disabled');
        } else {
            Snackbar.pushMessage('The quantity must be between 1 and 100', 'warning');
            $(this).val($(this).data('oldValue'));
        }
        if (valueCurrent <= maxValue) {
            $('.btn-number[data-type=\'plus\'][data-field=\'' + name + '\']').removeAttr('disabled');
        } else {
            Snackbar.pushMessage('The quantity must be between 1 and 100', 'warning');
            $(this).val($(this).data('oldValue'));
        }

        let id = name;

        let idx = current.cart.items.findIndex((ele) => {
            return ele.id === id;
        });

        if (idx >= 0) {
            current.cart.items[idx].quantity = parseInt($(this).val());
            current.updateItemPriceUI(id);
            current.update();
        }

    });

    $('.input-number').keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
            // Allow: Ctrl+A
            (e.keyCode === 65 && e.ctrlKey === true) ||
            // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
};

Cart.addToCart = function (item) {
    let itemIndex = this.cart.items.findIndex(function (ele) {
        return ele.id === item.id;
    });

    if (itemIndex < 0) {
        this.cart.totalItem += 1;
        this.cart.items.push(item);
    } else {
        this.cart.items[itemIndex].quantity += item.quantity;
        this.cart.items[itemIndex].price = item.price;
        this.updateItemPriceUI(item.id);
    }

    this.update();
};

Cart.calculateTotalPrice = function () {
    this.cart.totalPrice = this.cart.items.reduce((sum, item) => {
        return sum + item.price * item.quantity;
    }, 0);
    this.cart.vat = Math.round(this.cart.totalPrice * 0.1);
    this.cart.subTotalPrice = this.cart.totalPrice + this.cart.vat;
};

Cart.updateNumberCartUI = function () {
    let number = this.cart.totalItem;

    if (number < 0) {
        number = 0;
    }

    let text = '', text2 = '';
    switch (number) {
        case 0:
            text = 'empty';
            break;
        case 1:
            text = '1 item';
            text2 = '1 item in cart';
            break;
        default:
            text = number + ' items';
            text2 = number + ' items in cart';
            break;
    }
    $('.cart-logo').text(text);
    $('.panel-heading.number-item').text(text2);
};

Cart.updateItemPriceUI = function (id) {
    let item = this.cart.items.find((elem) => {
        return elem.id === id;
    });

    $('#total-price' + id).text('$' + item.price * item.quantity);
};

Cart.updateTotalPriceUI = function () {
    $('#total-price-all').text('$' + this.cart.totalPrice);
};

Cart.deleteElement = function (id) {
    let elem = $('#' + id);
    elem.remove();
};

Cart.writeCookie = function (name, value, days) {
    let date, expires;
    if (days) {
        date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = '; expires=' + date.toGMTString();
    } else {
        expires = '';
    }
    document.cookie = name + '=' + value + expires + '; path=/';
};

Cart.readCookie = function (name) {
    let i, c, ca, nameEQ = name + '=';
    ca = document.cookie.split(';');
    for (i = 0; i < ca.length; i++) {
        c = ca[i];
        while (c.charAt(0) === ' ') {
            c = c.substring(1, c.length);
        }
        if (c.indexOf(nameEQ) === 0) {
            return c.substring(nameEQ.length, c.length);
        }
    }
    return null;
};

Cart.paymentInfoUpdateUI = function () {
    $('.cart-product-price').text('$' + this.cart.totalPrice);
    $('.cart-vat').text('$' + this.cart.vat);
    $('.cart-total-price').text('$' + this.cart.subTotalPrice);
};

Cart.setCartUI = function () {
    if (this.cart.totalItem > 0) {
        $(this.setting.cartEmpty).addClass('hidden');
        $(this.setting.cartNonempty).removeClass('hidden');
    } else {
        $(this.setting.cartNonempty).addClass('hidden');
        $(this.setting.cartEmpty).removeClass('hidden');
    }
};

Cart.updateUI = function () {
    this.setCartUI();
    this.updateNumberCartUI();
    this.updateTotalPriceUI();
    this.paymentInfoUpdateUI();
};

Cart.update = function () {
    this.calculateTotalPrice();
    this.updateUI();
    this.writeCookie(this.setting.cookieName, JSON.stringify(this.cart), 7);
};

$(function () {
    Cart.init();
});