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
    return view('welcome');
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
    Route::get('/admin/users-sales/{id}/edit', 'AdminController@editUserSales')->name('admin.users-sales.edit');
    Route::put('/admin/users-sales/process', 'AdminController@updateDataUser')->name('admin.users-sales.edit-put');

    Route::get('/admin/users-cashier', 'AdminController@UsersCashier')->name('admin.users-cashier');
    Route::post('/admin/users-cashier', 'AdminController@storeUserCashier')->name('admin.users-cashier.store');

    Route::get('/admin/products', 'ProductController@getAllProducts')->name('admin.products');
    Route::post('/admin/products', 'ProductController@storeProduct')->name('admin.products.store');

    Route::get('/admin/units', 'ProductController@getAllUnits')->name('admin.common_units');
    Route::post('/admin/units', 'UnitsController@storeUnit')->name('admin.unit.store');
    Route::put('/admin/units/{id}', 'UnitsController@updateUnit')->name('admin.units.update');

    Route::get('/admin/categorys', 'CategoryProductController@getAllCategory')->name('admin.categorys');
    Route::post('/admin/categorys', 'CategoryProductController@storeCategory')->name('admin.categorys.store');
    Route::put('/admin/categorys/{id}', 'CategoryProductController@updateCategory')->name('admin.categorys.update');

    Route::get('/admin/settings', 'AdminController@settingsPage')->name('admin.settings');
    Route::post('/admin/settings', 'AdminController@updateInfoPage')->name('admin.update-settings');
});

Route::group(['middleware' => ['auth:cashier']], function () {
    Route::get('/cashier', 'CashierController@index')->name('cashier.home');
    Route::get('/cashier/products', 'ProductController@getAllProducts')->name('cashier.products');
    Route::post('/cashier/products', 'ProductController@storeProduct')->name('cashier.products.store');
    Route::get('/cashier/product_info/{id}', 'ProductController@getInfoProduct');
    Route::post('/cashier/product_info/', 'ProductController@updateProduct')->name('cashier.products.update');

    Route::get('/cashier/transaction', 'CashierController@transactionProduct')->name('cashier.transaction');
    Route::get('/cashier/transaction/new', 'CashierController@newTransaction')->name('cashier.newtransaction');
    Route::post('/cashier/clt', 'CashierController@getdatalistCartContent')->name('cashier.listCart');
    Route::post('/cashier/seeproduct', 'CashierController@getdataSeeProduct')->name('cashier.SeeProduct');
    Route::get('/cashier/t/{orderid}', 'CashierController@processCheckout')->name('cashier.check.checkout');
    Route::post('/cashier/t/{orderid}/confirm', 'CashierController@confirmCheckout')->name('cashier.confirm.checkout');

    Route::post('/cashier/t/confirm', 'CashierController@confirmCheckoutviaCashier')->name('cashier.confirm.viacashier');



    Route::get('/cashier/add-stock', 'StockController@addStock')->name('stock.add');
    Route::get('/cashier/add-stock/{id}', 'StockController@addStockProduct')->name('stock.add.process');
    Route::put('/cashier/add-stock/{id}', 'StockController@stockIn_store')->name('stock.stockIn.process');
});

Route::group(['middleware' => ['auth:web']], function () {
    Route::get('/product/{slug}', 'ProductController@detailsProduct')->name('details.product');
    Route::post('/product/search', 'ProductController@searchProduct')->name('search.product');
    Route::get('/addtocart', function () {
        return redirect()->back();
    });
    Route::post('/addtocart', 'ProductController@addToCart')->name('addtocart');
    Route::post('/editcartqty', 'ProductController@editQtyCart')->name('editQtyCart');
    Route::put('/editcartqty/{id}', 'ProductController@editQtyCart_put')->name('editQtyCart.put');

    Route::get('/category/{name}', 'HomeController@getProductbyCategorybyName')->name('categ.name');

    Route::get('/checkout', 'ProductController@checkOutProducts')->name('checkout');
    Route::post('/checkout/process', 'ProductController@processCheckOut')->name('checkout.process');
    Route::delete('/checkout/{id}', 'ProductController@destroyItemFromCheckout')->name('checkout.destroy');

    Route::get('/my-orders', 'HomeController@myOrders')->name('my-orders');
    Route::post('/my-orders/details', 'HomeController@getdataReceipts');

    Route::get('/my', 'HomeController@myProfile');

    Route::post('/invoice', 'HomeController@cetakFaktur');
    Route::get('/invoice', function () {
        return redirect()->back();
    });
});
Route::group(['middleware' => ['auth:admin,cashier']], function () {
    Route::get('/profile/{userid}', 'ProfileController@detailsUser')->name('user.profile');
});
