<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TableController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

Route::get('/', function () {
    return view('auth.login');
});

Route::post('/create_table', [TableController::class, 'create'])->name('create_table');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/form_design', [TableController::class, 'index'])->name('home');

Route::get('/view_table_col/{tableName}', [TableController::class, 'showColumns'])->name('columns.show');

Route::get('add_form_table/{table}', [FormController::class, 'showTable'])->name('form.create');

Route::post('/save',  [FormController::class, 'saveForm'])->name('save');

Route::delete('/table/{tableName}/row/{id}', [TableController::class, 'deleteRow'])->name('delete_row');

Route::get('/table/{tableName}/{id}/edit', [TableController::class, 'edit'])->name('table.edit');

Route::put('/table/{tableName}/{id}', [TableController::class, 'update'])->name('table.update');

Route::post('/delete-input-field', [TableController::class, 'delete'])->name('delete_input_field');

Route::delete('/delete_table/{tableName}', [TableController::class, 'tabledelete'])->name('delete_table');

Route::get('/columns/update', [TableController::class, 'showUpdateForm'])->name('columns.updateform');

Route::post('/columns/update', [TableController::class, 'updatecreate'])->name('columns.update');

Route::post('/toggle-language/{locale}', [LanguageController::class, 'toggle'])->name('language.toggle');

// Route::get('/dashboard', function () { })->middleware('SetLanguage');

Route::get('/table/{tableName}/download', [TableController::class, 'downloadExcel'])->name('table.download');


