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



jQuery(".form-valide").validate({

    rules: {

        "val-username": {

            required: !0,

            minlength: 3

        },

        "val-email": {

            required: !0,

            email: !0

        },

        "val-password": {

            required: !0,

            minlength: 5

        },

        "val-confirm-password": {

            required: !0,

            equalTo: "#val-password"

        },

        "val-select2": {

            required: !0

        },

        "val-select2-multiple": {

            required: !0,

            minlength: 2

        },

        "val-suggestions": {

            required: !0,

            minlength: 5

        },

        "val-skill": {

            required: !0

        },

        "val-currency": {

            required: !0,

            currency: ["$", !0]

        },

        "val-website": {

            required: !0,

            url: !0

        },

        "val-phoneus": {

            required: !0,

            phoneUS: !0

        },

        "val-digits": {

            required: !0,

            digits: !0

        },

        "val-number": {

            required: !0,

            number: !0

        },

        "val-range": {

            required: !0,

            range: [1, 5]

        },

        "val-terms": {

            required: !0

        }

    },

    messages: {

        "val-username": {

            required: "Please enter a username",

            minlength: "Your username must consist of at least 3 characters"

        },

        "val-email": "Please enter a valid email address",

        "val-password": {

            required: "Please provide a password",

            minlength: "Your password must be at least 5 characters long"

        },

        "val-confirm-password": {

            required: "Please provide a password",

            minlength: "Your password must be at least 5 characters long",

            equalTo: "Please enter the same password as above"

        },

        "val-select2": "Please select a value!",

        "val-select2-multiple": "Please select at least 2 values!",

        "val-suggestions": "What can we do to become better?",

        "val-skill": "Please select a skill!",

        "val-currency": "Please enter a price!",

        "val-website": "Please enter your website!",

        "val-phoneus": "Please enter a US phone!",

        "val-digits": "Please enter only digits!",

        "val-number": "Please enter a number!",

        "val-range": "Please enter a number between 1 and 5!",

        "val-terms": "You must agree to the service terms!"

    },



    ignore: [],

    errorClass: "invalid-feedback animated fadeInUp",

    errorElement: "div",

    errorPlacement: function(e, a) {

        jQuery(a).parents(".form-group > div").append(e)

    },

    highlight: function(e) {

        jQuery(e).closest(".form-group").removeClass("is-invalid").addClass("is-invalid")

    },

    success: function(e) {

        jQuery(e).closest(".form-group").removeClass("is-invalid"), jQuery(e).remove()

    },

});





jQuery(".form-valide-with-icon").validate({

    rules: {

        "val-username": {

            required: !0,

            minlength: 3

        },

        "val-password": {

            required: !0,

            minlength: 5

        }

    },

    messages: {

        "val-username": {

            required: "Please enter a username",

            minlength: "Your username must consist of at least 3 characters"

        },

        "val-password": {

            required: "Please provide a password",

            minlength: "Your password must be at least 5 characters long"

        }

    },



    ignore: [],

    errorClass: "invalid-feedback animated fadeInUp",

    errorElement: "div",

    errorPlacement: function(e, a) {

        jQuery(a).parents(".form-group > div").append(e)

    },

    highlight: function(e) {

        jQuery(e).closest(".form-group").removeClass("is-invalid").addClass("is-invalid")

    },

    success: function(e) {

        jQuery(e).closest(".form-group").removeClass("is-invalid").addClass("is-valid")

    }



});



// jQuery('.vendor_register').each( function(){

//    jQuery(this).validate();

// });



jQuery("#vendor_register").validate({

    rules: {

      name: "required",

      email: {

        required: true,

        email: true

      },

       phone: {

        required: true,

        digits: true

      },

      password: {

        required: true,

        minlength: 5

      }

    },

    //Specify validation error messages

    messages: {

      name: "Please enter your fullname",

      password: {

        required: "Please provide a password",

        minlength: "Your password must be at least 5 characters long"

      },

      email: "Please enter a valid email address",

      phone: "Please enter a valid phone number"

    },

    ignore: [],

    errorClass: "invalid-feedback animated fadeInUp",

    highlight: function(e) {

        jQuery(e).closest(".form-group").removeClass("is-invalid").addClass("is-invalid")

    },

    success: function(e) {

        jQuery(e).closest(".form-group").removeClass("is-invalid"), jQuery(e).remove()

    },

    submitHandler: function(form) {

      form.submit();

    }

});



jQuery("#customer_register").validate({

    rules: {

      name: "required",

      email: {

        required: true,

        email: true

      },

       phone: {

        required: true,

        digits: true

      },

      password: {

        required: true,

        minlength: 5

      }

    },

    //Specify validation error messages

    messages: {

      name: "Please enter your fullname",

      password: {

        required: "Please provide a password",

        minlength: "Your password must be at least 5 characters long"

      },

      email: "Please enter a valid email address",

      phone: "Please enter a valid phone number"

    },

    ignore: [],

    errorClass: "invalid-feedback animated fadeInUp",

    highlight: function(e) {

        jQuery(e).closest(".form-group").removeClass("is-invalid").addClass("is-invalid")

    },

    success: function(e) {

        jQuery(e).closest(".form-group").removeClass("is-invalid"), jQuery(e).remove()

    },

    submitHandler: function(form) {

      form.submit();

    }

});



jQuery("#item_register").validate({

    rules: {

      name: {

        required: true,

        minlength: 2

      },

      // unit: "required",

      brand: "required",

    },

    //Specify validation error messages

    messages: {

      // unit: "Please enter item unit",

      brand: "Please enter item brand",

      name: {

        required: "Please enter item name",

        minlength: "Your item must be at least 2 characters long"

      }

    },

    ignore: [],

    errorClass: "invalid-feedback animated fadeInUp",

    highlight: function(e) {

        jQuery(e).closest(".form-group").removeClass("is-invalid").addClass("is-invalid")

    },

    success: function(e) {

        jQuery(e).closest(".form-group").removeClass("is-invalid"), jQuery(e).remove()

    },

    submitHandler: function(form) {

      form.submit();

    }

});



jQuery("#admin_profile_setting").validate({

    rules: {

      name: {

        required: true,

        minlength: 2

      },

      phone: {

        required: true,

        digits: true

      }

    },

    messages: {

        phone: "Please enter a valid phone number!",

        name: {

            required: "Please enter item name!",

            minlength: "Your item must be at least 2 characters long!"

      }

    },

    ignore: [],

    errorClass: "invalid-feedback animated fadeInUp",

    highlight: function(e) {

        jQuery(e).closest(".form-group").removeClass("is-invalid").addClass("is-invalid")

    },

    success: function(e) {

        jQuery(e).closest(".form-group").removeClass("is-invalid"), jQuery(e).remove()

    },

    submitHandler: function(form) {

      form.submit();

    }

});



jQuery("#user_profile_setting").validate({

    rules: {

      name: {

        required: true,

        minlength: 2

      },

      phone: {

        required: true,

        digits: true

      },

      address: "required",

      city: "required",

    },

    messages: {

        name: {

            required: "Please enter item name!",

            minlength: "Your item must be at least 2 characters long!"

      },

      phone: "Please enter a valid phone number!",

      address: "Please enter address!",

      city: "Please enter city!"

    },

    ignore: [],

    errorClass: "invalid-feedback animated fadeInUp",

    highlight: function(e) {

        jQuery(e).closest(".form-group").removeClass("is-invalid").addClass("is-invalid")

    },

    success: function(e) {

        jQuery(e).closest(".form-group").removeClass("is-invalid"), jQuery(e).remove()

    },

    submitHandler: function(form) {

      form.submit();

    }

});



jQuery("#password_setting").validate({

    rules: {

      current_password: {

        required: true,

        minlength: 5

      },

      new_password: {

        required: true,

        minlength: 5

      },

      confirm_new_password: {

        required: true,

        equalTo: "#new_password"

      },

    },

    messages: {

        name: {

            required: "Please enter item name!",

            minlength: "Your item must be at least 2 characters long!"

      },

      phone: "Please enter a valid phone number!",

      address: "Please enter address!",

      current_password: {

        required: "Please provide a current password",

        minlength: "Your current password must be at least 5 characters long"

    },

      new_password: {

        required: "Please provide a new password",

        minlength: "Your password must be at least 5 characters long"

    },

    confirm_new_password: {

        required: "Please provide a password",

        minlength: "Your password must be at least 5 characters long",

        equalTo: "Please enter the same new password as above"

    },

    },

    ignore: [],

    errorClass: "invalid-feedback animated fadeInUp",

    highlight: function(e) {

        jQuery(e).closest(".form-group").removeClass("is-invalid").addClass("is-invalid")

    },

    success: function(e) {

        jQuery(e).closest(".form-group").removeClass("is-invalid"), jQuery(e).remove()

    },

    submitHandler: function(form) {

      form.submit();

    }

});



jQuery("#shop_setting").validate({

  rules: {

    shop_name: {

      required: true,

      minlength: 2

    },

    shop_phone: {

      required: true,

      digits: true

    },

    shop_email: {

      required: true,

      email: true

    },

    address: "required",

    city: "required",

    pincode: "required",

    GSTIN: "required",

    UPI: "required",

  },

  messages: {

      shop_name: {

          required: "Please enter item name!",

          minlength: "Your item must be at least 2 characters long!"

    },

    shop_email: "Please enter a valid email address!",

    shop_phone: "Please enter a valid phone number!",

    address: "Please enter address!",

    city: "Please enter city!",

    pincode: "Please enter pincode!",

    GSTIN: "Please enter GSTIN!",

    UPI: "Please enter UPI!",

  },

  ignore: [],

  errorClass: "invalid-feedback animated fadeInUp",

  highlight: function(e) {

      jQuery(e).closest(".form-group").removeClass("is-invalid").addClass("is-invalid")

  },

  success: function(e) {

      jQuery(e).closest(".form-group").removeClass("is-invalid"), jQuery(e).remove()

  },

  submitHandler: function(form) {

    form.submit();

  }

});