<?php

Route::group(['middleware' => ['web']], function () {
    Route::prefix('admin')->group(function () {
        // Admin Routes
        Route::group(['middleware' => ['admin']], function () {
            // Catalog Routes
            Route::prefix('catalog')->group(function () {
                // Catalog Services Routes
                Route::get('/services', 'MY\Service\Http\Controllers\ServiceController@index')->defaults('_config', [
                    'view' => 'service::catalog.services.index'
                ])->name('admin.catalog.services.index');

                Route::get('/services/create', 'MY\Service\Http\Controllers\ServiceController@create')->defaults('_config', [
                    'view' => 'service::catalog.services.create'
                ])->name('admin.catalog.services.create');

                Route::post('/services/create', 'MY\Service\Http\Controllers\ServiceController@store')->defaults('_config', [
                    'redirect' => 'admin.catalog.services.index'
                ])->name('admin.catalog.services.store');

                Route::get('/services/edit/{id}', 'MY\Service\Http\Controllers\ServiceController@edit')->defaults('_config', [
                    'view' => 'service::catalog.services.edit'
                ])->name('admin.catalog.services.edit');

                Route::put('/services/edit/{id}', 'MY\Service\Http\Controllers\ServiceController@update')->defaults('_config', [
                    'redirect' => 'admin.catalog.families.index'
                ])->name('admin.catalog.services.update');

                Route::post('/services/delete/{id}', 'MY\Service\Http\Controllers\ServiceController@destroy')->name('admin.catalog.services.delete');

                //service massupdate
                Route::post('services/massupdate', 'MY\Service\Http\Controllers\ServiceController@massUpdate')->defaults('_config', [
                    'redirect' => 'admin.catalog.services.index'
                ])->name('admin.catalog.services.massupdate');

                Route::post('/services/massdelete', 'MY\Service\Http\Controllers\ServiceController@massDestroy')->name('admin.catalog.services.massdelete');
            });
        });
    });
});