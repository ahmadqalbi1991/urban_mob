<?php



use Illuminate\Support\Facades\Route;



/*

|--------------------------------------------------------------------------

| Web Routes

|--------------------------------------------------------------------------

|

| Here is where you can register web routes for your application. These

| routes are loaded by the RouteServiceProvider within a group which

| contains the "web" middleware group. Now create something great!

|

*/



// Route::get('/', function () {

//     return view('home');

// });



// Route::get('/login', function () {

//     return view('auth/login');

// });
Route::get('clear', function() {
    Artisan::call('cache:clear');
	Artisan::call('config:cache');
});


Route::get('/login', 'Auth\AuthController@login')->name('login');

Route::post('/login', 'Auth\AuthController@authenticate');

Route::get('logout', 'Auth\AuthController@logout')->name('logout');

Route::get('/forgot-password', 'Auth\AuthController@forgot_password')->name('forgot.password');

Route::post('/forgot-password', 'Auth\AuthController@forgot_password_process')->name('forgot.password.process');

Route::get('/reset-password/{token}', 'Auth\AuthController@reset_password')->name('reset.password');

Route::post('/reset-password', 'Auth\AuthController@reset_password_process')->name('reset.password.process'); 



/* admin  */



Route::group(['middleware' => ['auth','role:admin']], function () {



	Route::get('dashboard','DashboardController@index')->name('home');



	Route::get('/settings', 'DashboardController@settings')->name('settings');

	Route::get('/setting1', 'DashboardController@setting1')->name('setting1');

	Route::get('/setting2', 'DashboardController@setting2')->name('setting2');

	Route::get('/setting3', 'DashboardController@setting3')->name('setting3');

	Route::get('/vendor/setting3', 'DashboardController@vendor_setting3')->name('vendor.setting3');

	Route::get('/page/create', 'DashboardController@page_create')->name('page.create');

	Route::get('/vendor/page/create', 'DashboardController@vendor_page_create')->name('vendor.page.create');

	Route::post('/store/page', 'DashboardController@store_page')->name('page.store');

	Route::post('/setting/{id}/type/{type}', 'DashboardController@setting_save')->name('setting.save');

	Route::post('/setting/ads/{id}}', 'DashboardController@setting_ads_save')->name('setting.ads.save');

	Route::post('/setting/video/{id}}', 'DashboardController@setting_video_save')->name('setting.video.save');

	Route::post('/setting/img/{id}}', 'DashboardController@setting_img_save')->name('setting.img.save');

	Route::post('/setting_update', 'DashboardController@setting_update')->name('setting_update');



	Route::get('/items', 'ItemController@index')->name('items');

	Route::post('/item/store', 'ItemController@item_add')->name('item.store');

	Route::post('/item/edit/{item}', 'ItemController@item_update')->name('item.update');

	Route::get('/item/{item}/status/{status}', 'ItemController@item_status')->name('item.status');


	Route::get('/vendors','UserController@vendors')->name('vendors');



	Route::get('/vendor/notifications','NotificationController@index');

	Route::get('/delete/notification/{id}','NotificationController@delete')->name('notifications.delete');

	Route::get('/customer/notifications','NotificationController@customer');

	Route::post('/send/notification','NotificationController@send_noti');

	Route::get('/vendor/details/{id}/{type?}','UserController@vendor_profile')->name('vendor.details');

	Route::get('/pay-out-history/{id}/{type?}','UserController@pay_out_history');

	Route::get('/vendor/payment/{id}/{type?}','UserController@vendor_payment');

	Route::get('/create/vendor/payment/{id}/{type?}','UserController@create_vendor_payment');

	Route::post('/store/vendor/payment','UserController@store_vendor_payment');

	Route::get('/vendor/edit/{id}','UserController@vendor_edit')->name('vendor.edit');

	Route::get('/vendor/view/{id}','UserController@vendor_view')->name('vendor.view');

	Route::get('/operators','UserController@operators')->name('operators');

	Route::post('/vendor/store', 'UserController@vendor_add')->name('vendor.store');

	Route::post('/vendor/edit/{id?}', 'UserController@vendor_update')->name('vendor.update');

	Route::get('/vendor/{vendor}/status/{status}', 'UserController@vendor_status')->name('vendor.status');

	Route::get('/vendor/verified/{id}', 'UserController@vendor_verified')->name('vendor.verified');

	Route::get('/vendor/detail/{user}', 'UserController@vendor_detail')->name('vendor.detail');

	Route::get('/vendor/delete/{user}', 'UserController@delete')->name('vendor.delete');


	Route::get('/partner/details/report', 'ReportController@partner_details_report');

	Route::get('/customer/details/report', 'ReportController@customer_details_report');

	Route::get('/revenue/bookings/report', 'ReportController@revenue_bookings_report');

	Route::get('/partner/settlement/report', 'ReportController@partner_settlement_report');



	Route::get('/customers', 'UserController@customers')->name('customers');	

	Route::post('/customer/store', 'UserController@customer_add')->name('customer.store');

	Route::post('/customer/edit/{user}', 'UserController@customer_update')->name('customer.update');

	Route::get('/users', 'UserController@users')->name('users');

	Route::post('/user/store', 'UserController@user_add')->name('user.store');

	Route::get('/customer/{customer}/status/{status}', 'UserController@customer_status')->name('customer.status');

	Route::get('/customer/verified/{id}', 'UserController@customer_verified')->name('customer.verified');

	Route::get('/customer/detail/{id}', 'UserController@customer_detail')->name('customer.detail');

	Route::get('/profile-setting', 'ProfileController@profile_setting')->name('profile.setting');

	Route::post('/profile-save', 'ProfileController@profile_save')->name('profile.save');

	Route::post('/password-save', 'ProfileController@password_save')->name('password.save');

	Route::get('/member/request','DashboardController@member_request' )->name('member.request');


	Route::get('/category', 'CategoryController@index')->name('category');

	Route::get('/sub-category', 'CategoryController@sub_index')->name('subcategory');

	Route::get('/category/create', 'CategoryController@create')->name('category.create');

	Route::get('/sub/category/create', 'CategoryController@subCatCreate')->name('sub.category.create');

	Route::post('/get/sub/category', 'CategoryController@get_sub_category')->name('get.sub_category');

	Route::post('/get/child/category', 'CategoryController@get_child_category')->name('get.child_category');

	Route::post('/category/store', 'CategoryController@store')->name('category.store');

	Route::post('/category/update/{id}', 'CategoryController@update')->name('category.update');

	Route::get('/category/edit/{id}', 'CategoryController@edit')->name('category.edit');

	Route::get('/category/delete/{id}', 'CategoryController@delete')->name('category.delete');

	Route::get('/category/{category}/status/{status}', 'CategoryController@status')->name('category.status');


	Route::resource('child-category','ChildCategoryController');

	Route::get('/child-category/delete/{id}', 'ChildCategoryController@destroy')->name('child.category.delete');


	Route::resource('slots','SlotController');

	Route::get('/slots/delete/{id}', 'SlotController@destroy')->name('slots.delete');


	Route::resource('service','ServiceController');

	Route::get('/service/delete/{id}', 'ServiceController@destroy')->name('service.delete');
	
	Route::get('/service/attributes/{id}', 'ServiceController@attributes')->name('service.attributes');

	Route::get('/create/service/attribute/{id}', 'ServiceController@create_attributes');
	
	Route::post('/store/service/attribute', 'ServiceController@store_service_attribute');

	Route::get('/edit/service/attribute/{id}', 'ServiceController@edit_attributes');

	Route::post('/update/service/attribute/{id}', 'ServiceController@update_attributes');
	
	Route::get('/delete/service/attribute/{id}', 'ServiceController@delete_attributes');
	
	Route::post('/get/attr/val', 'ServiceController@add_more_choice_option')->name('get.attr_val');

	Route::post('/delete/atr/item', 'ServiceController@delete_atr_item')->name('get.delete.atr.item');

	Route::post('get/remove/service/attr', 'ServiceController@remove_service_attr')->name('get.remove.service.attr');

	Route::post('get/delete/gallery', 'ServiceController@remove_gallery')->name('get.delete.gallery');

	Route::get('/service/attribute/items/list/{attribute}/{service}', 'ServiceController@attribute_items');

	Route::get('/manage/service/attribute/addon/{attribute}/{service}/{attribute_item}', 'AddonController@attribute_addon');

	Route::get('coupons', 'ServiceController@coupons')->name('coupons');

	Route::get('create/coupon', 'ServiceController@create_coupon');

	Route::post('store/coupon', 'ServiceController@store_coupon');

	Route::get('edit/coupon/{id}', 'ServiceController@edit_coupon');

	Route::get('status/coupon/{id}/{status}', 'ServiceController@change_coupon');

	Route::post('update/coupon', 'ServiceController@update_coupon');

	Route::get('delete/coupon/{id}', 'ServiceController@delete_coupon');


	Route::get('/brand', 'BrandController@index')->name('brand');

	Route::get('/brand/create', 'BrandController@create')->name('brand.create');

	Route::post('/brand/store', 'BrandController@store')->name('brand.store');

	Route::post('/brand/update/{id}', 'BrandController@update')->name('brand.update');

	Route::get('/brand/edit/{id}', 'BrandController@edit')->name('brand.edit');

	Route::get('/brand/delete/{id}', 'BrandController@delete')->name('brand.delete');

	Route::get('/brand/{brand}/status/{status}', 'BrandController@status')->name('brand.status');


	// Route::get('/bookings', 'CardController@index')->name('bookings');
	Route::get('/bookings', 'CardController@index_new')->name('bookings');

	Route::get('/bookings/new', 'CardController@index_new')->name('bookings.new');

	Route::get('/bookings/search', 'CardController@search_booking')->name('bookings.search');

	Route::post('/get/bookings', 'CardController@get_bookings')->name('get.bookings');

	Route::get('/draft/bookings', 'CardController@draft_bookings')->name('draft.bookings');

	Route::get('/booking/view/{id}', 'CardController@view')->name('booking.view');

	Route::get('/booking/completed/{id}', 'CardController@service_completed')->name('booking.completed');

	Route::get('/booking/invoice/{id}', 'CardController@invoice')->name('booking.invoice');

	Route::get('/booking/delete/{id}', 'CardController@delete')->name('booking.delete');

	Route::post('/change/vendor', 'CardController@change_vendor')->name('change.vendor');

	Route::get('/booking/cancel/{id}', 'CardController@cencal_booking')->name('booking.cencal');

	Route::get('/review', 'CardController@review')->name('review');

	Route::get('/change/booking/date/time/{id}', 'CardController@change_slot_date')->name('change.booking.date.time');
	

	Route::resource('blog-category','BlogCategoryController');

	Route::get('/blog-category/edit/{id}', 'BlogCategoryController@edit')->name('blog-category.edit');

	Route::get('/blog-category/delete/{id}', 'BlogCategoryController@destroy')->name('blog-category.delete');

	Route::post('/blog-category/update/{id}', 'BlogCategoryController@update')->name('blog-category.update');


	Route::get('question','SettingController@question')->name('question');

	Route::get('web-settings','SettingController@web_setting')->name('web.settings');

	Route::post('update/web-settings','SettingController@update_web_setting')->name('update.web.settings');


	Route::resource('blog','BlogController');

	Route::get('/blog/edit/{id}', 'BlogController@edit')->name('blog.edit');

	Route::get('/blog/delete/{id}', 'BlogController@destroy')->name('blog.delete');

	Route::post('/blog/update/{id}', 'BlogController@update')->name('blog.update');
	

	Route::resource('addon','AddonController');

	Route::get('/addon/create/{attribute}/{service}/{attr_item}', 'AddonController@create')->name('addon.create');
	
	Route::get('/addon/edit/{id}', 'AddonController@edit')->name('addon.edit');

	Route::get('/addon/delete/{id}', 'AddonController@destroy')->name('addon.delete');

	Route::post('/addon/update/{id}', 'AddonController@update')->name('addon.update');

	Route::post('/get/service_atr', 'AddonController@service_atr')->name('get.service_atr');
	
	Route::post('/get/cat_atr', 'AddonController@cat_atr')->name('get.cat_atr');

	
	Route::get('/attribute', 'AttributeController@index')->name('attribute');

	Route::post('/attribute/store', 'AttributeController@store')->name('attribute.store');

	Route::post('/attribute/update/{id}', 'AttributeController@update')->name('attribute.update');

	Route::get('/attribute/delete/{id}', 'AttributeController@delete')->name('attribute.delete');

	Route::get('/attribute/{attribute}/status/{status}', 'AttributeController@status')->name('attribute.status');

	Route::get('/attribute/manage/{id}', 'AttributeController@manage')->name('attribute.manage');

	Route::post('/attributevalue/store', 'AttributeController@attributevalue_store')->name('attributevalue.store');

	Route::post('/attributevalue/update/{id}', 'AttributeController@attributevalue_update')->name('attributevalue.update');

	Route::get('/attributevalue/delete/{id}', 'AttributeController@attributevalue_delete')->name('attributevalue.delete');


	Route::get('/packages','PackageController@index' )->name('packages');

	Route::get('/package/detail/{id}', 'PackageController@package_detail')->name('package.detail');

	Route::get('/addons','PackageController@addons' )->name('addons');

	Route::get('/addon/detail/{id}', 'PackageController@addon_detail')->name('addon.detail');


	Route::get('/home/setting', 'SettingController@setting')->name('home.setting');

	Route::post('/home/setting/update', 'SettingController@update')->name('home.setting.update');

	Route::get('/admin/setting', 'SettingController@admin_setting')->name('admin.setting');

	Route::get('/remove/admin/logo', 'SettingController@remove_admin_logo')->name('remove.admin_logo');

	Route::get('/remove/admin/side/logo', 'SettingController@remove_admin_side_logo')->name('remove.admin_side_logo');
	
	Route::post('/slider/update', 'SettingController@sliderupdate')->name('slider.update');

	Route::post('/app/slider/update', 'SettingController@appsliderupdate')->name('app.slider.update');

	Route::post('/app/sign-up/slider/update', 'SettingController@appsignupsliderupdate')->name('app.signup.slider.update');

	Route::post('/vendor/app/sign-up/slider/update', 'SettingController@vendorappsignupsliderupdate')->name('vendor.app.signup.slider.update');

	Route::post('/home/banner/update', 'SettingController@homebanner')->name('home.banner.update');

	Route::get('/remove/footer/logo', 'SettingController@remove_footer_logo')->name('remove.footer_logo');

	Route::get('/remove/header/logo', 'SettingController@remove_header_logo')->name('remove.header_logo');

	Route::get('/remove/first/slider/{type}', 'SettingController@remove_first_slider')->name('remove.first_slider');

	Route::get('/remove/app/slider/{type}', 'SettingController@remove_app_slider')->name('remove.app_slider');

	Route::get('/remove/app/sign/{type}', 'SettingController@remove_sign_slider')->name('remove.app_sign');

	Route::get('/vendor/remove/app/sign/{type}', 'SettingController@vendor_remove_sign_slider')->name('vendor.remove.app_sign');

	Route::get('/remove/home/slider/{type}', 'SettingController@remove_home_slider')->name('remove.home_slider');



	Route::get('/package-leave','PackageController@package_leave' )->name('package.leave');

	Route::get('/all-orders','OrderController@allOrders' )->name('all.orders');

	Route::get('/all-invoices','OrderController@allInvoices' )->name('all.invoices');

	// Manage Role

	Route::get('/manage/role','RoleController@index' )->name('manage.role');

	Route::get('/create/role','RoleController@create' )->name('create.role');

	Route::post('/role/store','RoleController@store' )->name('role.store');

	Route::post('/role/update/{role}', 'RoleController@update')->name('role.update');

	Route::get('/role/delete/{id}', 'RoleController@delete')->name('role.delete');

	Route::get('/manage/role/{id}', 'RoleController@permission')->name('role.permission');

	Route::get('/role/permissions/{id}', 'RoleController@permission_filter')->name('role.permissions');

	Route::post('/assign/permission', 'RoleController@update_assign_permission')->name('assign.permission');


	// Paypal
	
	Route::get('payment-status',array('as'=>'payment.status','uses'=>'PaymentController@paymentInfo'));

	Route::get('payment/paypal',array('as'=>'payment','uses'=>'PaymentController@payment'));

	Route::get('payment-cancel', function () {
	    return 'Payment has been canceled';
	});

	// Razorpay

	Route::get('/payment/razorpay', 'RazorpayController@pay_amount')->name('payment.razorpay');

	Route::post('rozer/payment/pay-success', 'RazorpayController@payment')->name('payment.rozer');

	// Stipe

	Route::get('payment/stripe', 'StripePaymentController@stripe');

	Route::post('/stripe/create-checkout-session', 'StripePaymentController@create_checkout_session')->name('stripe.get_token');

	Route::any('/stripe/payment/callback', 'StripePaymentController@callback')->name('stripe.callback');

	Route::get('/stripe/success', 'StripePaymentController@success')->name('stripe.success');

	Route::get('/stripe/cancel', 'StripePaymentController@cancel')->name('stripe.cancel');

	// Offline Booking

	Route::get('/offline/bookings', 'OffLineBookingController@booking_list')->name('offline.bookings');

	Route::get('/offline/booking', 'OffLineBookingController@index')->name('offline.booking');

	Route::get('/create/account', 'OffLineBookingController@create_account')->name('create.account');

	Route::post('/update/user/name', 'OffLineBookingController@update_user_name');

	Route::post('/update/user/email', 'OffLineBookingController@update_user_email');

	Route::post('/get/user/list', 'OffLineBookingController@get_user_list');

	Route::post('/add/offline/address', 'OffLineBookingController@store_address')->name('add.offline.address');
	
	Route::post('/step2', 'OffLineBookingController@step2')->name('step2');

	Route::get('/offline/service', 'OffLineBookingController@service')->name('offline.service');

	Route::post('/get/sub/catgeory/service', 'OffLineBookingController@sub_cate_service')->name('get.sub.catgeory.service');
	
	Route::post('/get/sub/child/catgeory/service', 'OffLineBookingController@sub_child_cate_service')->name('get.sub.child.catgeory.service');

	Route::post('/get/sub/child/attribute', 'OffLineBookingController@get_sub_child_attribute')->name('get.sub.child.attribute');
	
	Route::post('/get/material/price', 'OffLineBookingController@get_material_price')->name('get.material.price');
	
	Route::get('/get/slot', 'OffLineBookingController@get_slot')->name('get.slot');

	Route::get('/step3', 'OffLineBookingController@step3')->name('step3');

	Route::post('/step3', 'OffLineBookingController@step3_Store')->name('step3.store');

	Route::post('/add-attr-in-cart', 'OffLineBookingController@add_attr_in_cart')->name('add.attr.in.cart');

	Route::post('/remove-attr-in-cart', 'OffLineBookingController@remove_attr_in_cart')->name('remove.attr.in.cart');

	Route::get('/step4', 'OffLineBookingController@step4')->name('step4');

	Route::post('/pay/tip/value', 'OffLineBookingController@pay_tip')->name('pay.tip.value');

	Route::post('/pay/charge/value', 'OffLineBookingController@pay_charge')->name('pay.charge.value');

	Route::post('/pay/discount/value', 'OffLineBookingController@pay_discount')->name('pay.discount.value');

	Route::post('/step4', 'OffLineBookingController@step4_store')->name('step4');

	Route::post('/send/payment/link', 'OffLineBookingController@send_payment_list')->name('send.payment.link');

	Route::post('/confirm/booking', 'OffLineBookingController@confirm_booking')->name('confirm.booking');

	Route::get('/step5', 'OffLineBookingController@step5')->name('step5');

	Route::post('/update/payment', 'OffLineBookingController@update_payment')->name('update.payment');

	Route::post('/update/live.payment', 'OffLineBookingController@update_live_payment')->name('update.live.payment');

	Route::get('/launch/booking/{id}', 'OffLineBookingController@launch_booking')->name('launch.booking');

	Route::get('/offline/booking/view/{id}', 'OffLineBookingController@view_booking')->name('offline.booking.view');

	Route::get('/offline/booking/delete/{id}', 'OffLineBookingController@delete_booking')->name('offline.booking.delete');
	
	Route::get('/offline/booking/cencal/{id}', 'OffLineBookingController@cencal_booking')->name('offline.booking.cencal');

	Route::get('/off/checkmail', 'OffLineBookingController@checkmail');

	Route::post('off-apply-coupon', 'OffLineBookingController@apply_coupon')->name('off-apply-coupon');

	Route::post('off/change/vendor', 'OffLineBookingController@change_vendor')->name('off.change.vendor');

	Route::get('/change/booking/slot/{id}', 'OffLineBookingController@change_slot')->name('change.date.time');

	Route::post('/update/time/slot', 'OffLineBookingController@update_time_slot')->name('update.time.slot');
});

Route::get('checkmail/{id?}', 'FrontController@checkmail');

Route::get('/', 'FrontController@index')->name('web');
Route::get('/', 'FrontController@index')->name('shop');

Route::get('service/details/{id?}/{delete?}', 'FrontController@service_details')->name('service.details');

Route::get('contact-support', 'FrontController@contact_support')->name('contact.support');

Route::post('contact/support', 'FrontController@contact_support_post');

Route::post('login-with-otp', 'FrontController@login_with_otp');

Route::get('register-user', 'FrontController@register');

Route::get('logout-user', 'FrontController@logout');

Route::get('check-mobile-no', 'FrontController@checkmobileno');

Route::post('card/store', 'FrontController@add_card')->name('add.attribute.card');

Route::post('update/profile', 'FrontController@update_profile')->name('update.profile');

Route::get('card/details/{id}', 'FrontController@card_details')->name('card.details');

Route::post('card/update', 'FrontController@card_update')->name('update.card');

Route::post('add/addon', 'FrontController@add_addon');

Route::get('terms-and-condition', 'FrontController@terms_condition')->name('terms.condition');

Route::get('privacy-policies', 'FrontController@privacy_policies')->name('privacy.policies');

Route::post('get_child_cat_attr_items', 'FrontController@get_child_cat_attr_items')->name('get_child_cat_attr_items');

Route::post('remove/card/attribute', 'FrontController@remove_card_attr');

Route::post('minus_booking', 'FrontController@minus_booking');

Route::post('remove/attribute', 'FrontController@removed_card_attr_sec');

Route::get('profile', 'FrontController@profile');

Route::get('my-bookings', 'FrontController@myorders');

Route::get('draft-bookings', 'FrontController@draftbookings');

Route::get('my-address', 'FrontController@myaddress');

Route::post('get/card/booking', 'FrontController@get_card_booking')->name('get.card.booking');

Route::get('confirm-order', 'FrontController@confirm_order')->name('confirm-order');

Route::post('apply-coupon', 'FrontController@apply_coupon')->name('apply-coupon');

Route::post('remove-coupon', 'FrontController@remove_coupon')->name('remove-coupon');

Route::post('pay/tip', 'FrontController@pay_tip')->name('pay.tip');

Route::post('cod/charge', 'FrontController@cod_charge')->name('cod.charge');

Route::post('get_locality', 'FrontController@get_locality')->name('get_locality');

Route::post('update/address', 'FrontController@update_address');

Route::get('remove/address/{id}', 'FrontController@removeaddress');

Route::post('store/address', 'FrontController@store_address');

Route::post('update/slot', 'FrontController@update_slot');

Route::post('sub/attribute', 'FrontController@subAttribute');

Route::post('update/sub/attribute', 'FrontController@updateSubAttribute');

Route::post('update/material/charge', 'FrontController@update_material_charge');

Route::post('remove/booking/attribute', 'FrontController@removed_booking_attr');

Route::get('update/address/{id}', 'FrontController@editaddress');

Route::post('get/location', 'FrontController@getLocation');

Route::post('store/question', 'FrontController@store_question');

Route::get('payment/{id}', 'FrontController@payment');

Route::get('paymentsuccess', 'FrontController@paymentsuccess');

Route::get('paymentfailure', 'FrontController@paymentfailure');

Route::get('failed', 'FrontController@failed');

Route::get('search', 'FrontController@search')->name('search');

Route::get('about-us', 'FrontController@about_us')->name('about-us');

Route::get('blogs', 'FrontController@blogs')->name('blogs');

Route::get('blogs/{id}', 'FrontController@blog_details')->name('blog/details');

Route::get('become-a-vendor', 'FrontController@become_vendor')->name('become-a-vendor');

Route::post('store/vendor', 'FrontController@store_vendor')->name('store.vendor');

Route::post('get/live/address', 'FrontController@get_live_address')->name('get.live.address');

Route::post('get/lat/long', 'FrontController@get_lat_long')->name('get.lat.long');

Route::get('send-login-otp', 'FrontController@send_sms');

Route::get('checksmsapi', 'FrontController@checkapi');

Route::get('msg_testing', 'FrontController@msg_testing');

Route::post('get/slot', 'FrontController@get_slot');

// Tabby Route

Route::get('tabby/payment/response', 'FrontController@tabby_response');

Route::get('tabby/cancel/response', 'FrontController@tabby_cancel');

Route::get('tabby/failure/response', 'FrontController@tabby_failure');

Route::get('cronjob/check/tabby/status', 'FrontController@cronjob_tabby_status');

Route::get('check/tabby', 'FrontController@test_tabby');

// Tabby Route End

Route::get('check/slot', 'FrontController@checkslot');

Route::get('checkNotification', 'API\ApiController@checkNotification');

/* vendor  */

	Route::group(['middleware' => ['auth','role:vendor']], function () {



	// Route::get('/shop','ShopController@index' )->name('shop');

	// Route::get('/shop-items', 'ShopController@shop_items')->name('shop.items');

	// Route::post('/shop-item-process', 'ShopController@shop_item_process')->name('shop.item.process');

	// Route::get('/shop-item/{id}', 'ShopController@shop_item')->name('shop.item');

	// Route::post('/shop-item-store/{id}', 'ShopController@shop_item_store')->name('shop.item.store');

	// Route::post('/shop-item-update/{id}', 'ShopController@shop_item_update')->name('shop.item.update');



	// Route::get('/my-profile', 'ProfileController@my_profile')->name('my.profile');

	// Route::post('/my-profile-save', 'ProfileController@my_profile_save')->name('my.profile.save');

	// Route::post('/my-password-save', 'ProfileController@my_password_save')->name('my.password.save');

	// Route::post('/my-shop-save', 'ProfileController@my_shop_save')->name('my.shop.save');



	// Route::get('/shop-request', 'ShopController@shop_request')->name('shop.request');

	// Route::get('/request/{request}/status/{status}', 'ShopController@request_status')->name('request.status');

	// Route::get('/my-customers', 'ShopController@my_customers')->name('shop.customers');



	// Route::get('/package-request', 'ShopController@package_request')->name('shop.package.request');

	// Route::get('/package/{request}/status/{status}', 'ShopController@package_request_status')->name('package.request.status');

	// Route::get('/my-packages', 'ShopController@my_packages')->name('shop.packages');

	// Route::get('/package-items/{id}', 'ShopController@package_items')->name('shop.package.items');



	// Route::get('/package-addons-request', 'ShopController@package_addons_request')->name('shop.addons.request');

	// Route::get('/addon/{request}/status/{status}', 'ShopController@addon_request_status')->name('addon.request.status');

	// Route::get('/my-package-addons', 'ShopController@my_package_addons')->name('shop.addons');

	// Route::get('/addon-items/{id}', 'ShopController@package_addon_items')->name('shop.addon.items');



	// Route::get('/shop-package-leave','ShopController@shop_package_leave' )->name('shop.package.leave');

	// Route::get('/today-orders','OrderController@todayOrders' )->name('today.orders');

	// Route::get('/order/{request}/status/{status}', 'OrderController@order_status_update')->name('order.status');

	// Route::get('/my-orders','OrderController@myOrders' )->name('my.orders');

	// Route::get('/order-invoice','OrderController@orderInvoice' )->name('order.invoice');

	// Route::any('/generate-invoice','OrderController@generateInvoice' )->name('generate.invoice');

	// Route::post('/search-order','OrderController@searchOrder' )->name('search.order');

	// Route::post('/invoice-save','OrderController@invoiceSave' )->name('invoice.save');



	// Route::get('/shop-customer/detail/{id}', 'UserController@shop_customer_detail')->name('shop.customer.detail');

	// Route::post('/generate-today-orders','OrderController@todayOrdersGenerate' )->name('generate.today.orders');

	// Route::post('/payment-save/{id}','OrderController@payment_save' )->name('payment.save');

	// Route::get('/order-delivered/{today}','OrderController@order_delivered' )->name('order.delivered');

});





/* common page */



Route::group(['middleware' => ['auth']], function () {

	Route::get('/invoice-download','OrderController@invoiceDownload' )->name('invoice.download');

	Route::get('/test', 'DashboardController@test')->name('test');

});	

Route::get('delete_crt', 'FrontController@delete_crt');

Route::get('checkmailbookingaccept', 'FrontController@checkmailbookingaccept');

Route::get('booking/review/{id}', 'FrontController@booking_review');

Route::post('review/store', 'FrontController@store_review')->name('review.store');
