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

(function($) {
    "use strict"

    //steps
    var form = $("#step-form-horizontal");
    form.children('div').steps({
        headerTag: "h4",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        autoFocus: true, 
        onStepChanging: function (event, currentIndex, newIndex)
        {
            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        }, 
        labels: {
            finish: "Confirm"
        }, 
        titleTemplate: '<span class="step-name">#index#</span>', 
        onFinishing: function(e) {
            window.location.href = './form-submit.html'
        }
    });


    //payment methods
    $(document).on('click', '.payment_method', function() {
        const ID = $(this).attr('data-method');

        $('.payment_methods').css('display', 'none');

        $(`#${ID}`).css('display', 'flex');
    });


    $('.payment_method_form_back').on('click', function(e) {
        e.preventDefault();
        const thisForm = $(this).parents('.payment_method_form');

        thisForm.css('display', 'none');

        $('.payment_methods').css('display', 'flex');
    });


    // $('.shipping-method').on('click', function() {
    //     $('.shipping-method').removeClass('animated tada');
    //     $(this).addClass('animated tada');
    // });


})(jQuery);