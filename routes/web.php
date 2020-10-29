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

Route::get('/', function () {
    return view('auth/login');
});

Auth::routes(['register' => false]);

Route::get('/login/admin', 'Auth\LoginController@showAdminLoginForm');
Route::get('/login/cashier', 'Auth\LoginController@showCashierLoginForm');
Route::get('/home', 'HomeController@index')->name('home');


Route::post('/login/admin', 'Auth\LoginController@adminLogin');
Route::post('/login/cashier', 'Auth\LoginController@cashierLogin');

Route::group(['middleware' => ['auth:admin']], function () {
    Route::get('/admin', 'AdminController@index')->name('admin.home');
    Route::get('/admin/users-sales', 'AdminController@UsersSales')->name('admin.users-sales');
    Route::post('/admin/users-sales', 'AdminController@storeUserSales')->name('admin.users-sales.store');

    Route::get('/admin/users-cashier', 'AdminController@UsersCashier')->name('admin.users-cashier');
    Route::post('/admin/users-cashier', 'AdminController@storeUserCashier')->name('admin.users-cashier.store');

    Route::get('/admin/products', 'ProductController@getAllProducts')->name('admin.products');
    Route::post('/admin/products', 'ProductController@storeProduct')->name('admin.products.store');

    Route::get('/admin/units', 'ProductController@getAllUnits')->name('admin.common_units');
    Route::post('/admin/units', 'UnitsController@storeUnit')->name('admin.unit.store');

    Route::get('/admin/categorys', 'CategoryProductController@getAllCategory')->name('admin.categorys');
    Route::post('/admin/categorys', 'CategoryProductController@storeCategory')->name('admin.categorys.store');
    Route::put('/admin/categorys/{id}', 'CategoryProductController@updateCategory')->name('admin.categorys.update');
});

Route::group(['middleware' => ['auth:cashier']], function () {
    Route::get('/cashier', 'CashierController@index')->name('cashier.home');
    Route::get('/cashier/products', 'ProductController@getAllProducts')->name('cashier.products');
    Route::post('/cashier/products', 'ProductController@storeProduct')->name('cashier.products.store');

    Route::get('/cashier/add-stock', 'StockController@addStock')->name('stock.add');
});
Route::group(['middleware' => ['auth:web']], function () {
    Route::get('/product/{slug}', 'ProductController@detailsProduct')->name('details.product');
});
Route::group(['middleware' => ['auth:admin,cashier']], function () {

    Route::get('/profile/{userid}', 'ProfileController@detailsUser')->name('user.profile');
});
