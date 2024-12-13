<?php



use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;



/*

|--------------------------------------------------------------------------

| API Routes

|--------------------------------------------------------------------------

|

| Here is where you can register API routes for your application. These

| routes are loaded by the RouteServiceProvider within a group which

| is assigned the "api" middleware group. Enjoy building your API!

|

*/



Route::post('register', 'API\RegisterController@register');

Route::post('login', 'API\RegisterController@login');

Route::post('login_otp', 'API\RegisterController@login_otp');

Route::post('seller_login_otp', 'API\RegisterController@seller_login_otp');

Route::post('resend_otp', 'API\RegisterController@resend_otp');

Route::post('verify_otp', 'API\RegisterController@verify_otp');

Route::post('seller_verify_otp', 'API\SellerController@seller_verify_otp');

Route::post('seller_register', 'API\SellerController@seller_register');

Route::get('contact', 'API\ApiController@get_contact');

Route::get('payment/qr', 'API\ApiController@payment_qr');

Route::get('home', 'API\ApiController@home');

Route::get('slider', 'API\ApiController@slider');

Route::get('sing-up/banner', 'API\ApiController@sing_up_banner');

Route::post('page_content', 'API\ApiController@page_content');

Route::get('customer/app/version', 'API\ApiController@version');

Route::get('vendor/app/version', 'API\ApiController@vendor_version');

Route::post('slots', 'API\SlotController@index');

Route::get('preffered_days', 'API\SlotController@preffered_days');

Route::get('all/services', 'API\ServiceController@all_service');

Route::get('service/category/{id}', 'API\ServiceController@get_service_by_category');

Route::get('all/service/name', 'API\ServiceController@all_service_name');

Route::get('/service/attributes/{id}', 'API\ServiceController@attributes')->name('service.attributes');

Route::get('service/details/new/{id}', 'API\ServiceController@service_details_new');

Route::get('service/details/{id}', 'API\ServiceController@service_details');

Route::post('search/services', 'API\ServiceController@search_service');

Route::get('search/services/trends', 'API\ServiceController@getTrendingAndRecentSearches');

Route::get('featured/services', 'API\ServiceController@featured_service');

Route::get('categories', 'API\ServiceController@all_category');
Route::get('categories_old/{id}', 'API\ServiceController@all_category_old');

Route::get('sub/category/{id}', 'API\ServiceController@sub_category');

Route::get('child/category/{service}/{sub_cat}', 'API\ServiceController@child_category');

Route::post('category/attribute', 'API\ServiceController@cate_attr');

Route::post('get/card', 'API\CardController@get_card');

Route::post('get/perticular/card', 'API\CardController@get_perticular_card');

Route::post('get/all/bookings', 'API\CardController@get_all_booking');

Route::get('get/latest/bookings', 'API\CardController@get_latest_booking');

Route::get('get/settings', 'API\ApiController@get_settings');

Route::get('get/vendor/service/{id}', 'API\CardController@get_vendor_service');

Route::delete('/service/{id}/delete-video', 'ServiceController@deleteVideo')->name('service.deleteVideo');

Route::get('remove/vendor/{id}', 'API\CardController@remove_vendor');

Route::get('get/vendor/payment/history', 'API\CardController@get_vendor_payment');

Route::post('get-all-bookings', 'API\CardController@get_all_booking');


Route::post('get/addon', 'API\ServiceController@get_addon');

Route::post('privacy-policy', 'API\ServiceController@privacy_policy');

Route::post('cms/page', 'API\ServiceController@get_page')->name('cms_page');
Route::post('cms/faq', 'API\ServiceController@get_faq')->name('get_faq');

Route::post('terms-condition', 'API\ServiceController@terms_condition');

Route::post('vendor/privacy-policy', 'API\ServiceController@vendor_privacy_policy');

Route::post('vendor/terms-condition', 'API\ServiceController@vendor_terms_condition');

Route::post('contact-us', 'API\ServiceController@contact_us');

Route::get('financial_calculation/{id}', 'API\CardController@financial_calculation');

Route::get('remove/coupon/{id}', 'API\CardController@remove_coupon');

Route::post('get/usage/coupon/count', 'API\CardController@get_coupon_use_count');

// Seller & Vendor

Route::post('login/seller', 'API\SellerController@login_otp');

Route::post('update/seller/{id}', 'API\SellerController@update_seller');

Route::post('update/seller/status/{id}', 'API\SellerController@update_seller_status');

Route::get('vendor/all/services', 'API\SellerController@all_service');

Route::get('vendor/all/service/name', 'API\SellerController@all_service_name');

Route::get('vendor/service/details/{id}', 'API\SellerController@service_details');

Route::get('vendor/featured/services', 'API\SellerController@featured_service');

Route::post('update/seller/info/{id}', 'API\SellerController@update_seller_details');

Route::get('seller/sing-up/banner', 'API\SellerController@sing_up_banner');



Route::get('vendor/notifications', 'API\ApiController@vendor_noti');

Route::get('customer/notifications', 'API\ApiController@customer_noti');

Route::get('get/tips', 'API\ApiController@get_tips');

Route::get('get/coupon', 'API\ApiController@get_coupon');

Route::post('get/particular/coupon', 'API\ApiController@get_particular_coupon');

Route::get('get/city', 'API\ApiController@get_city');

Route::post('get/locality', 'API\ApiController@get_locality');
    
Route::post('get_selected_price', 'API\CardController@get_selected_price');

Route::post('get_selected_price_by_card', 'API\CardController@get_selected_price_by_card');

Route::middleware('auth:api')->group( function () {

    Route::post('apply/coupon', 'API\ApiController@apply_coupon');

    Route::post('/test_sms','API\ApiController@test_sms');
    Route::post('/my_rewards','API\ServiceController@my_rewards');
    

    Route::post('/wallet_payment_init','API\ApiController@wallet_payment_init');
    Route::post('/wallet_init','API\ApiController@wallet_init');
    Route::post('/wallet_recharge','API\ApiController@wallet_recharge');
    Route::post('/wallet_details','API\ApiController@wallet_details');

    Route::get('payments', 'API\SellerController@get_payment');

    Route::post('confirm_details', 'API\CardController@confirm_details');

    Route::get('payout/history', 'API\SellerController@get_payout_history');

    Route::get('get/completed/booking', 'API\CardController@get_completed_booking');

    Route::get('get/active/booking', 'API\CardController@get_active_booking');

    Route::post('payment_save_api', 'API\ApiController@payment_save_api');
    
    Route::post('transections', 'API\ApiController@transections');

    Route::post('get_price', 'API\CardController@get_price');

    Route::post('card/store', 'API\CardController@store');
    
    Route::post('profile_update', 'API\RegisterController@profile_update');
    
    Route::get('remove/profile', 'API\RegisterController@remove_profile');
    
    Route::post('service/packages', 'API\ServiceController@service_packages');
    Route::post('get/user/bookings', 'API\CardController@get_user_bookings');
    Route::post('get/cards', 'API\CardController@get_bookings_by_user');
    Route::post('delete/bookings/user', 'API\CardController@delete_bookings_by_user');

    Route::get('logout', 'API\RegisterController@logout');

    Route::get('user/address', 'API\ApiController@user_address');

    Route::post('update_address/{address_id}', 'API\ApiController@update_address');

    Route::delete('delete_address/{user_id}/{address_id}', 'API\ApiController@delete_address');

    Route::post('user_info', 'API\RegisterController@user_info');

    Route::get('staff_list', 'API\SellerController@staff_list');

    Route::post('staff_added', 'API\SellerController@staff_added');

    Route::post('staff_updated/{id}', 'API\SellerController@staff_updated');
    Route::post('staff_deleted/{id}', 'API\SellerController@staff_deleted');

    Route::post('delete_user', 'API\SellerController@delete_user');

    Route::get('get/card/info/{id}', 'API\CardController@get_card_info');

    Route::post('payment/update', 'API\CardController@card_payment_update');
    Route::post('reorder', 'API\CardController@reorder');

    Route::post('checkout/data', 'API\CardController@card_checkout_data');

    Route::post('checkout/go', 'API\CardController@card_checkout_go');
    
    Route::post('transection_id/update/{id}', 'API\CardController@card_transection_id_update');

    Route::post('store/address', 'API\ApiController@add_address');

    Route::post('store/business_information', 'API\ApiController@add_or_update_business_information');

    Route::post('update/license', 'API\ApiController@update_license');

    Route::post('update/bank', 'API\ApiController@update_bank');
    
    Route::post('edit/address/{id}', 'API\ApiController@edit_address');

    Route::get('get/my/jobs', 'API\CardController@get_my_job');

    Route::post('get/my/bookings', 'API\CardController@get_my_booking');
    
    Route::post('change/slot/time', 'API\CardController@change_slot_and_date');
    
    Route::post('accept/booking', 'API\CardController@accept_booking');
    
    Route::post('started/booking', 'API\CardController@booking_started');
    
    Route::post('mark-as-arrived', 'API\CardController@mark_arrived');
    
    Route::post('payment/collected', 'API\CardController@payment_collected');
    
    Route::post('service/completed', 'API\CardController@service_completed');
    
    Route::post('new/service/completed', 'API\CardController@old_service_completed');
    
    Route::post('cod/update', 'API\CardController@cod_status');
    
    Route::post('work/done', 'API\CardController@work_done');
    
    Route::post('booking/canceled', 'API\CardController@booking_canceled');
    
    Route::post('booking/canceled/partner', 'API\CardController@booking_canceled_partner');
    
    Route::get('payment/success/{id}', 'API\CardController@paymentsuccess');
    
    Route::post('payment/done', 'API\CardController@paymentDone');
    
    Route::post('/review-submit', 'API\CardController@review')->name('review.submit');

    // Route::post('profile_update', 'API\RegisterController@profile_update');

   

});


Route::post('todayOrdersGenerate', 'API\ApiController@todayOrdersGenerate');






/* customer side api */

Route::post('vendorInfo', 'API\ApiController@vendorInfo');

Route::post('vendorSearch', 'API\ApiController@vendorSearch');

Route::post('vendorRequest', 'API\ApiController@vendorRequest');

Route::post('vendorList', 'API\ApiController@vendorList');

Route::post('myVendorList', 'API\ApiController@myVendorList');

Route::post('vendorRequestCancel', 'API\ApiController@vendorRequestCancel');

Route::post('vendorShopItems', 'API\ApiController@vendorShopItems');

Route::post('vendorPackageRequest', 'API\ApiController@vendorPackageRequest');

Route::post('PackageRequestCancel', 'API\ApiController@PackageRequestCancel');

Route::post('vendorPackageList', 'API\ApiController@vendorPackageList');

Route::post('packageItemsUpdate', 'API\ApiController@packageItemsUpdate');

Route::post('savePackageLeave', 'API\ApiController@savePackageLeave');

Route::post('savePackageAddons', 'API\ApiController@savePackageAddons');

Route::post('addonsItemsUpdate', 'API\ApiController@addonsItemsUpdate');

Route::post('packageAddonCancel', 'API\ApiController@packageAddonCancel');

Route::post('vendorPackageAddonList', 'API\ApiController@vendorPackageAddonList');



/* vendor - customer - common */

Route::post('packageInfo', 'API\ApiController@packageInfo');

Route::post('packageAddonInfo', 'API\ApiController@packageAddonInfo');

Route::post('viewPackageLeave', 'API\ApiController@viewPackageLeave');

Route::post('todayOrders', 'API\ApiController@todayOrders');

Route::post('todayOrdersItemWise', 'API\ApiController@todayOrdersItemWise');

Route::post('monthOrders', 'API\ApiController@monthOrders');

Route::post('invoices', 'API\ApiController@invoices');


Route::post('walletBalance', 'API\ApiController@walletBalance');

Route::post('pauseSubscriptionPackage', 'API\ApiController@pauseSubscriptionPackage');

Route::post('resumeSubscriptionPackage', 'API\ApiController@resumeSubscriptionPackage');






Route::any('allData','API\ApiController@allData');

// V1 APIs

Route::group(['prefix' => 'V1'], function () {

    Route::post('tabby/payment/success', 'API\V1\PaymentController@tabby_success');

    Route::post('tabby/payment/failure', 'API\V1\PaymentController@tabby_failure');

    Route::post('get/rating', 'API\V1\CommonController@get_rating');

    Route::get('resend/payment/link/{id}', 'API\V1\CommonController@resend_payment_link');

    Route::get('service/complete/approval/{id}', 'API\V1\CommonController@service_complete_approval');

    Route::post('payment-success', 'API\V1\PaymentController@paySuccess');

    Route::post('payment-failure', 'API\V1\PaymentController@payFailure');

    Route::post('cms/page', 'CMS@get_page')->name('cms_page');
});

Route::get('/test-notification', 'API\V1\PaymentController@testNoti');