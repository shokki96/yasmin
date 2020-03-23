<?php

Route::group(['middleware' => ['web']], function () {
    Route::prefix('admin')->group(function () {
        // Admin Routes
        Route::group(['middleware' => ['admin']], function () {
            // Catalog Routes
            Route::prefix('catalog')->group(function () {
                // Catalog Services Routes
                Route::get('/services', 'Webkul\Product\Http\Controllers\ProductController@index')->defaults('_config', [
                    'view' => 'service::catalog.services.index'
                ])->name('admin.catalog.services.index');

                Route::get('/services/edit/{id}', 'Webkul\Attribute\Http\Controllers\AttributeFamilyController@edit')->defaults('_config', [
                    'view' => 'service::catalog.services.edit'
                ])->name('admin.catalog.services.edit');

                Route::put('/services/edit/{id}', 'Webkul\Attribute\Http\Controllers\AttributeFamilyController@update')->defaults('_config', [
                    'redirect' => 'admin.catalog.families.index'
                ])->name('admin.catalog.services.update');

                Route::post('/services/delete/{id}', 'Webkul\Attribute\Http\Controllers\AttributeController@destroy')->name('admin.catalog.services.delete');

                //service massupdate
                Route::post('services/massupdate', 'Webkul\Product\Http\Controllers\ProductController@massUpdate')->defaults('_config', [
                    'redirect' => 'admin.catalog.services.index'
                ])->name('admin.catalog.services.massupdate');

                Route::post('/services/massdelete', 'Webkul\Attribute\Http\Controllers\AttributeController@massDestroy')->name('admin.catalog.services.massdelete');
            });
        });
    });
});