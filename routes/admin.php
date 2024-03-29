<?php

use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;

Route::get('/admin-login', [App\Http\Controllers\Auth\LoginController::class, 'adminLogin'])->name('admin.login');

// Route::get('/admin/home', [App\Http\Controllers\HomeController::class, 'admin'])->name('admin.home')->middleware('is_admin');
Route::group(['namespace'=>'App\Http\Controllers\Admin','middleware'=>'is_admin'],function(){
    Route::get('/admin/home', 'AdminController@admin')->name('admin.home');
    Route::get('/admin/change-password', 'AdminController@changepassword')->name('admin.password.change');
    Route::post('/admin/change-update', 'AdminController@updatepassword')->name('admin.password.update');
    Route::get('/admin/logout', 'AdminController@logout')->name('admin.logout');


    //category Routes
    Route::prefix('admin/category')->group(function () {
        Route::get('/','CategoryController@index')->name('category.index');
        Route::post('/store','CategoryController@store')->name('category.store');
        Route::get('/delete/{id}','CategoryController@delete')->name('category.delete');
        Route::get('/edit/{id}','CategoryController@edit');
        Route::post('/update','CategoryController@update')->name('category.update');
        });




    //Global Route
    
    Route::get('/get-sub-category/{id}','CategoryController@GetSubCategory');
    Route::get('/get-child-category/{id}','CategoryController@GetChildCategory');


    //subcategory Routes
    Route::prefix('admin/subcategory')->group(function () {
        Route::get('/','SubcategoryController@index')->name('subcategory.index');
        Route::post('/store','SubcategoryController@store')->name('subcategory.store');
        Route::get('/delete/{id}','subcategoryController@delete')->name('subcategory.delete');
        Route::get('/edit/{id}','subcategoryController@edit');
        Route::post('/update','subcategoryController@update')->name('subcategory.update');
    });
    //childcategory Routes
    Route::prefix('admin/childcategory')->group(function () {
        Route::get('/','ChildcategoryController@index')->name('childcategory.index');
        Route::post('/store','ChildcategoryController@store')->name('childcategory.store');
        Route::get('/delete/{id}','ChildcategoryController@delete')->name('childcategory.delete');
        Route::get('/edit/{id}','ChildcategoryController@edit');
        Route::post('/update','ChildcategoryController@update')->name('childcategory.update');
        });
    //Brand Routes
    Route::prefix('admin/brand')->group(function () {
        Route::get('/','BrandController@index')->name('brand.index');
        Route::post('/store','BrandController@store')->name('brand.store');
        Route::get('/delete/{id}','BrandController@delete')->name('brand.delete');
        Route::get('/edit/{id}','BrandController@edit');
        Route::post('/update','BrandController@update')->name('brand.update');
    });

    
    Route::prefix('admin/setting')->group(function () {
        //seo setting
        Route::prefix('seo')->group(function () {
            Route::get('/','SettingController@seo')->name('seo.setting');
            Route::post('/update/{id}','SettingController@seoUpdate')->name('seo.setting.update');
        });
        //smtp setting
		Route::group(['prefix'=>'smtp'], function(){
			Route::get('/','SettingController@smtp')->name('smtp.setting');
			Route::post('/update/{id}','SettingController@smtpUpdate')->name('smtp.setting.update');
	    });
        //page setting
		Route::group(['prefix'=>'page'], function(){
			Route::get('/','PageController@index')->name('page.index');
            Route::get('/create','PageController@create')->name('create.page');
            Route::post('/store', 'PageController@store')->name('page.store');
            Route::get('/delete/{id}','PageController@destroy')->name('page.delete');
			Route::get('/edit/{id}','PageController@edit')->name('page.edit');
			Route::post('/update/{id}','PageController@update')->name('page.update');
	    });
        //website setting
		Route::group(['prefix'=>'website'], function(){
			Route::get('/','SettingController@website')->name('website.setting');
            Route::post('/update/{id}','SettingController@websiteupdate')->name('website.setting.update');
	    });
        //warehouse setting
		Route::group(['prefix'=>'warehouse'], function(){
			Route::get('/','WarehouseController@index')->name('warehouse.index');
			Route::get('/create','WarehouseController@create')->name('create.warehouse');
			Route::post('/store','WarehouseController@store')->name('store.warehouse');
            Route::get('/delete/{id}','WarehouseController@delete')->name('delete.warehouse');
            Route::get('/edit/{id}','WarehouseController@edit')->name('edit.warehouse');
            Route::post('/update/{id}','WarehouseController@update')->name('update.warehouse');
	    });

    });

	   //Coupon Routes
	   Route::group(['prefix'=>'coupon'], function(){
		   Route::get('/','CouponController@index')->name('coupon.index');
		   Route::post('/store','CouponController@store')->name('store.coupon');
		   Route::delete('/delete/{id}','CouponController@destroy')->name('coupon.delete');
		   Route::get('/edit/{id}','CouponController@edit');
		   Route::post('/update','CouponController@update')->name('update.coupon');
	   });

        //Pickup Point Routes
       Route::group(['prefix'=>'Pickup-point'], function(){
         Route::get('/','PickupController@index')->name('pickup-point.index');
         Route::get('/create','PickupController@create')->name('create.pickup-point');
         Route::post('/store','PickupController@store')->name('store.pickup-point');
         Route::get('/delete/{id}','PickupController@delete')->name('pickup-point.delete');
         Route::get('/edit/{id}','PickupController@edit')->name('edit.pickup-point');
         Route::post('/update/{id}','PickupController@update')->name('update.pickup-point');
     });
     

    //product Routes
    Route::group(['prefix'=>'admin/product'], function(){
        Route::get('/','ProductController@index')->name('product.index');
        Route::get('/create','ProductController@create')->name('product.create');
        Route::post('/store','ProductController@store')->name('product.store');
        Route::get('/delete/{id}','ProductController@delete')->name('product.delete');
        Route::get('/edit/{id}','ProductController@edit')->name('product.edit');
        Route::post('/update/{id}','ProductController@update')->name('product.update');
        Route::get('/active-featured/{id}','ProductController@activefeatured');
        Route::get('/not-featured/{id}','ProductController@notfeatured');
        Route::get('/active-deal/{id}','ProductController@activedeal');
        Route::get('/not-deal/{id}','ProductController@notdeal');
        Route::get('/active-status/{id}','ProductController@activestatus');
        Route::get('/not-status/{id}','ProductController@notstatus');
        
       });
    //Campaign Routes
	Route::group(['prefix'=>'campaign'], function(){
		Route::get('/','CampaignController@index')->name('campaign.index');
		Route::post('/store','CampaignController@store')->name('campaign.store');
		Route::get('/delete/{id}','CampaignController@destroy')->name('campaign.delete');
		Route::get('/edit/{id}','CampaignController@edit');
		Route::post('/update','CampaignController@update')->name('campaign.update');
	});
    //__campaign product routes__//
	Route::group(['prefix'=>'campaign-product'], function(){
		Route::get('/{campaign_id}','CampaignController@campaignProduct')->name('campaign.product');
		Route::get('/add/{id}/{campaign_id}','CampaignController@ProductAddToCampaign')->name('add.product.to.campaign');
		Route::get('/list/{campaign_id}','CampaignController@ProductListCampaign')->name('campaign.product.list');
		Route::get('/remove/{id}','CampaignController@RemoveProduct')->name('product.remove.campaign');
	});
    //Ticket 
	Route::group(['prefix'=>'ticket'], function(){
		Route::get('/','TicketController@index')->name('ticket.index');
		Route::get('/ticket/show/{id}','TicketController@show')->name('admin.ticket.show');
		Route::post('/ticket/reply','TicketController@ReplyTicket')->name('admin.store.reply');
		Route::get('/ticket/close/{id}','TicketController@CloseTicket')->name('admin.close.ticket');
		Route::delete('/ticket/delete/{id}','TicketController@destroy')->name('admin.ticket.delete');
			
	    });
    //website setting
	Route::group(['prefix'=>'payment-gateway'], function(){
		Route::get('/','SettingController@PaymentGateway')->name('payment.gateway');
		Route::post('/update-aamarpay','SettingController@AamarpayUpdate')->name('update.aamarpay');
		Route::post('/update-surjopay','SettingController@SurjopayUpdate')->name('update.surjopay');
	    });
    //__order 
	Route::group(['prefix'=>'order'], function(){
		Route::get('/','OrderController@index')->name('admin.order.index');
		Route::get('/admin/edit/{id}','OrderController@Editorder');
		Route::post('/update/order/status','OrderController@updateStatus')->name('update.order.status');
		Route::get('/view/admin/{id}','OrderController@ViewOrder');
		Route::get('/delete/{id}','OrderController@delete')->name('order.delete');
		 
	}); 
    //__report routes__//
    Route::group(['prefix'=>'report'], function(){
        Route::get('/order','OrderController@Reportindex')->name('report.order.index');
        Route::get('/order/print','OrderController@ReportOrderPrint')->name('report.order.print');
                
    });

    //Blog category
    Route::group(['prefix'=>'blog-category'], function(){
        Route::get('/','BlogController@index')->name('admin.blog.category');
        Route::post('/store','BlogController@store')->name('blog.category.store');
        Route::get('/delete/{id}','BlogController@destroy')->name('blog.category.delete');
        Route::get('/edit/{id}','BlogController@edit');
        Route::post('/update','BlogController@update')->name('blog.category.update');
    });

    //__role create__
    Route::group(['prefix'=>'role'], function(){
        Route::get('/','RoleController@index')->name('manage.role');
        Route::get('/create','RoleController@create')->name('create.role');
        Route::post('/store','RoleController@store')->name('store.role');
        Route::get('/delete/{id}','RoleController@destroy')->name('role.delete');
        Route::get('/edit/{id}','RoleController@edit')->name('role.edit');
        Route::post('/update','RoleController@update')->name('update.role');
    });
    


    });