<?php

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

Route::get('certificado/{curso}/{usuario}/{fecha}', 'CourseController@generate_certificate');
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    return 'DONE'; //Return anything
});

Route::group(['prefix' => 'scripts', 'middleware' => 'https'], function () {
	Route::get('corregir-comisiones-angelica-carrillo', 'ScriptController@corregir_comisiones_angelica_carrillo');
	Route::get('asignar-comisiones-eventos-mentores', 'ScriptController@asignar_comisiones_eventos_mentores');
	Route::get('send-expiration-notifications', 'ScriptController@send_expiration_notifications');
	Route::get('update-transactions', 'ScriptController@update_transactions');
	Route::get('create-slugs', 'ScriptController@create_slugs');
	Route::get('codigos-amigables', 'ScriptController@codigos_amigables');
	Route::get('actualizar-progresos-cursos', 'ScriptController@actualizar_progresos_cursos');
	Route::get('actualizar-codigos', 'ScriptController@actualizar_codigos');
	Route::get('bloquear-lecciones', 'ScriptController@bloquear_lecciones');
	Route::get('desbloquear-lecciones', 'ScriptController@desbloquear_lecciones');
	Route::get('reiniciar-billeteras', 'ScriptController@reiniciar_billeteras');
	Route::get('corregir-comisiones', 'ScriptController@corregir_comisiones');
});

Route::group(['middleware' => ['https']], function () {
	
	Route::post('login', 'Auth\LoginController@post_login')->name('login');
	Route::post('register', 'Auth\RegisterController@create')->name('register');
	Route::post('logout', 'Auth\LoginController@logout')->name('logout');

	Route::group(['prefix' => 'login'], function () {
	    Route::get('/{provider}', 'Auth\LoginController@redirectToProvider');
	    Route::get('/{provider}/callback', 'Auth\LoginController@handleProviderCallback');
	});

	Route::get('verify-email/{id}/{token}', 'Auth\RegisterController@verify_email')->name('landing.verify-email');
	Route::post('recover-password', 'Auth\ResetPasswordController@recover_password')->name('landing.recover-password');
	Route::get('/', 'LandingController@index')->name('landing.index');
	Route::get('search', 'LandingController@search')->name('landing.search');

	Route::group(['prefix' => 't-courses'], function() {
		Route::get('/{slug?}/{categoria?}', 'LandingController@courses')->name('landing.courses');
		Route::get('show/{slug}/{id}', 'CourseController@show')->name('landing.courses.show');
	});

	Route::group(['prefix' => 't-books'], function() {
		Route::get('/', 'LandingController@podcasts')->name('landing.podcasts');
		Route::get('search-by-category/{slug}/{id}', 'LandingController@search_podcasts_by_category')->name('landing.podcasts.search-by-category');
		Route::get('show/{slug}/{id}', 'PodcastController@show')->name('landing.podcasts.show');
	});

	Route::group(['prefix' => 't-master-class'], function() {
		Route::get('show/{slug}/{id}', 'MasterClassController@show')->name('landing.master-class.show');
	});

	Route::group(['prefix' => 't-mentor'], function() {
		Route::get('/', 'LandingController@t_mentor')->name('landing.t-mentor');
	});

	Route::group(['prefix' => 't-member'], function() {
		Route::get('/', 'LandingController@t_member')->name('landing.t-member');
	});

	Route::group(['prefix' => 't-mentorings'], function() {
		Route::get('/', 'LandingController@certifications')->name('landing.certifications');
		Route::get('search-by-category/{slug}/{id}', 'LandingController@search_certifications_by_category')->name('landing.certifications.search-by-category');
		Route::get('show/{slug}/{id}', 'CertificationController@show')->name('landing.certifications.show');
	});

	Route::group(['prefix' => 'shopping-cart'], function() {
		Route::get('/', 'ShoppingCartController@index')->name('landing.shopping-cart.index');
		Route::get('store/{id}/{tipo}', 'ShoppingCartController@store')->name('landing.shopping-cart.store');
		Route::get('delete/{id}', 'ShoppingCartController@delete')->name('landing.shopping-cart.delete');
		Route::get('checkout', 'ShoppingCartController@checkout')->name('landing.shopping-cart.checkout');
		Route::get('bill/{payment_type}/{payment_id}', 'PaymentController@bill')->name('landing.shopping-cart.bill');


		Route::post('add_code', 'ShoppingCartController@add_code')->name('landing.shopping-cart.add-code');
		Route::get('gift-membership/{membresia}', 'ShoppingCartController@gift_membership')->name('landing.shopping-cart.gift-membership');
		Route::post('paypal-gift-membership', 'PaypalController@checkout')->name('landing.shopping-cart.paypal-gift-membership');
		Route::get('process-gift-membership', 'PaypalController@process_gift_membership')->name('landing.shopping-cart.process-gift-membership');
		Route::post('mercado-pago-gift-membership', 'PaymentController@mercado_pago_checkout')->name('landing.shopping-cart.mercado-pago-gift-membership');
		Route::get('show-gift/{compra_id}', 'ShoppingCartController@show_gift')->name('landing.shopping-cart.show-gift');
		Route::post('send-gift-membership', 'ShoppingCartController@send_gift_membership')->name('landing.shopping-cart.send-gift-membership');
		
	});

	Route::group(['prefix' => 't-events'], function(){
		Route::get('/', 'EventController@index')->name('landing.events');
		Route::get('show/{slug}/{id}', 'EventController@show')->name('landing.events.show');
		Route::post('subscribe', 'EventController@subscribe')->name('landing.events.subscribe');
		Route::get('payment/{suscriptor}', 'EventController@payment')->name('landing.events.payment');
		Route::post('add-instructor-code', 'EventController@add_instructor_code')->name('landing.events.add-instructor-code');
		Route::post('add-partner-code', 'EventController@add_partner_code')->name('landing.events.add-partner-code');
		Route::post('mercado-pago-checkout', 'EventController@mercado_pago_checkout')->name('landing.events.mercado-pago-checkout');
		Route::post('bank-transfer-checkout', 'EventController@bank_transfer_checkout')->name('landing.events.bank-transfer-checkout');
		Route::post('efecty-checkout', 'EventController@efecty_checkout')->name('landing.events.efecty-checkout');
		Route::post('paypal-checkout', 'PaypalController@payment_event')->name('landing.events.paypal-checkout');
		Route::get('process-paypal-checkout', 'PaypalController@process_payment_event')->name('landing.events.process-paypal-checkout');
		Route::get('payment-bill/{suscriptor}', 'EventController@payment_bill')->name('landing.events.payment-bill');
		Route::post('buy-to-gift', 'EventController@buy_to_gift')->name('landing.events.buy-to-gift');
		Route::post('redeem-gift', 'EventController@redeem_gift')->name('landing.events.redeem-gift');
		Route::get('add-video-view-counter/{evento_id}', 'EventController@add_video_view_counter')->name('landing.events.add-video-view-counter');
	});

	Route::group(['prefix' => 'instructors'], function() {
		Route::get('profile/{slug}/{id}', 'LandingController@show_instructor_profile')->name('landing.instructor.show-profile');
	});

	Route::group(['prefix' => 'marketplace'], function() {
		Route::get('/', 'MarketplaceController@index')->name('landing.marketplace');
		Route::get('products-by-category/{slug_category}/{id_category}/{slug_subcategory?}/{id_subcategory?}', 'MarketplaceController@products_by_category')->name('landing.marketplace.products-by-category');
	});

	Route::get('/home', 'HomeController@index')->name('home');

	Route::post('new-subscriber', 'NewsletterController@new_subscriber')->name('landing.new-subscriber');
	Route::post('contact-us', 'LandingController@contact_us')->name('landing.contact-us');
});

Route::group(['prefix' => 'ajax', 'middleware' => 'https'], function() {
	Route::get('modulos-por-curso/{id}/{contenido}', 'AjaxController@modulos_por_curso');
	Route::get('portada-actual-curso/{curso}', 'AjaxController@portada_actual_curso');
	Route::get('verificar-correo/{correo}', 'AjaxController@verificar_correo');
	Route::get('contenido-por-tipo/{tipo}', 'AjaxController@contenido_por_tipo');
	Route::get('cargar-subcategorias/{categoria}', 'AjaxController@cargar_subcategorias');
	Route::get('verificar-correo/{correo}', 'AjaxController@verificar_correo');
	Route::get('verificar-sponsor/{sponsor}', 'AjaxController@verificar_sponsor');
	Route::get('actualizar-progreso-leccion/{leccion}/{progreso}/{tipo}', 'AjaxController@actualizar_progreso_leccion');
	Route::get('archivos-por-leccion/{leccion}', 'AjaxController@archivos_por_leccion');
	Route::get('multimedias-por-contenido/{tipo}/{id}', 'AjaxController@multimedias_por_contenido');
	Route::get('verificar-etiqueta/{etiqueta}', 'AjaxController@verificar_etiqueta');
	Route::get('verificar-nombre-perfil/{nombre}', 'AjaxController@verificar_nombre_perfil');
	//CArgar credenciales de S3
	Route::post('load', 'AjaxController@load');
	Route::get('detalles-compra/{compra}', 'AjaxController@detalles_compra');
	Route::get('datos-cobro-mentor/{mentor}', 'AjaxController@datos_cobro_mentor');
	Route::get('cargar-curso-original/{curso}', 'AjaxController@cargar_curso_original');
	Route::get('load-preview/{id}/{tipo_contenido}', 'AjaxController@load_preview')->name('ajax.load-preview');
	Route::get('multimedias-por-producto/{id}', 'AjaxController@multimedias_por_producto');
	Route::get('load-courses-by-category/{id}', 'AjaxController@load_courses_by_category')->name('ajax.load-courses-by-category');
	Route::get('change-category/{id}', 'AjaxController@change_category')->name('ajax.change-category');
	Route::get('actualizar-barra-progreso/{leccion}', 'AjaxController@actualizar_barra_progreso');
});

Route::group(['prefix' => 'notifications', 'middleware' => ['https', 'auth']], function(){
	Route::get('/', 'NotificationController@index')->name('notifications.index');
	Route::get('update/{notificacion}', 'NotificationController@update')->name('notifications.update');
});

Route::post('redeem-gift-membership', 'MembershipController@redeem_gift')->name('membership.redeem-gift');

Route::group(['prefix' => 'students', 'middleware' => ['https', 'auth', 'students']], function() {
	Route::group(['prefix' => 'profile'], function() {
		Route::get('/', function(){
			return view('students.myProfile');
		})->name('students.profile.my-profile');

		Route::post('update', 'StudentController@update_profile')->name('students.profile.update');
	});

	Route::get('/my-content', 'StudentController@my_content')->name('students.my-content');
	Route::get('/my-gifts', 'GiftController@index')->name('students.my-gifts');
	Route::post('redeem-gift-code', 'GiftController@redeem_gift_code')->name('students.redeem-gift-code');

	Route::group(['prefix' => 't-courses'], function() {
		Route::get('/', 'LandingController@courses')->name('students.courses');
		Route::get('resume/{slug}/{id}', 'CourseController@resume')->name('students.courses.resume')->middleware('course_student');
		Route::get('lessons/{slug}/{id}/{lesson_id}', 'CourseController@lessons')->name('students.courses.lessons');
		Route::get('add/{id}/{membresia?}', 'CourseController@add')->name('students.courses.add');
		Route::get('load-video-duration/{leccion}/{duracion?}', 'LessonController@load_video_duration')->name('students.courses.lessons.load-video-duration');
	});

	Route::group(['prefix' => 't-mentorings'], function() {
		Route::get('/', 'CertificationController@index')->name('students.certifications');
		Route::get('show/{slug}/{id}', 'CertificationController@show')->name('students.certifications.show');
		Route::get('resume/{slug}/{id}', 'CertificationController@resume')->name('students.certifications.resume')->middleware('certification_student');
		Route::get('lessons/{slug}/{id}/{lesson_id}', 'CertificationController@lessons')->name('students.certifications.lessons');
	});

	Route::group(['prefix' => 't-books'], function() {
		Route::get('resume/{slug}/{id}', 'PodcastController@resume')->name('students.podcasts.resume')->middleware('podcast_student');
	});

	Route::group(['prefix' => 'tests'], function(){
		Route::get('submit/{slug}/{id}', 'TestController@submit')->name('students.tests.submit')->middleware('test_student');
		Route::post('save', 'TestController@save')->name('students.tests.save');
		Route::get('show-score/{slug}/{id}', 'TestController@show_score')->name('students.tests.show-score');
	});

	Route::group(['prefix' => 'discussions'], function() {
		Route::post('store', 'DiscussionController@store')->name('students.discussions.store');
		Route::get('group/{tipo}/{slug}/{id}', 'DiscussionController@discussions_group')->name('students.discussions.group');
		Route::get('show/{slug}/{id}', 'DiscussionController@show')->name('students.discussions.show');
		Route::post('store-comment', 'DiscussionController@store_comment')->name('students.discussions.store-comment');
	});

	Route::group(['prefix' => 'ratings'], function() {
		Route::post('store', 'RatingController@store')->name('students.ratings.store');
		Route::post('update', 'RatingController@update')->name('students.ratings.update');
	});

	Route::group(['prefix' => 'instructors'], function() {
		Route::get('profile/{slug}/{id}', 'InstructorController@profile')->name('students.instructors.show-profile');
	});

	Route::group(['prefix' => 'shopping-cart'], function() {
		Route::post('add_code', 'ShoppingCartController@add_code')->name('students.shopping-cart.add-code');
		Route::post('add-gift', 'ShoppingCartController@add_gift')->name('students.shopping-cart.add-gift');
		Route::post('apply-coupon', 'CouponController@apply')->name('students.shopping-cart.apply-coupon');
		Route::get('checkout', 'ShoppingCartController@checkout')->name('students.shopping-cart.checkout');
		Route::get('free-checkout', 'PaymentController@free_checkout')->name('students.shopping-cart.free-checkout'); 
		Route::get('/', 'ShoppingCartController@index')->name('students.shopping-cart.index');
		Route::post('bank-transfer-checkout', 'PaymentController@bank_transfer_checkout')->name('students.checkout.bank-transfer-checkout');
		Route::post('paypal-checkout', 'PaypalController@checkout')->name('students.shopping-cart.paypal-checkout');
		Route::get('delete/{ID}', 'ShoppingCartController@delete')->name('students.shopping-cart.delete');
		Route::get('payment-status', 'PaypalController@payment_status')->name('students.shopping-cart.payment-status');
		Route::post('mercado-pago-checkout', 'PaymentController@mercado_pago_checkout')->name('students.shopping-cart.mercado-pago-checkout');
		Route::post('efecty-checkout', 'PaymentController@efecty_checkout')->name('students.shopping-cart.efecty-checkout');
		Route::get('bill/{payment_type}/{payment_id}', 'PaymentController@bill')->name('students.shopping-cart.bill');
	});

	Route::group(['prefix' => 'membership'], function() {
		Route::get('/', 'MembershipController@index')->name('students.membership.index');
	});

	Route::group(['prefix' => 'purchases'], function() {
		Route::get('/', 'PaymentController@purchases_record')->name('students.purchases.index');
		ROute::get('show-bill/{compra_id}', 'PaymentController@show_bill')->name('students.purchases.show-bill');
	});

	Route::group(['prefix' => 'marketplace'], function() {
		Route::get('my-products', 'MarketplaceController@my_purchased_products')->name('students.marketplace.my-products');
	});

	Route::group(['prefix' => 'tickets'], function() {
		Route::get('/', 'TicketController@index')->name('students.tickets.index');
		Route::get('show/{id}', 'TicketController@show')->name('students.tickets.show')->middleware('ticket_user');
		Route::post('reply', 'TicketController@reply')->name('students.tickets.reply');
		Route::get('close/{id}', 'TicketController@close')->name('students.tickets.close')->middleware('ticket_user');
		Route::post('store', 'TicketController@store')->name('students.tickets.store');
	});
});

Route::group(['prefix' => 'instructors', 'middleware' => ['https', 'auth', 'instructors']], function() {
	Route::get('/', 'InstructorController@home')->name('instructors.index');
	
	Route::group(['prefix' => 'profile'], function() {
		Route::get('/', 'InstructorController@my_profile')->name('instructors.profile.my-profile');
		Route::post('update', 'InstructorController@update_profile')->name('instructors.profile.update');
	});

	Route::group(['prefix' => 't-courses'], function() {
		Route::get('/', 'CourseController@index')->name('instructors.courses.index');
		Route::get('steps', 'CourseController@steps')->name('instructors.courses.steps');
		Route::get('show/{slug}/{id}', 'CourseController@show')->name('instructors.courses.show')->middleware('course_instructor');
		Route::get('create', 'CourseController@create')->name('instructors.courses.create');
		Route::post('store', 'CourseController@store')->name('instructors.courses.store');
		Route::get('edit/{slug}/{id}', 'CourseController@edit')->name('instructors.courses.edit')->middleware('course_instructor');
		Route::post('update', 'CourseController@update')->name('instructors.courses.update');

		Route::group(['prefix' => 'temary'], function() {
			Route::post('new-module', 'LessonController@add_module')->name('instructors.courses.temary.add-module');
			Route::post('update-module', 'LessonController@update_module')->name('instructors.courses.temary.update-module');
			Route::get('delete-module/{id}', 'LessonController@delete_module')->name('instructors.courses.temary.delete-module');
			Route::post('new-lesson', 'LessonController@store')->name('instructors.courses.temary.add-lesson');
			Route::post('load-video', 'LessonController@load_video')->name('instructors.courses.temary.load-video');
			Route::post('update-lesson', 'LessonController@update')->name('instructors.courses.temary.update-lesson');
			Route::get('delete-lesson/{id}', 'LessonController@delete')->name('instructors.courses.temary.delete-lesson');
			Route::post('load-resource', 'LessonController@load_resource')->name('instructors.courses.temary.load-resource');
			Route::get('show-resources/{lesson}', 'LessonController@show_resources')->name('instructors.courses.temary.show-resources');
			Route::get('delete-resource/{id}', 'LessonController@delete_resource')->name('instructors.courses.temary.delete-resource');
			Route::get('{slug}/{curso}', 'CourseController@temary')->name('instructors.courses.temary')->middleware('course_instructor');
		});
		Route::get('publish/{id}', 'CourseController@publish')->name('instructors.courses.publish')->middleware('course_instructor');
		Route::get('purchases-record/{slug}/{curso}', 'CourseController@purchases_record')->name('instructors.courses.purchases-record')->middleware('course_instructor');
	});

	Route::group(['prefix' => 't-mentorings'], function() {
		Route::get('/', 'CertificationController@index')->name('instructors.certifications.index');
		Route::get('create', 'CertificationController@create')->name('instructors.certifications.create');
		Route::post('store', 'CertificationController@store')->name('instructors.certifications.store');
		Route::get('show/{slug}/{id}', 'CertificationController@show')->name('instructors.certifications.show')->middleware('certification_instructor');
		Route::get('edit/{slug}/{id}', 'CertificationController@edit')->name('instructors.certifications.edit')->middleware('certification_instructor');
		Route::post('update', 'CertificationController@update')->name('instructors.certifications.update');
		Route::get('temary/{slug}/{id}', 'CertificationController@temary')->name('instructors.certifications.temary')->middleware('certification_instructor');
		Route::post('update-temary', 'CertificationController@update_temary')->name('instructors.certifications.update-temary');
		Route::get('publish/{id}', 'CertificationController@publish')->name('instructors.certifications.publish')->middleware('certification_instructor');
		Route::get('purchases-record/{slug}/{certificacion}', 'CertificationController@purchases_record')->name('instructors.certifications.purchases-record')->middleware('certification_instructor');
	});

	Route::group(['prefix' => 't-books'], function() {
		Route::get('/', 'PodcastController@index')->name('instructors.podcasts.index');
		Route::get('create', 'PodcastController@create')->name('instructors.podcasts.create');
		Route::post('store', 'PodcastController@store')->name('instructors.podcasts.store');
		Route::get('show/{slug}/{id}', 'PodcastController@show')->name('instructors.podcasts.show')->middleware('podcast_instructor');
		Route::get('edit/{slug}/{id}', 'PodcastController@edit')->name('instructors.podcasts.edit')->middleware('podcast_instructor');
		Route::post('update', 'PodcastController@update')->name('instructors.podcasts.update');
		Route::post('load-resource', 'PodcastController@load_resource')->name('instructors.podcasts.load-resource');
		Route::get('resources/{podcast}', 'PodcastController@resources')->name('instructors.podcasts.resources');
		Route::post('delete-resource', 'PodcastController@delete_resource')->name('instructors.podcasts.delete-resource');
		Route::get('publish/{id}', 'PodcastController@publish')->name('instructors.podcasts.publish')->middleware('podcast_instructor');
		Route::get('purchases-record/{slug}/{podcast}', 'PodcastController@purchases_record')->name('instructors.podcasts.purchases-record')->middleware('podcast_instructor');
	});

	Route::group(['prefix' => 'lessons'], function() {
		Route::post('delete', 'LessonController@delete')->name('instructors.lessons.delete');
		Route::post('delete-resource', 'LessonController@delete_resource')->name('instructors.lessons.delete-resource');
	});

	Route::group(['prefix' => 'tests'], function() {
		Route::post('create', 'TestController@create')->name('instructors.tests.create');
		Route::post('store', 'TestController@store')->name('instructors.tests.store');
		Route::get('show/{slug}/{id}', 'TestController@show')->name('instructors.tests.show');
		Route::get('show-resume/{slug}/{id}', 'TestController@show_resume')->name('instructors.tests.show-resume');
		Route::get('show-result/{slug_test}/{slug_student}/{id}', 'TestController@show_result')->name('instructors.tests.show-result');
		Route::post('update', 'TestController@update')->name('instructors.tests.update');
		Route::post('update-question', 'TestController@update_question')->name('instructors.tests.update-question');
		Route::get('delete-question/{id}', 'TestController@delete_question')->name('instructors.tests.delete-question');
		Route::post('delete', 'TestController@delete')->name('instructors.tests.delete');
		Route::get('/{content_type}/{slug}/{curso}', 'TestController@index')->name('instructors.tests.index');
	});

	Route::group(['prefix' => 'discussions'], function() {
		Route::get('/', 'DiscussionController@index')->name('instructors.discussions.index');
		Route::get('group/{tipo}/{slug}/{id}', 'DiscussionController@discussions_group')->name('instructors.discussions.group');
		Route::get('show/{slug}/{id}', 'DiscussionController@show')->name('instructors.discussions.show');
		Route::post('store-comment', 'DiscussionController@store_comment')->name('instructors.discussions.store-comment');
	});

	Route::group(['prefix' => 'ratings'], function() {
		Route::get('/{tipo}/{slug}/{id}', 'RatingController@index')->name('instructors.ratings.index');
	});

	Route::group(['prefix' => 'commissions'], function() {
		Route::get('/{type?}', 'CommissionController@index')->name('instructors.commissions.index');
		Route::get('show/{id}', 'CommissionController@show')->name('instructors.commissions.show');
	});

	Route::group(['prefix' => 'liquidations'], function() {
		Route::get('/', 'LiquidationController@index')->name('instructors.liquidations.index');
		Route::post('store', 'LiquidationController@store')->name('instructors.liquidations.store');
		Route::get('show/{id}', 'LiquidationController@show')->name('instructors.liquidations.show');
	});

	Route::group(['prefix' => 'products'], function() {
		Route::get('/', 'MarketplaceController@my_products')->name('instructors.products.index');
		Route::get('create', 'MarketplaceController@create')->name('instructors.products.create');
		Route::post('store', 'MarketplaceController@store')->name('instructors.products.store');
		Route::get('edit/{slug}/{id}', 'MarketplaceController@edit')->name('instructors.products.edit');
		Route::post('update', 'MarketplaceController@update')->name('instructors.products.update');
		Route::get('purchases-record/{slug}/{id}', 'MarketplaceController@purchases_record')->name('instructors.products.purchases-record');
		Route::get('my-purchased-products', 'MarketplaceController@my_purchased_products')->name('instructors.products.my-purchased-products');
	});

	Route::group(['prefix' => 'shopping-cart'], function() {
		Route::get('/', 'ShoppingCartController@index')->name('instructors.shopping-cart.index');
		Route::get('delete/{id}', 'ShoppingCartController@delete')->name('instructors.shopping-cart.delete');
		Route::get('checkout', 'ShoppingCartController@checkout')->name('instructors.shopping-cart.checkout');
		Route::post('apply-coupon', 'CouponController@apply')->name('instructors.shopping-cart.apply-coupon');
		Route::post('bank-transfer-checkout', 'PaymentController@bank_transfer_checkout')->name('instructors.checkout.bank-transfer-checkout');
		Route::get('bill/{payment_type}/{payment_id}', 'PaymentController@bill')->name('instructors.shopping-cart.bill');
		Route::post('paypal-checkout', 'PaypalController@checkout')->name('instructors.shopping-cart.paypal-checkout');
		Route::get('payment-status', 'PaypalController@payment_status')->name('instructors.shopping-cart.payment-status');
		Route::post('mercado-pago-checkout', 'PaymentController@mercado_pago_checkout')->name('instructors.shopping-cart.mercado-pago-checkout');
		Route::get('free-checkout', 'PaymentController@free_checkout')->name('instructors.shopping-cart.free-checkout');
	});

	Route::group(['prefix' => 'tickets'], function() {
		Route::get('/', 'TicketController@index')->name('instructors.tickets.index');
		Route::get('show/{id}', 'TicketController@show')->name('instructors.tickets.show')->middleware('ticket_user');
		Route::post('reply', 'TicketController@reply')->name('instructors.tickets.reply');
		Route::get('close/{id}', 'TicketController@close')->name('instructors.tickets.close')->middleware('ticket_user');
		Route::post('store', 'TicketController@store')->name('instructors.tickets.store');
	});

	Route::group(['prefix' => 'referrals'], function() {
		Route::get('/', 'InstructorController@my_referrals')->name('instructors.referrals.index');
	});

	Route::group(['prefix' => 'membership'], function() {
		Route::get('/', 'MembershipController@index')->name('instructors.membership.index');
	});
});

Route::group(['prefix' => 't-partners', 'middleware' => ['https', 'auth', 'partners']], function() {
	Route::get('/', 'PartnerController@home')->name('partners.index');

	Route::group(['prefix' => 'profile'], function() {
		Route::get('/', 'PartnerController@my_profile')->name('partners.profile.my-profile');
		Route::post('update', 'InstructorController@update_profile')->name('partners.profile.update');
	});

	Route::group(['prefix' => 'commissions'], function() {
		Route::get('/', 'CommissionController@index')->name('partners.commissions.index');
		Route::get('show/{id}', 'CommissionController@show')->name('partners.commissions.show');
	});
});

Route::group(['prefix' => 'admins', 'middleware' => ['https', 'auth', 'admins']], function() {
	Route::get('/', 'AdminController@home')->name('admins.index');

	Route::group(['prefix' => 'profile'], function() {
		Route::get('/', 'AdminController@my_profile')->name('admins.profile.my-profile');
		Route::post('update', 'AdminController@update_profile')->name('admins.profile.update');
	});

	Route::group(['prefix' => 'users'], function() {
		Route::get('students', 'StudentController@index')->name('admins.users.students');
		Route::group(['prefix' => 'instructors'], function() {
			Route::get('/', 'InstructorController@index')->name('admins.users.instructors');
			Route::get('pending-list', 'InstructorController@pending_list')->name('admins.users.instructors.pending-list');
			Route::get('approve/{id}/{accion}', 'InstructorController@approve')->name('admins.users.instructors.approve');
		});
		Route::get('t-partners', 'PartnerController@index')->name('admins.users.partners');
		Route::get('administrators', 'AdminController@index')->name('admins.users.administrators');
		Route::post('store', 'AdminController@store_user')->name('admins.users.store');
		Route::get('show/{id}', 'AdminController@show_user')->name('admins.users.show');
		Route::post('update', 'AdminController@update_user')->name('admins.users.update');
		Route::post('change-status', 'AdminController@change_status_user')->name('admins.users.change-status');
		Route::post('send-mail-students-by-course', 'AdminController@send_mail_students_by_course')->name('admins.users.send-mail-students-by-course');
		Route::post('send-mail-all-students', 'AdminController@send_mail_all_students')->name('admins.users.send-mail-all-students');
	});

	Route::group(['prefix' => 'administrative-profiles'], function() {
		Route::get('/', 'ProfileController@index')->name('admins.profiles.index');
		Route::post('store', 'ProfileController@store')->name('admins.profiles.store');
		Route::get('show/{id}', 'ProfileController@show')->name('admins.profiles.show');
		Route::post('update', 'ProfileController@update')->name('admins.profiles.update');
		Route::get('delete/{id}', 'ProfileController@delete')->name('admins.profiles.delete');
	});

	Route::group(['prefix' => 't-courses'], function() {
		Route::get('/', 'CourseController@courses_record')->name('admins.courses.index');
		Route::get('published', 'CourseController@published_record')->name('admins.courses.published');
		Route::get('pending', 'CourseController@pending_for_publication')->name('admins.courses.pending-for-publication');
		Route::get('disabled', 'CourseController@disabled_record')->name('admins.courses.disabled-record');
		Route::get('show/{slug}/{id}', 'CourseController@show')->name('admins.courses.show');
		Route::get('resume/{slug}/{id}', 'CourseController@resume')->name('admins.courses.resume');
		Route::post('update', 'CourseController@update')->name('admins.courses.update');
		Route::get('lessons/show-video/{id}', 'LessonController@show_video')->name('admins.courses.lessons.show-video');
		Route::get('load-video-duration/{leccion}/{duracion?}', 'LessonController@load_video_duration')->name('admins.courses.lessons.load-video-duration');
		Route::get('lessons/delete/{id}', 'LessonController@delete')->name('admins.courses.lessons.delete');
		Route::get('lessons/{slug}/{id}', 'LessonController@record')->name('admins.courses.lessons');
		Route::post('change-status', 'AdminController@change_status')->name('admins.courses.change-status');
		Route::post('send-corrections', 'AdminController@send_corrections')->name('admins.courses.send-corrections');
		Route::get('home-cover', 'AdminController@home_cover')->name('admins.courses.home-cover');
		Route::post('upload-home-cover', 'AdminController@upload_home_cover')->name('admin.courses.upload-home-cover');	
		Route::post('load-cover-image', 'AdminController@load_cover_image')->name('admin.courses.load-cover-image');
		Route::get('home-sliders', 'AdminController@home_sliders')->name('admin.courses.home-sliders');
		Route::post('update-home-sliders', 'AdminController@update_home_sliders')->name('admin.courses.update-home-sliders');
		Route::get('featured', 'AdminController@featured')->name('admins.courses.featured');
		Route::post('update-featured', 'AdminController@update_featured')->name('admins.courses.update-featured');
		Route::group(['prefix' => 'reports'], function() {
			Route::get('sales', 'CourseController@sales')->name('admins.courses.reports.sales');
			Route::get('show-sales/{curso}', 'CourseController@show_sales')->name('admins.courses.reports.show-sales');
			Route::get('students', 'CourseController@students')->name('admins.courses.reports.students');
			Route::get('show-students/{curso}', 'CourseController@show_students')->name('admins.courses.reports.show-students');
			Route::get('ratings', 'CourseController@ratings')->name('admins.courses.reports.ratings');
			Route::get('show-ratings/{curso}', 'CourseController@show_ratings')->name('admins.courses.reports.show-ratings');
			Route::post('add-rating', 'RatingController@store')->name('admins.courses.reports.add-rating');
		});
		Route::get('show-by-instructor/{slug}/{id}', 'CourseController@show_by_instructor')->name('admins.courses.show-by-instructor');
	});

	Route::group(['prefix' => 't-mentorings'], function() {
		Route::get('/', 'CertificationController@certifications_record')->name('admins.certifications.index');
		Route::get('published', 'CertificationController@published_record')->name('admins.certifications.published');
		Route::get('pending', 'CertificationController@pending_for_publication')->name('admins.certifications.pending-for-publication');
		Route::get('disabled', 'CertificationController@disabled_record')->name('admins.certifications.disabled-record');
		Route::get('show/{slug}/{id}', 'CertificationController@show')->name('admins.certifications.show');
		Route::get('resume/{slug}/{id}', 'CertificationController@resume')->name('admins.certifications.resume');
		Route::post('update', 'CertificationController@update')->name('admins.certifications.update');
		Route::post('change-status', 'AdminController@change_status')->name('admins.certifications.change-status');
		Route::post('send-corrections', 'AdminController@send_corrections')->name('admins.certifications.send-corrections');
		Route::get('home-cover', 'AdminController@home_cover')->name('admins.certifications.home-cover');
		Route::post('upload-home-cover', 'AdminController@upload_home_cover')->name('admin.certifications.upload-home-cover');	
		Route::post('load-cover-image', 'AdminController@load_cover_image')->name('admin.certifications.load-cover-image');
		Route::get('home-sliders', 'AdminController@home_sliders')->name('admins.certifications.home-sliders');
		Route::post('update-home-sliders', 'AdminController@update_home_sliders')->name('admins.certifications.update-home-sliders');
		Route::get('featured', 'AdminController@featured')->name('admins.certifications.featured');
		Route::post('update-featured', 'AdminController@update_featured')->name('admins.certifications.update-featured');
		Route::group(['prefix' => 'reports'], function() {
			Route::get('sales', 'CertificationController@sales')->name('admins.certifications.reports.sales');
			Route::get('show-sales/{curso}', 'CertificationController@show_sales')->name('admins.certifications.reports.show-sales');
			Route::get('students', 'CertificationController@students')->name('admins.certifications.reports.students');
			Route::get('show-students/{curso}', 'CertificationController@show_students')->name('admins.certifications.reports.show-students');
			Route::get('ratings', 'CertificationController@ratings')->name('admins.certifications.reports.ratings');
			Route::get('show-ratings/{curso}', 'CertificationController@show_ratings')->name('admins.certifications.reports.show-ratings');
		});
		Route::get('show-by-instructor/{slug}/{id}', 'CertificationController@show_by_instructor')->name('admins.certifications.show-by-instructor');
	});

	Route::group(['prefix' => 't-books'], function() {
		Route::get('/', 'PodcastController@podcasts_record')->name('admins.podcasts.index');
		Route::get('pending', 'PodcastController@pending_for_publication')->name('admins.podcasts.pending-for-publication');
		Route::get('published', 'PodcastController@published_record')->name('admins.podcasts.published');
		Route::get('disabled', 'PodcastController@disabled_record')->name('admins.podcasts.disabled-record');
		Route::get('show/{slug}/{id}', 'PodcastController@show')->name('admins.podcasts.show');
		Route::get('resume/{slug}/{id}', 'PodcastController@resume')->name('admins.podcasts.resume');
		Route::post('update', 'PodcastController@update')->name('admins.podcasts.update');
		Route::post('change-status', 'AdminController@change_status')->name('admins.podcasts.change-status');
		Route::post('send-corrections', 'AdminController@send_corrections')->name('admins.podcasts.send-corrections');	
		Route::get('featured', 'AdminController@featured')->name('admins.podcasts.featured');
		Route::post('update-featured', 'AdminController@update_featured')->name('admins.podcasts.update-featured');
		Route::group(['prefix' => 'reports'], function() {
			Route::get('sales', 'PodcastController@sales')->name('admins.podcasts.reports.sales');
			Route::get('show-sales/{podcast}', 'PodcastController@show_sales')->name('admins.podcasts.reports.show-sales');
			Route::get('students', 'PodcastController@students')->name('admins.podcasts.reports.students');
			Route::get('show-students/{podcast}', 'PodcastController@show_students')->name('admins.podcasts.reports.show-students');
			Route::get('ratings', 'PodcastController@ratings')->name('admins.podcasts.reports.ratings');
			Route::get('show-ratings/{podcast}', 'PodcastController@show_ratings')->name('admins.podcasts.reports.show-ratings');
		});
		Route::get('show-by-instructor/{slug}/{id}', 'PodcastController@show_by_instructor')->name('admins.podcasts.show-by-instructor');
	});

	Route::group(['prefix' => 't-master-class'], function() {
		Route::get('/', 'MasterClassController@index')->name('admins.master-class.index');
		Route::get('create', 'MasterClassController@create')->name('admins.master-class.create');
		Route::post('store', 'MasterClassController@store')->name('admins.master-class.store');
		Route::get('edit/{slug}/{id}', 'MasterClassController@edit')->name('admins.master-class.edit');
		Route::post('update', 'MasterClassController@update')->name('admins.master-class.update');
		Route::post('load-resource', 'MasterClassController@load_resource')->name('admins.master-class.load-resource');
		Route::get('resources/{clase}', 'MasterClassController@resources')->name('admins.master-class.resources');
		Route::post('delete-resource', 'MasterClassController@delete_resource')->name('admins.master-class.delete-resource');
		Route::get('change-status/{id}/{status}', 'MasterClassController@change_status')->name('admins.master-class.change-status');
		Route::get('disabled', 'MasterClassController@disabled')->name('admins.master-class.disabled');
	});
	

	Route::group(['prefix' => 'tests'], function() {
		Route::get('show/{show}/{id}', 'TestController@show')->name('admins.tests.show');
	});

	Route::group(['prefix' => 'tags'], function() {
		Route::get('/', 'TagController@index')->name('admins.tags.index');
		Route::post('store', 'TagController@store')->name('admins.tags.store');
		//Consulta AJAX
		Route::get('edit/{id}', 'TagController@edit')->name('admins.tags.edit');
		Route::post('update', 'TagController@update')->name('admins.tags.update');
		Route::get('delete/{id}', 'TagController@delete')->name('admins.tags.delete');
	});

	Route::group(['prefix' => 'orders'], function() {
		Route::get('/', 'PurchaseController@index')->name('admins.orders.index');
		Route::get('create', 'PurchaseController@create')->name('admins.orders.create');
		Route::post('store', 'PurchaseController@store')->name('admins.orders.store');
		Route::get('show-bill/{compra}', 'PurchaseController@show_bill')->name('admins.orders.show-bill');
	});


	Route::group(['prefix' => 'liquidations'], function() {
		Route::get('/', 'LiquidationController@index')->name('admins.liquidations.index');
		Route::get('pending', 'LiquidationController@pending')->name('admins.liquidations.pending');
		Route::post('update', 'LiquidationController@update')->name('admins.liquidations.update');
		Route::get('generate-all', 'LiquidationController@generate_all')->name('admins.liquidations.generate-all');
	});

	Route::group(['prefix' => 'coupons'], function() {
		Route::get('/', 'CouponController@index')->name('admins.coupons.index');
		Route::get('show/{id}', 'CouponController@show')->name('admins.coupons.show');
		Route::post('store', 'CouponController@store')->name('admins.coupons.store');
		Route::post('update', 'CouponController@update')->name('admins.coupons.update');
		Route::get('applied_coupons', 'CouponController@applied_coupons')->name('admins.coupons.applied_coupons');
	});

	Route::group(['prefix' => 'tickets'], function() {
		Route::get('new', 'TicketController@new_tickets')->name('admins.tickets.new-tickets');
		Route::get('open', 'TicketController@open_tickets')->name('admins.tickets.open-tickets');
		Route::get('closed', 'TicketController@closed_tickets')->name('admins.tickets.closed-tickets');
		Route::get('show/{id}', 'TicketController@show')->name('admins.tickets.show');
		Route::post('reply', 'TicketController@reply')->name('admins.tickets.reply');
		Route::get('categories', 'TicketController@categories')->name('admins.tickets.categories');
		Route::get('delete-category/{id}', 'TicketController@delete_category')->name('admins.tickets.delete-category');
		Route::post('create-category', 'TicketController@create_category')->name('admins.tickets.create-category');
	});

	Route::group(['prefix' => 'finances'], function() {
		Route::get('earnings', 'PaymentController@index')->name('admins.finances.earnings');
		Route::get('show-earning/{id}', 'PaymentController@show_earning')->name('admins.finances.show-earning');
		Route::get('expenses', 'LiquidationController@expenses')->name('admins.finances.expenses');
		Route::get('show-expense/{id}', 'LiquidationController@show_expense')->name('admins.finances.show-expense');
		Route::get('balance', 'AdminController@balance')->name('admins.finances.balance');
	});

	Route::group(['prefix' => 'bank'], function(){
		Route::get('accounts', 'BankController@index')->name('admins.bank.accounts');
		Route::post('store', 'BankController@store')->name('admins.bank.store');
		Route::get('show/{id}', 'BankController@show')->name('admins.bank.show');
		Route::post('update', 'BankController@update')->name('admins.bank.update');
		Route::get('change-status/{id}/{status}', 'BankController@change_status')->name('admins.bank.change-status');
		Route::get('transfers-record', 'BankController@transfers_record')->name('admins.bank.transfers-record');
		Route::get('pending-transfers', 'BankController@pending_transfers')->name('admins.bank.pending-transfers');
		Route::get('update-transfer/{id}/{status}', 'BankController@update_transfer')->name('admins.bank.update-transfer');
		Route::get('show-details/{id_transferencia}', 'BankController@show_details')->name('admins.bank.show-details');
	});

	Route::group(['prefix' => 'reports'], function() {
		Route::get('best-selling-courses', 'CourseController@best_selling')->name('admins.reports.best-selling-courses');
		Route::get('most-taken-courses', 'CourseController@most_taken')->name('admins.reports.most-taken-courses');
		Route::get('best-rating-courses', 'CourseController@best_rating')->name('admins.reports.best-rating-courses');
		Route::get('most-recent-courses', 'CourseController@most_recent')->name('admins.reports.most-recent-courses');
		Route::get('commissions-by-reference', 'CommissionController@commissions_by_reference')->name('admins.reports.commissions-by-reference');
	});

	Route::group(['prefix' => 'commissions'], function() {
		Route::get('show/{id}', 'CommissionController@show')->name('admins.commissions.show');
	});

	Route::group(['prefix' => 't-events'], function(){
		Route::get('/', 'EventController@index')->name('admins.events.index');
		Route::post('store', 'EventController@store')->name('admins.events.store');
		Route::get('show/{slug}/{id}', 'EventController@show')->name('admins.events.show');
		Route::post('update', 'EventController@update')->name('admins.events.update');
		Route::get('delete-image/{id}', 'EventController@delete_image')->name('admins.events.delete-image');
		Route::post('change-file', 'EventController@change_file')->name('admins.events.change-file');
		Route::post('add-testimony', 'EventController@add_testimony')->name('admins.events.add-testimony');
		Route::post('add-presale', 'EventController@add_presale')->name('admins.events.add-presale');
		Route::get('delete-presale/{id}', 'EventController@delete_presale')->name('admins.events.delete-presale');
		Route::get('subscriptions/{slug}/{id}', 'EventController@show_subscriptions')->name('admins.events.subscriptions');
		Route::get('show-subscription/{id}', 'EventController@show_subscription')->name('admins.events.show-subscription');
		Route::get('delete-subscription/{id}', 'EventController@delete_subscription')->name('admins.events.delete-subscription');
		Route::get('disabled/{id}/{status}', 'EventController@disabled')->name('admins.events.disabled');
		Route::get('record', 'EventController@record')->name('admins.events.record');
		Route::post('send-mail', 'EventController@send_mail')->name('admins.events.send-mail');
	});

	Route::group(['prefix' => 'gifts'], function() {
		Route::get('/', 'GiftController@index')->name('admins.gifts.index');
		Route::post('store', 'GiftController@store')->name('admins.gifts.store');
		Route::get('delete/{id}', 'GiftController@delete')->name('admins.gifts.delete');
		Route::get('user-purchases', 'GiftController@user_purchases')->name('admins.gifts.user-purchases');
		Route::get('events-inscriptions', 'GiftController@events_inscriptions')->name('admins.gifts.events-inscriptions');
	});

	Route::group(['prefix' => 'newsletters'], function() {
		Route::get('/', 'NewsletterController@index')->name('admins.newsletters.index');
		Route::post('store', 'NewsletterController@store')->name('admins.newsletters.store');
		Route::get('subscribers', 'NewsletterController@subscribers')->name('admins.newsletters.subscribers');
	});

});
