/**
 * **************************************************
 * ******* Name: drora
 * ******* Description: Bootstrap 4 Admin Dashboard
 * ******* Version: 1.0.0
 * ******* Released on 2019-02-08 15:41:24
 * ******* Support Email : quixlab.com@gmail.com
 * ******* Support Skype : sporsho9
 * ******* Author: Quixlab
 * ******* URL: https://quixlab.com
 * ******* Themeforest Profile : https://themeforest.net/user/quixlab
 * ******* License: ISC
 * ***************************************************
 */

(function() {
    "use strict"

    //touchspin configuration for number input
    const touchSpinOptions = {
        verticalbuttons: true, 
        min: 1
    }

    /*
    Data source for whole app
    */
    const items = {
        products: [
            {
                id: 101,
                name: 'Chicken Roll',
                desc: 'Apple Juice, Beef Roast, Cheese Burger', 
                price: 10,
                quantity: null, 
                image: './../../assets/images/menu/granny-menu12.jpg', 
                area: 'Bristol, Boston', 
                availability: true
            },
            {
                id: 102,
                name: 'Chicken Grill',
                desc: 'Apple Juice, Beef Roast, Cheese Burger', 
                price: 20,
                quantity: null, 
                image: './../../assets/images/menu/granny-menu5.jpg', 
                area: 'Bristol, Boston', 
                availability: true
            },
            {
                id: 103,
                name: 'Cold Coffee',
                desc: 'Apple Juice, Beef Roast, Cheese Burger', 
                price: 30,
                quantity: null, 
                image: './../../assets/images/menu/granny-menu6.jpg', 
                area: 'Bristol, Boston', 
                availability: false
            },
            {
                id: 104,
                name: 'Fried Egg Sandwich',
                desc: 'Apple Juice, Beef Roast, Cheese Burger', 
                price: 5,
                quantity: null, 
                image: './../../assets/images/menu/granny-menu11.jpg', 
                area: 'Bristol, Boston', 
                availability: true
            },
            {
                id: 105,
                name: 'French Crostini',
                desc: 'Apple Juice, Beef Roast, Cheese Burger', 
                price: 20,
                quantity: null, 
                image: './../../assets/images/menu/granny-menu10.jpg', 
                area: 'Bristol, Boston', 
                availability: false
            },
            {
                id: 106,
                name: 'French Crostini',
                desc: 'Apple Juice, Beef Roast, Cheese Burger', 
                price: 20,
                quantity: null, 
                image: './../../assets/images/menu/granny-menu8.jpg', 
                area: 'Bristol, Boston', 
                availability: true
            },
            {
                id: 107,
                name: 'Chicken Grill',
                desc: 'Apple Juice, Beef Roast, Cheese Burger', 
                price: 20,
                quantity: null, 
                image: './../../assets/images/menu/granny-menu5.jpg', 
                area: 'Bristol, Boston', 
                availability: true
            },
            {
                id: 108,
                name: 'Cold Coffee',
                desc: 'Apple Juice, Beef Roast, Cheese Burger', 
                price: 30,
                quantity: null, 
                image: './../../assets/images/menu/granny-menu6.jpg', 
                area: 'Bristol, Boston', 
                availability: false
            },
            {
                id: 109,
                name: 'Fried Egg Sandwich',
                desc: 'Apple Juice, Beef Roast, Cheese Burger', 
                price: 5,
                quantity: null, 
                image: './../../assets/images/menu/granny-menu11.jpg', 
                area: 'Bristol, Boston', 
                availability: true
            },
            {
                id: 110,
                name: 'French Crostini',
                desc: 'Apple Juice, Beef Roast, Cheese Burger', 
                price: 20,
                quantity: null, 
                image: './../../assets/images/menu/granny-menu10.jpg', 
                area: 'Bristol, Boston', 
                availability: false
            },
            {
                id: 111,
                name: 'French Crostini',
                desc: 'Apple Juice, Beef Roast, Cheese Burger', 
                price: 20,
                quantity: null, 
                image: './../../assets/images/menu/granny-menu8.jpg', 
                area: 'Bristol, Boston', 
                availability: true
            },
            {
                id: 112,
                name: 'French Crostini',
                desc: 'Apple Juice, Beef Roast, Cheese Burger', 
                price: 20,
                quantity: null, 
                image: './../../assets/images/menu/granny-menu8.jpg', 
                area: 'Bristol, Boston', 
                availability: true
            }
        ],
        cart: [],
        subTotalAmount: 0, 
        vat: 0, 
        totalAmount: 0
    }
    
    //set the data into localstorage
    localStorage.setItem('products', JSON.stringify(items));

    /*
    Whole app controller
    */
    const controller = {
        addCart: function (ID, quantity) {
            quantity = quantity || 1;

            for(let i = 0; i < items.cart.length; i++) {
                if(items.cart[i].id === ID) {
                    this.increamentQuantity(ID);
                    this.countSubTotalAmount();
                    this.countVat();
                    this.countTotalAmount();
                    this.updateProductBill(ID);
                    cartView.init();
                    totalPriceView.init();
                    $(".item_count").TouchSpin(touchSpinOptions);
                    localStorage.setItem('products', JSON.stringify(items));
                    return;
                }
            }

            items.products.forEach(function (product) {
                if (parseInt(product.id) === ID) {
                    product.quantity = quantity;
                    product.totalBill = product.price;
                    items.cart.push(product)
                }
            });
            this.countSubTotalAmount();
            this.countVat();
            this.countTotalAmount();
            cartView.init();
            totalPriceView.init();
            $(".item_count").TouchSpin(touchSpinOptions);
            localStorage.setItem('products', JSON.stringify(items));
        }, 
        removeCart: function (ID) {
            items.cart.forEach(function (product) {
                if (parseInt(product.id) === ID) {
                    items.cart.splice(items.cart.indexOf(product), 1);
                }
            });
            cartView.init();
            $(".item_count").TouchSpin(touchSpinOptions);
            localStorage.setItem('products', JSON.stringify(items));
        }, 
        increamentQuantity: function (ID) {
            items.cart.forEach(function (product) {
                if (parseInt(product.id) === ID) {
                    product.quantity = parseInt(product.quantity) + 1;
                }
            });
            totalPriceView.init();
        }, 
        decreaseQuantity: function (ID) {
            items.cart.forEach(function (product) {
                if (parseInt(product.id) === ID) {
                    product.quantity = parseInt(product.quantity) - 1;
                }
            });
            totalPriceView.init();
        }, 
        changeQuantity: function(ID, value) {
            items.cart.forEach(function(product) {
                if(parseInt(product.id) === ID) {
                    product.quantity = value;
                }
            });
            this.updateProductBill(ID);
            this.countSubTotalAmount();
            this.countVat();
            this.countTotalAmount();
            localStorage.setItem('products', JSON.stringify(items));
        }, 
        countSubTotalAmount: function () {
            items.subTotalAmount = items.cart.reduce(function (total, item) {
                return (item.price * item.quantity) + total;
            }, 0);
            localStorage.setItem('products', JSON.stringify(items));
            return items.subTotalAmount;
        }, 
        updateProductBill: function(ID) {
            items.cart.forEach(function(item) {
                if(item.id === ID) {
                    item.totalBill = item.price * item.quantity;
                }
            });
            localStorage.setItem('products', JSON.stringify(items));
        }, 
        clearCart: function() {
            items.cart = [];
            this.countSubTotalAmount();
            this.countVat();
            this.countTotalAmount();
            localStorage.setItem('products', JSON.stringify(items));
        }, 
        countVat: function() {
            const subtotal = this.countSubTotalAmount();
            return items.vat = subtotal * .15;
            localStorage.setItem('products', JSON.stringify(items));
        }, 
        countTotalAmount: function() {
            items.totalAmount = items.subTotalAmount + items.vat;
            localStorage.setItem('products', JSON.stringify(items));
        }, 
        countAddedCarts: function() {
            const items = JSON.parse(localStorage.getItem('products'));
            return items.cart.length;
        }
    }

    /*
    All views renders into HTML file
    */

    //product renders from here
    const productView = {
        init: function (parent) {
            this.parent = parent;
            this.render(parent);

            $(document).on('click', '.add_btn', this.addCartHandler);
        },
        render: function (parent) {
            const items = JSON.parse(localStorage.getItem('products'));
            const productsList = items.products.map(function (product) {
                return productTemplate(product);
            });
            parent.innerHTML = productsList.join('');
        },
        addCartHandler: function () {
            const ID = parseInt($(this).attr('data-id'));
            controller.addCart(ID);
            cartView.cartCountRender();

            //Toastr for successfull alert
            toastr.success("Successfully added to cart!", {
                timeOut: 2000,
                closeButton: !0,
                debug: !1,
                newestOnTop: !0,
                progressBar: !0,
                positionClass: "toast-bottom-right demo_rtl_class",
                preventDuplicates: !0,
                onclick: null,
                showDuration: "300",
                hideDuration: "1000",
                extendedTimeOut: "1000",
                showEasing: "swing",
                hideEasing: "linear",
                showMethod: "fadeIn",
                hideMethod: "fadeOut",
                tapToDismiss: !1, 
                closeHtml: '<button><i class="ti-close"></i></button>'
            });
        }
    }

    //cart renders from here
    const cartView = {
        init: function () {
            // this.parent = parent;
            this.render(document.querySelector('#shopping_cart'));
            this.clearCartHandler();
            // console.log(controller.countSubTotalAmount());
            // console.log(controller.countVat());
            $(document).on('change', '.item_count', this.changeHandler);
            $(document).on('click', '.remove_cart', this.cartRemoveHandler);
        },
        render: function (parent) {
            const items = JSON.parse(localStorage.getItem('products'));
            const cartList = items.cart.map(function (product) {
                return cartTemplate(product);
            });
            cartList.length > 0 ?  parent.innerHTML = cartList.join('') : parent.innerHTML = `<p>Your cart is empty now</p>`;
        }, 
        changeHandler: function() {
            const items = JSON.parse(localStorage.getItem('products'));
            const ID = parseInt($(this).attr('data-id'));
            const value = this.value;
            controller.changeQuantity(ID, value);
            const index = items.cart.findIndex(element => element.id === ID);
            $(this).parents('li').children('.item_bill')[0].innerHTML = `$${parseInt(items.cart[index].totalBill)}`;
            totalPriceView.init();
        }, 
        cartRemoveHandler: function() {
            const ID = parseInt($(this).attr('data-id'));
            controller.removeCart(ID);
            controller.countSubTotalAmount();
            controller.countVat();
            controller.countTotalAmount();
            totalPriceView.init();
        }, 
        clearCartHandler: function() {
            $('#clear_cart').on('click', function() {
                controller.clearCart();
                cartView.init();
                totalPriceView.init();
            });
        }, 
        cartCountRender: function() {
            const cartCount = parseInt(controller.countAddedCarts());
            const countContainer = $('#cart_count')[0];
            cartCount > 0 ? countContainer.innerHTML = cartCount : countContainer.innerHTML = 0;
        }
    }

    //total price renders from here
    const totalPriceView = {
        init: function () {
            // this.parent = parent;
            this.render(
                document.querySelector('#total_price'), 
                document.querySelector('#subtotal_price'), 
                document.querySelector('#vat_count')
            );
        },
        render: function (totalParent, subTotalParent, vatParent) {
            const items = JSON.parse(localStorage.getItem('products'));
            items.subTotalAmount > 0 ? subTotalParent.innerHTML =  priceTemplate(items.subTotalAmount) : subTotalParent.innerHTML =  0;
            items.vat > 0 ? vatParent.innerHTML =  priceTemplate(items.vat) : vatParent.innerHTML =  0;
            items.totalAmount > 0 ? totalParent.innerHTML =  priceTemplate(items.totalAmount) : totalParent.innerHTML =  0;
        }
    }

    //

    //main app
    const app = {
        init: function (productContainer) {
            productView.init(productContainer);
            cartView.init();
            totalPriceView.init();
            cartView.cartCountRender();
            $(".item_count").TouchSpin(touchSpinOptions);
        }
    }

    /*
    Templates
    */

    //template for product render
    function productTemplate(product) {
        return (`
            <div class="col-xl-3 col-xxl-6 col-md-6">
                <div class="card vertical-card__menu">
                    <span class="ribbon ribbon__one vertical-card__menu--status ${product.availability ? '' : "closed"}">${product.availability ? "Available" : "Closed"} <em class="ribbon-curve"></em></span>
                    <div class="card-header p-0">
                        <div class="vertical-card__menu--image">
                            <img src=${product.image} alt="Menu">
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="vertical-card__menu--desc p-3">
                            <div class="d-flex justify-content-between">
                                <h4 class="vertical-card__menu--title">${product.name}</h4>
                                <div class="vertical-card__menu--fav">
                                    <a href="javascript:void()"><i class="fa fa-heart-o"></i></a>
                                </div>
                            </div>
                            <p>${product.desc}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="vertical-card__menu--rating c-pointer">
                                    <span class="icon"><i class="fa fa-star"></i></span>
                                    <span class="icon"><i class="fa fa-star"></i></span>
                                    <span class="icon"><i class="fa fa-star"></i></span>
                                    <span class="icon"><i class="fa fa-star"></i></span>
                                    <span class="icon"><i class="fa fa-star-o"></i></span>
                                </div>
                                <h2 class="vertical-card__menu--price">$<span>${product.price}</span></h2>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <div class="vertical-card__menu--location">
                            <div class="d-block">
                                <span class="icon"><i class="fa fa-map-marker"></i></span>
                                ${product.area}
                            </div>
                            <div>
                                <span class="icon"><i class="fa fa-motorcycle"></i></span>
                                <span>10 min</span>
                                <span class="icon ml-2"><i class="fa fa-clock-o"></i></span>
                                <span>15min</span>
                            </div>
                        </div>
                        <div class="vertical-card__menu--button">
                            <button ${product.availability ? '' : 'disabled'} class="btn btn-primary add_btn" data-id=${product.id}>Add Cart</button>
                        </div>
                    </div>
                </div>
            </div>
            `);
    }

    //template for cart render
    function cartTemplate(product) {
        let i = 1;
        return `
            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h5 class="item_name m-0">${product.name}</h5>
                <div class="number_input">
                    <input type="number" data-id=${parseInt(product.id)} class="item_count form-control" value=${product.quantity} min="1">
                </div>
                <div class="item_price text-center pl-2">$${product.price}</div>
                <div class="item_bill text-center" data-id=${parseInt(product.id)}>$${product.totalBill}</div>
                <div class="remove_button text-center">
                    <button class="btn remove_cart" data-id=${parseInt(product.id)}><i class="ti-close"></i></button>
                </div>
            </li>
        `
    }

    //template for total price render
    function priceTemplate(total) {
        return `<span>$${total}</span>`;
    }




    //initiate the whole app
    app.init(document.querySelector('#products'));


    //scroll for cart
    // $('#shopping_cart_wrapper').slimscroll({
    //     position: "right",
    //     size: "5px",
    //     height: "300",
    //     color: "transparent"
    // });

})(jQuery);

