<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.


Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () {
    Route::crud('user', 'UserCrudController');
    Route::crud('person', 'PersonCrudController');
    Route::crud('library', 'LibraryCrudController');
    Route::crud('person-function', 'PersonFunctionCrudController');
    Route::post('library/{id}/export', 'LibraryCrudController@export');
}); // this should be the absolute last line of this file