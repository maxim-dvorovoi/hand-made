jQuery(document).ready(function($){

    var cartWrapper = $('.cd-cart-container');
    //product id - you don't need a counter in your real project but you can use your real product id
    var productId = 0;


    if( cartWrapper.length > 0 ) {
        //store jQuery objects
        var cartBody = cartWrapper.find('.body')
        var cartList = cartBody.find('ul').eq(0);
        var cartTotal = cartWrapper.find('.checkout').find('span');
        var cartTrigger = cartWrapper.children('.cd-cart-trigger');
        var cartCount = cartTrigger.children('.count')
        var addToCartBtn = $('.cd-add-to-cart');
        var undo = cartWrapper.find('.undo');
        var undoTimeoutId;

        //add product to cart
        addToCartBtn.on('click', function(event){
            event.preventDefault();
            addToCart($(this));
        });

        //open/close cart
        cartTrigger.on('click', function(event){
            event.preventDefault();
            toggleCart();
        });

        //close cart when clicking on the .cd-cart-container::before (bg layer)
        cartWrapper.on('click', function(event){
            if( $(event.target).is($(this)) ) toggleCart(true);
        });

        //delete an item from the cart
        cartList.on('click', '.delete-item', function(event){
            event.preventDefault();
            removeProduct($(event.target).parents('.product'));
        });

        //update item quantity
        cartList.on('change', 'select', function(event){
            quickUpdateCart();
        });

        //reinsert item deleted from the cart
        undo.on('click', 'a', function(event){
            clearInterval(undoTimeoutId);
            event.preventDefault();
            cartList.find('.deleted').addClass('undo-deleted').one('webkitAnimationEnd oanimationend msAnimationEnd animationend', function(){
                $(this).off('webkitAnimationEnd oanimationend msAnimationEnd animationend').removeClass('deleted undo-deleted').removeAttr('style');
                quickUpdateCart();
            });
            undo.removeClass('visible');
        });
    }

    function toggleCart(bool) {
        var cartIsOpen = ( typeof bool === 'undefined' ) ? cartWrapper.hasClass('cart-open') : bool;

        if( cartIsOpen ) {
            cartWrapper.removeClass('cart-open');
            //reset undo
            clearInterval(undoTimeoutId);
            undo.removeClass('visible');
            cartList.find('.deleted').remove();

            setTimeout(function(){
                cartBody.scrollTop(0);
                //check if cart empty to hide it
                if( Number(cartCount.find('li').eq(0).text()) == 0) cartWrapper.addClass('empty');
            }, 500);
        } else {
            cartWrapper.addClass('cart-open');
        }
    }

    function addToCart(trigger) {
        var cartIsEmpty = cartWrapper.hasClass('empty');
        //update cart product list
        addProduct(trigger);
        //update number of items
        updateCartCount(cartIsEmpty);
        //update total price
        updateCartTotal(trigger.data('price'), true);
        //show cart
        cartWrapper.removeClass('empty');
    }

    function addProduct(trigger) {
        //this is just a product placeholder
        //you should insert an item with the selected product info
        //replace productId, productName, price and url with your real product info
        productId = productId + 1;
        var pricelist = trigger.data('price');
        var imglist = trigger.data('img');
        var titlelist = trigger.data('title');
        var idlist = trigger.data('id');
        var productAdded = $(`<li class="product"><div class="product-image"><a href="/user/product/${idlist}"><img src="${imglist}" alt="placeholder"></a></div><div class="product-details"><h3><a href="/user/product/${idlist}">${titlelist}</a></h3><span class="price">${pricelist} грн</span><div class="actions"><a href="#0" class="delete-item" id="${idlist}">Удалить</a></div></div></li>`);
        cartList.prepend(productAdded);
        if ($.cookie('id') != undefined) {
            var valueid = $.cookie('id');
            $.cookie('id', valueid + ',' + idlist, { path: '/' });
        } else {
            $.cookie('id', idlist, { path: '/' });
        }

    }

    function removeProduct(product) {
        var remove = $.cookie('id').split(',');
        var removeid = product.context.id;
        var one = 0;
        var j=0;
        var cooks = [];

        for (i=0; i<remove.length; i++) {
            if ( remove[i] == removeid && one == 0 ) {
                one = 1;
            } else {
                cooks [j] = remove[i];
                j++;
            }
        }

        var cook = cooks.join(',');

        if (cook != '') {
            $.cookie('id', cook, { path: '/' });
        } else {
            $.removeCookie('id', { path: '/' });
        }

        clearInterval(undoTimeoutId);
        cartList.find('.deleted').remove();

        var topPosition = product.offset().top - cartBody.children('ul').offset().top ,
            productQuantity = Number(product.find('.quantity').find('select').val()),
            productTotPrice = Number(product.find('.price').text().replace('грн', '')) * productQuantity;

        product.css('top', topPosition+'px').addClass('deleted');

        //update items count + total price
        updateCartTotal(productTotPrice, false);
        updateCartCount(true, -productQuantity);

    }

    function quickUpdateCart() {
        var quantity = 0;
        var price = 0;
    }

    function updateCartCount(emptyCart, quantity) {
        if( typeof quantity === 'undefined' ) {
            var actual = Number(cartCount.find('li').eq(0).text()) + 1;
            var next = actual + 1;

            if( emptyCart ) {
                cartCount.find('li').eq(0).text(actual);
                cartCount.find('li').eq(1).text(next);
            } else {
                cartCount.addClass('update-count');

                setTimeout(function() {
                    cartCount.find('li').eq(0).text(actual);
                }, 150);

                setTimeout(function() {
                    cartCount.removeClass('update-count');
                }, 200);

                setTimeout(function() {
                    cartCount.find('li').eq(1).text(next);
                }, 230);
            }
        } else {
            var actual = Number(cartCount.find('li').eq(0).text()) - 1;
            var next = actual + 1;

            cartCount.find('li').eq(0).text(actual);
            cartCount.find('li').eq(1).text(next);
        }
    }

    function updateCartTotal(price, bool) {
        bool ? cartTotal.text( (Number(cartTotal.text()) + Number(price)).toFixed(2) )  : cartTotal.text( (Number(cartTotal.text()) - Number(price)).toFixed(2) );
    }
});