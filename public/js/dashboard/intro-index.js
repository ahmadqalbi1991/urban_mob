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

    
    localStorage.setItem('products', JSON.stringify({
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
    }));

    //touchspin configuration for number input
    const touchSpinOptions = {
        verticalbuttons: true, 
        min: 1
    }

    /*
    Data source for whole app
    */
    const items = JSON.parse(localStorage.getItem('products'));

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
            
            return items.cart.length;
        }
    }

    /*
    All views renders into HTML file
    */
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
            
            const cartList = items.cart.map(function (product) {
                return cartTemplate(product);
            });
            cartList.length > 0 ?  parent.innerHTML = cartList.join('') : parent.innerHTML = `<p>Your cart is empty now</p>`;
        }, 
        changeHandler: function() {
            
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
            
            items.subTotalAmount > 0 ? subTotalParent.innerHTML =  priceTemplate(items.subTotalAmount) : subTotalParent.innerHTML =  0;
            items.vat > 0 ? vatParent.innerHTML =  priceTemplate(items.vat) : vatParent.innerHTML =  0;
            items.totalAmount > 0 ? totalParent.innerHTML =  priceTemplate(items.totalAmount) : totalParent.innerHTML =  0;
        }
    }

    //

    //main app
    const app = {
        init: function () {
            cartView.init();
            totalPriceView.init();
            cartView.cartCountRender();
            $(".item_count").TouchSpin(touchSpinOptions);
        }
    }

    /*
    Templates
    */

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
    app.init();


    //scroll for cart
    // $('#shopping_cart_wrapper').slimscroll({
    //     position: "right",
    //     size: "5px",
    //     height: "300",
    //     color: "transparent"
    // });

})(jQuery);
    