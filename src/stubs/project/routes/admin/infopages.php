<?php

use App\Http\Controllers\Admin\InfopageController;
use App\Models\Infopage;

Route::group([
    'controller' => InfopageController::class,
    'middleware' => ['auth:admin', 'can:view,'.Infopage::class],
    'as' => 'infopages.', //naming prefix
    'prefix' => 'infopages', //for routes
], function() {
    Route::get('', 'index')->name('index');
    Route::post('', 'store')->name('store')->middleware('can:create,'.Infopage::class);
    Route::get('create', 'create')->name('create')->middleware('can:create,'.Infopage::class);
    Route::get('{chInfopage}/edit', 'edit')->name('edit');
    Route::get('{chInfopage}', 'edit')->name('show');
    Route::patch('{chInfopage}', 'update')->name('update')->middleware('can:update,chInfopage');
    Route::delete('{chInfopage}', 'destroy')->name('destroy')->middleware('can:delete,chInfopage');

    // @HOOK_INFOPAGES_ROUTES
});
