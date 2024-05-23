<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', '/library');
Route::redirect('/dashboard', '/library');

Route::get('/export', 'App\Http\Controllers\Admin\ExportController@index')->name('export');

Route::post('/export/library', 'App\Http\Controllers\Admin\ExportController@exportLibraries')->name('export.library');
Route::get('/export/blade/library', 'App\Http\Controllers\Admin\ExportController@libraryModal')->name('export.modal.library');

Route::post('/export/stock', 'App\Http\Controllers\Admin\ExportController@exportStocks')->name('export.stock');
Route::get('/export/blade/stock', 'App\Http\Controllers\Admin\ExportController@stockModal')->name('export.modal.stock');

Route::post('/export/building', 'App\Http\Controllers\Admin\ExportController@exportBuildings')->name('export.building');
Route::get('/export/blade/building', 'App\Http\Controllers\Admin\ExportController@buildingModal')->name('export.modal.building');

Route::post('/export/slsp', 'App\Http\Controllers\Admin\ExportController@exportSlsps')->name('export.slsp');
Route::get('/export/blade/slsp', 'App\Http\Controllers\Admin\ExportController@slspModal')->name('export.modal.slsp');

Route::post('/export/catalog', 'App\Http\Controllers\Admin\ExportController@exportCatalogs')->name('export.catalog');
Route::get('/export/blade/catalog', 'App\Http\Controllers\Admin\ExportController@catalogModal')->name('export.modal.catalog');

Route::post('/export/function', 'App\Http\Controllers\Admin\ExportController@exportFunctions')->name('export.function');
Route::get('/export/blade/function', 'App\Http\Controllers\Admin\ExportController@functionModal')->name('export.modal.function');

Route::post('/export/collection', 'App\Http\Controllers\Admin\ExportController@exportCollections')->name('export.collection');
Route::get('/export/blade/collection', 'App\Http\Controllers\Admin\ExportController@collectionModal')->name('export.modal.collection');

Route::post('/export/personfunction', 'App\Http\Controllers\Admin\ExportController@exportPersonFunctions')->name('export.personFunction');
Route::get('/export/blade/personfunction', 'App\Http\Controllers\Admin\ExportController@personFunctionModal')->name('export.modal.personFunction');

Route::post('/export/contact', 'App\Http\Controllers\Admin\ExportController@exportContacts')->name('export.contact');
Route::get('/export/blade/contact', 'App\Http\Controllers\Admin\ExportController@contactModal')->name('export.modal.contact');