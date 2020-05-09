$(document).ready(function () {
    /* currency */
    $('#currency').change(function () {
        window.location = 'currency/change?curr=' + $(this).val();
    });
    /* END currency */

    /* modification */
    $('.available select').on('change', function () {
        let modificationId = $(this).val();
        let color = $(this).find('option').filter(':selected').data('title');
        let price = $(this).find('option').filter(':selected').data('price');
        let basePrice = $('#base-price').data('base');

        if (price) {
            $('#base-price').text(symbolLeft + price + symbolRight);
        } else {
            $('#base-price').text(symbolLeft + basePrice + symbolRight);
        }
    });
    /* END modification */

    /* cart */
    $(document).on('click', '.js-add-to-cart-link', function (e) {
        e.preventDefault();

        let id = $(this).data('id'),
            qty = $('.product-quantity input').val() ? $('.product-quantity input').val() : 1,
            mod = $('.available select').val();

        $.ajax({
            url: '/cart/add',
            data: {id: id, qty: qty, mod: mod},
            type: 'GET',
            success: function (res) {
                showCart(res);
            },
            error: function () {
                alert('Ошибка! Попробуйте позже');
            }
        });
    });

    $('#cart .modal-body').on('click', '.del-item', function (e) {
        e.preventDefault();
        let id = $(this).data('id');

        $.ajax({
            url: '/cart/delete',
            data: {id: id},
            type: 'GET',
            success: function (res) {
                showCart(res);
            },
            error: function () {
                alert('Ошибка! Попробуйте позже');
            }
        });
    });
    /* END cart */

    /* search */
    let products = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            wildcard: '%QUERY',
            url: path + 'search/typehead?query=%QUERY'
        }
    });

    products.initialize();

    $('#typehead').typeahead({
        highlight: true
    }, {
        name: 'products',
        display: 'title',
        limit: 9,
        source: products
    });

    $('#typehead').bind('typehead:select', function(ev, suggestion) {
        window.location = path + 'search/?s=' + encodeURIComponent(suggestion.title);
    });
    /* END search */

    /* checkout */
    $('.cart').on('click', '.del-item', function (e) {
        e.preventDefault();
        let id = $(this).data('id');

        $.ajax({
            url: '/cart/delete',
            data: {id: id},
            type: 'GET',
            success: function (res) {
                window.location = path + 'cart/view';
            },
            error: function () {
                alert('Ошибка! Попробуйте позже');
            }
        });
    });
    /* END checkout */

    /* filters */
    $(document).on('change', '.w_sidebar input', function() {
        let checked = $('.w_sidebar input:checked'),
            data = '';

        checked.each(function () {
            data += this.value + ',';
        });

        if (data) {
            $.ajax({
                url: location.href,
                data: {filter: data},
                type: 'GET',
                beforeSend: function() {
                    $('.preloader').fadeIn(300, function() {
                        $('.product-one').hide();
                    });
                },
                success: function(res) {
                    $('.preloader').delay(500).fadeOut('slow', function() {
                        $('.product-one').html(res).fadeIn();
                        let url = location.search.replace(/filter(.+?)(&|$)/g, '');
                        let newUrl = location.pathname + url + (location.search ? "&" : "?") + "filter=" + data;
                        newUrl = newUrl.replace('&&', '&');
                        newUrl = newUrl.replace('?&', '?');
                        history.pushState({}, '', newUrl);
                    });
                },
                error: function() {
                    alert('Ошибка в работе Фильтра!');
                }
            });
        } else {
            window.location = location.pathname;
        }
    });
    /* END filters */
});

function showCart(cart) {
    if ($.trim(cart) == '<h3>Корзина пуста</h3>') {
        $('#cart .modal-footer a, #cart .modal-footer .btn-danger').css('display', 'none');
    } else {
        $('#cart .modal-footer a, #cart .modal-footer .btn-danger').css('display', 'inline-block');
    }

    $('#cart .modal-body').html(cart);

    if ($('.cart-sum').text()) {
        $('.simpleCart_total').html($('#cart .cart-sum').text());
    } else {
        $('.simpleCart_total').text('Empty Cart');
    }

    $('#cart').modal();
}

function getCart() {
    $.ajax({
        url: '/cart/show',
        type: 'GET',
        success: function (res) {
            showCart(res);
        },
        error: function () {
            alert('Ошибка! Попробуйте позже');
        }
    });
}

function clearCart() {
    $.ajax({
        url: '/cart/clear',
        type: 'GET',
        success: function (res) {
            showCart(res);
        },
        error: function () {
            alert('Ошибка! Попробуйте позже');
        }
    });
    return false;
}