<?php

use App\Models\Plan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\GatewayController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\Admin\FAQController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Customer\DashboardController;




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
Route::get('/maintenance', [\App\Http\Controllers\FrontController::class,'maintenance'])->name('maintenance');


Route::group(['middleware'=> 'maintenance_mode'], function(){
Route::get('/', [\App\Http\Controllers\FrontController::class,'home'])->name('home');
Route::get('/blog', [\App\Http\Controllers\FrontController::class,'blog'])->name('blog');
Route::get('/blog/details/{slug}', [\App\Http\Controllers\FrontController::class,'blog_details'])->name('blog.details');
Route::get('/publications', [\App\Http\Controllers\FrontController::class,'publications'])->name('publications');
Route::get('/publications/details/{slug}', [\App\Http\Controllers\FrontController::class,'publications_details'])->name('publications.details');
    // Route::get('/','FrontController@home')->name('home');
// Route::get('/', [AdminLoginController::class,'index'])->name('login');

});



//#region admin route
Route::group(['as' => 'admin.', 'prefix' => 'admin'], function () {

    Route::group(['middleware' => 'guest'], function () {
        Route::get('/login', [\App\Http\Controllers\Auth\AdminLoginController::class,'index'])->name('login');

        Route::post('/login', [\App\Http\Controllers\Auth\AdminLoginController::class,'authenticate'])->name('authenticate');

        Route::get('/password/reset', [\App\Http\Controllers\Auth\ForgotPasswordController::class,'showLinkRequestFormAdmin'])->name('password.request');

        Route::post('/password/email', [\App\Http\Controllers\Auth\ForgotPasswordController::class,'sendResetLinkEmailAdmin'])->name('password.email');

    });

    Route::group(['middleware' => 'auth'], function () {
        // Route::get('/logout', ['uses' => 'Auth\AdminLoginController@logout', 'as' => 'logout']);
        Route::get('/logout', [\App\Http\Controllers\Auth\AdminLoginController::class,'logout'])->name('logout');


        Route::group(['namespace' => ''], function () {
            Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class,'index'])->name('dashboard');
            Route::get('/notification/counter', [\App\Http\Controllers\Admin\DashboardController::class,'countNotification'])->name('notification.counter');
            Route::resource('/customers', \App\Http\Controllers\Admin\CustomerController::class);
            Route::resource('/blog-category', \App\Http\Controllers\Admin\BlogCategoryController::class);
            Route::get('/get/all', [\App\Http\Controllers\Admin\BlogCategoryController::class,'getAll'])->name('get.all.blogcategory');
            Route::resource('/bloglist', \App\Http\Controllers\Admin\BloglistController::class);
            Route::get('/get/alls', [\App\Http\Controllers\Admin\BloglistController::class,'getAll'])->name('get.all.bloglist');
            Route::resource('/event-category', \App\Http\Controllers\Admin\EventCategoryController::class);

            Route::resource('/ibft', \App\Http\Controllers\Admin\IbftController::class);
            Route::get('/get/all/ibft', [\App\Http\Controllers\Admin\IbftController::class,'getAll'])->name('get.all.ibft');

            Route::resource('/fees', \App\Http\Controllers\Admin\FeesController::class);
            Route::get('/header/fees', [\App\Http\Controllers\Admin\FeesController::class, 'header'])->name('header.fees');
            Route::get('fees/get/all', [\App\Http\Controllers\Admin\FeesController::class,'getAll'])->name('fees.get.all');

            Route::resource('/services', \App\Http\Controllers\Admin\ServicesController::class);
            Route::get('/header/services', [\App\Http\Controllers\Admin\ServicesController::class, 'header'])->name('header.services');
            Route::get('services/get/all', [\App\Http\Controllers\Admin\ServicesController::class,'getAll'])->name('services.get.all');

            Route::resource('/courses', \App\Http\Controllers\Admin\CoursesController::class);
            Route::get('courses/get/all', [\App\Http\Controllers\Admin\CoursesController::class,'getAll'])->name('courses.get.all');


            Route::get('/resources/create', [\App\Http\Controllers\Admin\ResourcesController::class, 'create'])->name('resources.create');
            Route::post('/resources/store', [\App\Http\Controllers\Admin\ResourcesController::class, 'store'])->name('resources.store');

            Route::resource('/teams', \App\Http\Controllers\Admin\TeamsController::class);
            Route::get('teams/get/all', [\App\Http\Controllers\Admin\TeamsController::class,'getAll'])->name('teams.get.all');

            Route::resource('/instruments', \App\Http\Controllers\Admin\InstrumentsController::class);
            Route::get('instruments/get/all', [\App\Http\Controllers\Admin\InstrumentsController::class,'getAll'])->name('instruments.get.all');

            Route::resource('/questions', \App\Http\Controllers\Admin\QuestionsController::class);
            Route::get('questions/get/all', [\App\Http\Controllers\Admin\QuestionsController::class,'getAll'])->name('questions.get.all');

            Route::get('/signupinfo/create', [\App\Http\Controllers\Admin\SignupInfoController::class, 'create'])->name('signupinfo.create');
            Route::post('/signupinfo/store', [\App\Http\Controllers\Admin\SignupInfoController::class, 'store'])->name('signupinfo.store');

            Route::get('/omug/create', [\App\Http\Controllers\Admin\OmugController::class, 'create'])->name('omug.create');
            Route::post('/omug/store', [\App\Http\Controllers\Admin\OmugController::class, 'store'])->name('omug.store');

            Route::get('/contact/create', [\App\Http\Controllers\Admin\ContactController::class, 'create'])->name('contact.create');
            Route::post('/contact/store', [\App\Http\Controllers\Admin\ContactController::class, 'store'])->name('contact.store');

            Route::resource('/page', \App\Http\Controllers\Admin\PageController::class);


            Route::get('/slider/index', [\App\Http\Controllers\Admin\ThemeController::class, 'slider_index'])->name('theme.slider.index');
            Route::post('/slider/store', [\App\Http\Controllers\Admin\ThemeController::class, 'slider_store'])->name('theme.slider.store');
            
            Route::get('/contact/us/index', [\App\Http\Controllers\Admin\ThemeController::class, 'contact_us_index'])->name('theme.contact.index');
            Route::post('/contact/store', [\App\Http\Controllers\Admin\ThemeController::class, 'contact_us_store'])->name('theme.contact.store');
            
            Route::resource('/blog-category', \App\Http\Controllers\Admin\BlogCategoryController::class);

            Route::resource('/category-publication', \App\Http\Controllers\Admin\CategoryPublicationController::class);

            Route::get('category-publication/get/all', [\App\Http\Controllers\Admin\CategoryPublicationController::class,'getAll'])->name('category-publication.get.all');

            Route::resource('/publications', \App\Http\Controllers\Admin\PublicationsController::class);
            Route::get('publications/get/all', [\App\Http\Controllers\Admin\PublicationsController::class,'getAll'])->name('publications.get.all');

            Route::group(['as' => 'customer.', 'prefix' => 'customer'], function () {
                // Route::get('/all', 'CustomerController@getAll')->name('get.all');
                Route::get('/all', [\App\Http\Controllers\Admin\CustomerController::class,'getAll'])->name('get.all');
                // Route::post('/change-plan', 'CustomerController@changePlan')->name('plan.change');
                Route::post('/change-plan', [\App\Http\Controllers\Admin\CustomerController::class,'changePlan'])->name('plan.change');
                // Route::post('/login-as', 'CustomerController@loginAs')->name('login.ass');
                Route::post('/login-as', [\App\Http\Controllers\Admin\CustomerController::class,'loginAs'])->name('login.ass');
//                Edit Current Plan
                // Route::get('/plan/edit/{customer}', 'CustomerController@editCustomerPLan')->name('current.plan.edit');
                Route::get('/plan/edit/{customer}', [\App\Http\Controllers\Admin\CustomerController::class,'editCustomerPLan'])->name('current.plan.edit');
                // Route::post('/plan/update/{customer}', 'CustomerController@updateCustomerPLan')->name('current.plan.update');
                Route::get('/plan/update/{customer}', [\App\Http\Controllers\Admin\CustomerController::class,'updateCustomerPLan'])->name('current.plan.update');
                // Route::get('get/info', 'CustomerController@getCustomerInfo')->name('get.info');
                Route::get('get/info', [\App\Http\Controllers\Admin\CustomerController::class,'getCustomerInfo'])->name('get.info');

                Route::get('/overview', [\App\Http\Controllers\Customer\ComposeController::class,'overview'])->name('overview');
                Route::get('/overview/get/data', [\App\Http\Controllers\Customer\ComposeController::class,'overview_get_data'])->name('overview.get.data');
                Route::get('/overview/data/delete', [\App\Http\Controllers\Customer\ComposeController::class,'overview_data_delete'])->name('overview.data.delete');
                // Route::get('/overview', '\App\Http\Controllers\Admin\ComposeController@overview')->name('overview')->middleware('can:show_message_overview');
                // Route::get('/overview/get/data', 'ComposeController@overview_get_data')->name('overview.get.data');
                // Route::delete('/overview/data/delete', 'ComposeController@overview_data_delete')->name('overview.data.delete');
                Route::get('/reseller-customer/assign-number', [\App\Http\Controllers\Customer\ResellerCustomerController::class,'assignNumber'])->name('reseller.customer.number.assign');
                // Route::post('/reseller-customer/assign-number', 'ResellerCustomerController@assignNumber')->name('reseller.customer.number.assign');
                Route::get('/ibft/transfer/${id}', [\App\Http\Controllers\Customer\ComposeController::class,'ibft_transfer'])->name('ibft.transfer');
                Route::get('/ibft/transfer/list', [\App\Http\Controllers\Customer\ComposeController::class,'ibft_transfer_list'])->name('ibft.transfer.list');
            });


            Route::group(['as' => 'plan.', 'prefix' => 'plan'], function () {
                // Route::get('/all', 'PlanController@getAll')->name('get.all');
                Route::get('/all', [\App\Http\Controllers\Admin\PlanController::class,'getAll'])->name('get.all');
                // Route::get('/requests', 'PlanController@requests')->name('requests');
                Route::get('/requests', [\App\Http\Controllers\Admin\PlanController::class,'requests'])->name('requests');
                // Route::get('/requests/get', 'PlanController@get_requests')->name('get.requests');
                Route::get('/requests/get', [\App\Http\ControllersAdmin\PlanController::class,'get_requests'])->name('get.requests');
            });

            Route::group(['as' => 'settings.', 'prefix' => 'settings'], function () {
                // Route::get('/', 'SettingsController@index')->name('index');
                Route::get('/', [\App\Http\Controllers\Admin\SettingsController::class,'index'])->name('index');
                // Route::post('/update/profile', 'SettingsController@profile_update')->name('profile_update');
                Route::post('/update/profile', [\App\Http\Controllers\Admin\SettingsController::class,'profile_update'])->name('profile_update');
                // Route::post('/update/application', 'SettingsController@app_update')->name('app_update');
                Route::post('/update/application', [\App\Http\Controllers\Admin\SettingsController::class,'app_update'])->name('app_update');
                // header
                Route::post('/update/header/title', [\App\Http\Controllers\Admin\SettingsController::class,'header_title'])->name('header_title');
                // Route::post('/update/smtp', 'SettingsController@smtp_update')->name('smtp_update');
                Route::post('/update/smtp', [\App\Http\Controllers\Admin\SettingsController::class,'smtp_update'])->name('smtp_update');
                // Route::post('/update/api', 'SettingsController@api_update')->name('api_update');
                Route::post('/update/api', [\App\Http\Controllers\Admin\SettingsController::class,'api_update'])->name('api_update');
                // Route::post('/email-template/store', 'SettingsController@templateStore')->name('email.template.store');
                Route::post('/email-template/store', [\App\Http\Controllers\Admin\SettingsController::class,'templateStore'])->name('email.template.store');
                // Route::post('/update/local/setting', 'SettingsController@local_settings')->name('local.setting');
                Route::post('/update/local/setting', [\App\Http\Controllers\Admin\SettingsController::class,'local_settings'])->name('local.setting');
                // Route::post('/sending-setting', 'SettingsController@sending_setting')->name('sending.setting');
                Route::post('/sending-setting', [\App\Http\Controllers\Admin\SettingsController::class,'sending_setting'])->name('sending.setting');
                // Route::get('/gateway/numbers', 'SettingsController@getGatewayNumber')->name('gateway.numbers');
                Route::get('/gateway/numbers', [\App\Http\Controllers\Admin\SettingsController::class,'getGatewayNumber'])->name('gateway.numbers');
                // Route::get('/otp/index', 'SettingsController@otpSettins')->name('otp.index');
                Route::get('/otp/index', [\App\Http\Controllers\Admin\SettingsController::class,'otpSettins'])->name('otp.index');
                // Route::get('get/all/otp/user', 'SettingsController@activeOtpUser')->name('get.all.otp.user');
                Route::get('get/all/otp/user', [\App\Http\Controllers\Admin\SettingsController::class,'activeOtpUser'])->name('get.all.otp.user');
                // Route::post('/otp', 'SettingsController@otpSetting')->name('otp');
                Route::post('/otp', [\App\Http\Controllers\Admin\SettingsController::class,'otpSetting'])->name('otp');
                // Route::get('/customer/otp/status', 'SettingsController@getOtpStatus')->name('user.otp.status');
                Route::get('/customer/otp/status', [\App\Http\Controllers\Admin\SettingsController::class,'getOtpStatus'])->name('user.otp.status');
                // Route::post('/cache', 'SettingsController@cacheSettings')->name('cache');
                Route::post('/cache', [\App\Http\Controllers\Admin\SettingsController::class,'cacheSettings'])->name('cache');


            });
//            For Database Backup
            Route::get('/db-backup', 'SettingsController@dbBackupList')->name('db.backup');
            Route::get('/download/db-backup', 'SettingsController@downloadDbBackup')->name('download.db.backup');


            Route::group(['as' => 'ticket.', 'prefix' => 'ticket'], function () {
                // Route::get('/', 'TicketController@index')->name('index');
                Route::get('/', [\App\Http\Controllers\Admin\TicketController::class,'index'])->name('index');
                // Route::get('/get-all', 'TicketController@show')->name('get.all');
                Route::get('/get-all', [\App\Http\Controllers\Admin\TicketController::class,'show'])->name('get.all');
                Route::post('/store', 'TicketController@store')->name('store');
                Route::get('/reply', 'TicketController@reply')->name('reply');
                Route::post('/status', 'TicketController@status')->name('status');
                Route::get('/download', 'TicketController@documentDownload')->name('download');
            });


            Route::group(['as' => 'addon.', 'prefix' => 'addon'], function () {
                Route::get('/', 'AddonController@index')->name('index');
                Route::get('/import', 'AddonController@import')->name('import');
                Route::post('/import', 'AddonController@importPost')->name('import');
                Route::get('/get/all', 'AddonController@getAll')->name('get.all');
                Route::delete('/uninstall', 'AddonController@uninstall')->name('uninstall');
                Route::post('/change-status', 'AddonController@changeStatus')->name('change-status');

            });

            Route::group(['as' => 'page.', 'prefix' => 'page'], function () {
                Route::get('/all', 'PageController@getAll')->name('get.all');
            });
            Route::resource('/faq', 'FAQController');
            Route::get('/get-all/faq', 'FAQController@getAll')->name('get.all.faq');

            Route::group(['as' => 'page.', 'prefix' => 'page'], function () {
                Route::get('/all', 'PageController@getAll')->name('get.all');
            });
            Route::resource('/faq', FAQController::class);
            Route::get('/get-all/faq', [FAQController::class,'getAll'])->name('get.all.faq');

            Route::group(['as' => 'subscribe.', 'prefix' => 'subscribe'], function () {
                // Route::get('/index','SubscribeController@index')->name('index');
                Route::get('/index', [\App\Http\Controllers\Admin\SubscribeController::class,'index'])->name('index');

                // Route::post('/store','SubscribeController@subscribe_store')->name('subscribe.store');
                Route::post('/store', [\App\Http\Controllers\Admin\SubscribeController::class,'subscribe_store'])->name('subscribe.store');

            });

            // Route::get('/user/message/index','UserMessageController@index')->name('user.message.index');
            Route::get('/user/message/index', [\App\Http\Controllers\Admin\UserMessageController::class,'index'])->name('user.message.index');
            // Route::post('/store','UserMessageController@store')->name('user.message.store');
            Route::post('/store', [\App\Http\Controllers\Admin\UserMessageController::class,'store'])->name('user.message.store');


            Route::get('/template','TemplateController@index')->name('template');
            Route::post('/template/store','TemplateController@store')->name('template.store');
            // Route::get('/theme/customize','TemplateController@theme')->name('theme.customize');
            // Route::post('/theme/customize/store','TemplateController@themeStore')->name('theme.customize.store');

            Route::get('/theme/customize',[\App\Http\Controllers\Admin\ThemeController::class,'index'])->name('theme.customize');
            Route::get('/sign/up/info',[\App\Http\Controllers\Admin\ThemeController::class,'sign_up_index'])->name('sign.up.info');
            Route::get('/omug',[\App\Http\Controllers\Admin\ThemeController::class,'omug_index'])->name('omug');
            Route::get('/resources',[\App\Http\Controllers\Admin\ThemeController::class,'resources_index'])->name('resources');
            Route::get('/teams',[\App\Http\Controllers\Admin\ThemeController::class,'teams_index'])->name('teams');
            Route::get('/fees',[\App\Http\Controllers\Admin\ThemeController::class,'fees_index'])->name('fees');
            Route::get('/courses',[\App\Http\Controllers\Admin\ThemeController::class,'courses_index'])->name('courses');
            Route::get('/services',[\App\Http\Controllers\Admin\ThemeController::class,'services_index'])->name('services');
            Route::get('/welcome/section',[\App\Http\Controllers\Admin\ThemeController::class,'welcome_section_index'])->name('welcome.section');
            Route::post('/sign/up/info/store',[\App\Http\Controllers\Admin\ThemeController::class,'sign_up_info'])->name('theme.sign.up.info.store');
            Route::post('/resources/store',[\App\Http\Controllers\Admin\ThemeController::class,'resources'])->name('theme.resources.store');
            Route::post('/omug/store',[\App\Http\Controllers\Admin\ThemeController::class,'omug'])->name('theme.omug.store');
            Route::post('/team/store',[\App\Http\Controllers\Admin\ThemeController::class,'team'])->name('theme.team.store');
            Route::post('/fees/store',[\App\Http\Controllers\Admin\ThemeController::class,'fees'])->name('theme.fees.store');
            Route::post('/courses/store',[\App\Http\Controllers\Admin\ThemeController::class,'courses'])->name('theme.courses.store');
            Route::post('/services/store',[\App\Http\Controllers\Admin\ThemeController::class,'services'])->name('theme.services.store');
            Route::post('/welcome/section/store',[\App\Http\Controllers\Admin\ThemeController::class,'welcome_section'])->name('theme.welcome.section.store');

        });
    });

});
//#endregion


//#region customer routes

//Guest customer route
Route::group(['middleware' => 'guest','maintenance_mode'], function () {
    Route::get('admin/password/reset', 'Admin\ForgotPasswordController@show_form')->name('admin.password.request');
    Route::post('admin/password/reset', 'Admin\ForgotPasswordController@sent_email')->name('admin.password.sent');
    Route::get('admin/password/reset/confirm', 'Admin\ForgotPasswordController@reset_form')->name('admin.password.reset.confirm');
    Route::post('admin/password/reset/confirm', 'Admin\ForgotPasswordController@reset_confirm')->name('admin.password.reset.confirm');
});

Route::group(['middleware' => ['guest:customer','maintenance_mode']], function () {
    // Route::get('/login', ['uses' => 'Auth\CustomerLoginController@index', 'as' => 'login']);
    Route::get('/login', [\App\Http\Controllers\Auth\CustomerLoginController::class,'index'])->name('login');

    // Route::post('/login', ['uses' => 'Auth\CustomerLoginController@authenticate', 'as' => 'authenticate']);
    Route::post('/login', [\App\Http\Controllers\Auth\CustomerLoginController::class,'authenticate'])->name('authenticate');

    Route::get('/sign-up', ['uses' => '\App\Http\Controllers\Auth\CustomerLoginController@sign_up', 'as' => 'signup']);
    Route::post('/sign-up', ['uses' => 'Auth\CustomerLoginController@sign_up_create', 'as' => 'signup']);

    Route::get('password/reset', '\App\Http\Controllers\Auth\ForgotPasswordController@show_form')->name('password.request');
    Route::post('password/reset', '\App\Http\Controllers\Auth\ForgotPasswordController@sent_email')->name('password.sent');
    Route::get('password/reset/confirm', '\App\Http\Controllers\Auth\ForgotPasswordController@reset_form')->name('password.reset.confirm');
    Route::post('password/reset/confirm', '\App\Http\Controllers\Auth\ForgotPasswordController@reset_confirm')->name('password.reset.confirm');

    Route::get('/verify/', ['uses' => 'Auth\CustomerLoginController@verifyView', 'as' => 'customer.verify.view']);
    Route::get('/verify/customer', ['uses' => 'Auth\CustomerLoginController@verify', 'as' => 'customer.verify']);
});

//Auth customer route
Route::group(['as' => 'customer.', 'middleware' => ['auth:customer', 'email.verify:customer']], function () {

    Route::get('/logout', ['uses' => '\App\Http\Controllers\Auth\CustomerLoginController@logout', 'as' => 'logout']);
    Route::post('/login-as-admin', 'Auth\CustomerLoginController@loginAsAdmin')->name('login.as.admin');
    Route::get('/check/plan', function (\Illuminate\Http\Request $request){
        if (!$request->plan_id){
            return abort('404');
        }
        $data['plan']=Plan::find($request->plan_id);
        return view('customer.trigger_plan', $data);
    })->name('trigger.plan');

    Route::group(['namespace' => 'Customer'], function () {
        // Route::group(['auth:customer','middleware' => ['plan.validation']], function () {
            Route::get('/dashboard', ['uses' => '\App\Http\Controllers\Customer\DashboardController@index', 'as' => 'dashboard']);

            Route::get('/download/notice/file', ['uses' => 'DashboardController@downloadAttach', 'as' => 'download.notice.file']);
            Route::get('/all/notice', ['uses' => 'DashboardController@viewAllNotices', 'as' => 'all.notices']);
            Route::get('/notification/counter', ['uses' => 'DashboardController@countNotification', 'as' => 'notification.counter']);
            Route::get('/clear/cache', ['uses' => 'DashboardController@clearCache', 'as' => 'clear.cache']);

            Route::group(['as' => 'settings.', 'prefix' => 'settings'], function () {
                Route::get('/index', ['uses' => 'SettingsController@index', 'as' => 'index']);
                Route::post('/profile-update', ['uses' => 'SettingsController@profile_update', 'as' => 'profile_update']);
                Route::post('/password-update', ['uses' => 'SettingsController@password_update', 'as' => 'password_update']);
                Route::post('/notification-update', ['uses' => 'SettingsController@notification_update', 'as' => 'notification_update']);
                Route::post('/webhook/update', ['uses' => 'SettingsController@webhookUpdate', 'as' => 'webhook_update']);
                Route::post('/data/posting', ['uses' => 'SettingsController@dataPosting', 'as' => 'data_posting']);
            });

//            API Token
            Route::get('/authorization/token/create', 'AuthorizationController@index')->name('authorization.token.create');
            Route::post('/authorization/token/store', 'AuthorizationController@store')->name('authorization.token.store');



            Route::get('/get/all/staff', [\App\Http\Controllers\Customer\StaffController::class,'getAll'])->name('get.all.staff');
            Route::resource('/staff', 'StaffController');
            //Staff Message View Access
            Route::post('/access/staff/message/view/', 'SettingsController@accessStaffViewMessage')->name('access.staff.message.view');

            Route::group(['as' => 'ticket.', 'prefix' => 'ticket'], function () {
                Route::get('/', 'TicketController@index')->name('index');
                Route::post('/store', 'TicketController@store')->name('store');
                Route::get('/get-all', 'TicketController@show')->name('get.all');
                Route::get('/details', 'TicketController@details')->name('details');
                Route::post('/reply', 'TicketController@reply')->name('reply');
                Route::get('/download', 'TicketController@documentDownload')->name('download');
            });

           //            Email Template
            Route::post('/settings/email/template', [\App\Http\Controllers\Customer\SettingsController::class,'templateStore'])->name('settings.email.template');


        // });

        Route::group(['as' => 'billing.', 'prefix' => 'billing'], function () {
            Route::get('/', 'BillingController@index')->name('index');
            Route::get('/change/plan', 'BillingController@changePLan')->name('change.plan');
            Route::get('/reseller/plan', 'BillingController@resellerPlan')->name('reseller.plan');
            Route::get('/phone-numbers', 'BillingController@phone_numbers')->name('phone_numbers');
            Route::get('/get-numbers', 'BillingController@get_numbers')->name('get.numbers');
            Route::post('/update', 'BillingController@update')->name('update');
        });

        Route::post('/cancel/billing', [\App\Http\Controllers\Customer\BillingController::class, 'cancelRequest'])->name('cancel.billing.request');

    });
});

//#endregion

//Db Backup
Route::get('/db/backup', ['uses' => 'ScheduleController@processDbBackup', 'as' => 'db.backup']);

Route::get('/process/upgrade', ['uses' => 'UpgradeController@process', 'as' => 'process.upgrade']);

//Route::redirect('/', route('login'));
Route::redirect('/admin', "/admin/login");


//Route::get('{url}',['uses' => 'RouteController@index']);

// Route::post('/verify/user','FrontController@verifyCode')->name('verify');
Route::post('/verify/user', [\App\Http\Controllers\FrontController::class,'verifyCode'])->name('verify');


Route::get('locale/{type}', [\App\Http\Controllers\Admin\DashboardController::class, 'setLocale'])->name('set.locale');

// Route::get('/{page}','FrontController@page')->name('page');
Route::get('/{page}', [\App\Http\Controllers\FrontController::class,'page'])->name('page');

