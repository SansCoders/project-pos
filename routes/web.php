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
    Route::get('/admin/users-cashier/{id}/edit', 'AdminController@editUserCashier')->name('admin.users-cashier.edit');
    Route::put('/admin/users-sales/process', 'AdminController@updateDataUser')->name('admin.users-sales.edit-put');
    Route::put('/admin/users-cashier/process', 'AdminController@updateDataCashier')->name('admin.users-cashier.edit-put');
    Route::put('/admin/user-changepassword', 'AdminController@changePass')->name('admin.changepass');

    Route::get('/admin/users-cashier', 'AdminController@UsersCashier')->name('admin.users-cashier');
    Route::post('/admin/users-cashier', 'AdminController@storeUserCashier')->name('admin.users-cashier.store');
    Route::post('/admin/change-status', 'AdminController@changeStatusUser')->name('admin.changestatususer');
    Route::post('/admin/change', 'AdminController@changeStatus')->name('admin.changestatus');

    Route::get('/admin/products', 'ProductController@getAllProducts')->name('admin.products');
    Route::post('/admin/products', 'ProductController@storeProduct')->name('admin.products.store');

    Route::get('/admin/units', 'ProductController@getAllUnits')->name('admin.common_units');
    Route::post('/admin/units', 'UnitsController@storeUnit')->name('admin.unit.store');
    Route::put('/admin/units/{id}', 'UnitsController@updateUnit')->name('admin.units.update');

    Route::get('/admin/categorys', 'CategoryProductController@getAllCategory')->name('admin.categorys');
    Route::post('/admin/categorys', 'CategoryProductController@storeCategory')->name('admin.categorys.store');
    Route::put('/admin/categorys/{id}', 'CategoryProductController@updateCategory')->name('admin.categorys.update');

    Route::get('/admin/stock-activity', 'AdminController@indexStockActivities')->name('admin.stock-activity');

    Route::get('/admin/settings', 'AdminController@settingsPage')->name('admin.settings');
    Route::post('/admin/settings', 'AdminController@updateInfoPage')->name('admin.update-settings');
    Route::post('/admin/settings/cp', 'AdminController@updatePassword')->name('admin.update-settings.cp');
});

Route::group(['middleware' => ['auth:cashier']], function () {
    Route::get('/cashier', 'CashierController@index')->name('cashier.home');
    Route::get('/cashier/products', 'ProductController@getAllProducts')->name('cashier.products');
    Route::post('/cashier/products/cari', 'ProductController@searchProducts')->name('cashier.products.search');
    Route::post('/cashier/products', 'ProductController@storeProduct')->name('cashier.products.store');
    Route::put('/cashier/products/{id}', 'ProductController@destroyTemp')->name('cashier.products.destroyTemp');
    Route::get('/cashier/product_info/{id}', 'ProductController@getInfoProduct');
    Route::post('/cashier/product_info/', 'ProductController@updateProduct')->name('cashier.products.update');

    Route::get('/cashier/transaction', 'CashierController@transactionProduct')->name('cashier.transaction');
    Route::get('/cashier/transaction/new', 'CashierController@newTransaction')->name('cashier.newtransaction');
    Route::get('/cashier/transaction/checkout', 'CashierController@cashierCheckout')->name('cashier.myCheckout');
    Route::delete('/cashier/transaction/checkout/{id}', 'ProductController@destroyItemFromCheckout')->name('cashier.checkout.destroy');
    Route::get('/cashier/product/{slug}', 'ProductController@detailsProduct')->name('cashier.details.product');
    Route::post('/cashier/editcartqty', 'ProductController@editQtyCart')->name('cashier.editQtyCart');
    Route::post('/cashier/editcartprice', 'ProductController@editPriceCart')->name('cashier.editPriceCart');
    Route::put('/cashier/editcartprice/{id}', 'ProductController@editPriceCart_put')->name('cashier.editPriceCart.put');
    Route::post('/cashier/editreceiptprice', 'ProductController@editPriceReceipt')->name('cashier.editPriceReceipt');
    Route::put('/cashier/editreceiptprice/{id}', 'ProductController@editPriceReceipt_put')->name('cashier.editPriceReceipt.put');
    // Route::get('/cashier/transaction/registred-user', 'CashierController@newTransactionUserRegisterd')->name('cashier.transaction.registereduser');
    Route::get('/cashier/transaction/search-product', 'CashierController@SearchinnewTransaction')->name('cashier.newtransaction.search');

    Route::post('/cashier/clt', 'CashierController@getdatalistCartContent')->name('cashier.listCart');
    // Route::post('/cashier/sendtocart', 'CashierController@sendDataSeeProduct')->name('cashier.sendtocart');
    Route::post('/cashier/sendtocart', 'ProductController@addToCart')->name('cashier.sendtocart');
    Route::post('/cashier/seeproduct', 'CashierController@getdataSeeProduct')->name('cashier.SeeProduct');
    Route::get('/cashier/t/{orderid}', 'CashierController@processCheckout')->name('cashier.check.checkout');
    Route::post('/cashier/t/{orderid}/confirm', 'CashierController@confirmCheckout')->name('cashier.confirm.checkout');
    Route::put('/cashier/t/{orderid}/cancel', 'CashierController@canceledCheckout')->name('cashier.confirm.checkout-canceled');

    Route::post('/cashier//checkout/process', 'CashierController@confirmCheckOutDirect')->name('cashier.checkout.confirmDirect');
    Route::post('/cashier/t/confirm', 'CashierController@confirmCheckoutviaCashier')->name('cashier.confirm.viacashier');
    Route::post('/cashier/cart/delete-{id}', 'CashierController@deleteItemCart')->name('cashier.cart.deleteItem');

    Route::get('/cashier/reports/transactions', 'CashierController@listTransactions')->name('cashier.listTransactions');
    Route::get('/cashier/reports/transactions/search', function () {
        return abort(404);
    });
    Route::post('/cashier/reports/transactions/search', 'CashierController@searchTransactions')->name('cashier.searchTransactions');

    Route::get('/cashier/add-stock', 'StockController@addStock')->name('stock.add');
    Route::get('/cashier/add-stock/{id}', 'StockController@addStockProduct')->name('stock.add.process');
    Route::put('/cashier/add-stock/{id}', 'StockController@stockIn_store')->name('stock.stockIn.process');

    Route::get('/cashier/custom-prices', 'CashierController@customPrice')->name('cashier.customprice');
    Route::post('/cashier/custom-prices/cari', 'CashierController@customPriceSearch')->name('cashier.customprice.search');
    Route::get('/cashier/custom-prices/{user}', 'CashierController@setCustomPrice')->name('cashier.customprice.set');
    Route::post('/cashier/custom-prices/{user}', 'CashierController@confirmCustomPrice')->name('cashier.customprice.confirm');
    Route::put('/cashier/custom-prices/{user}', 'CashierController@editCustomPrice')->name('cashier.customprice.edit');
    Route::delete('/cashier/custom-prices/{user}', 'CashierController@deleteCustomPrice')->name('cashier.customprice.delete');

    Route::post('/list-orders/details', 'CashierController@getdataReceipts');
    Route::get('/invoice/view-{id}', 'CashierController@previewFaktur')->name('cashier.previewFaktur');
    Route::post('/invoice/download', 'CashierController@cetakFaktur')->name('cashier.downloadFaktur');
});

Route::group(['middleware' => ['auth:web']], function () {
    Route::get('/product/{slug}', 'ProductController@detailsProduct')->name('details.product');
    Route::post('/product/search', 'ProductController@searchProduct')->name('search.product');
    Route::get('/addtocart', function () {
        return redirect()->back();
    });
    Route::post('/addtocart', 'ProductController@addToCart')->name('addtocart');
    Route::post('/editcartqty', 'ProductController@editQtyCart')->name('editQtyCart');
    // Route::put('/editcartqty/{id}', 'ProductController@editQtyCart_put')->name('editQtyCart.put');

    Route::get('/category/{name}', 'HomeController@getProductbyCategorybyName')->name('categ.name');

    Route::get('/checkout', 'ProductController@checkOutProducts')->name('checkout');
    Route::post('/checkout/process', 'ProductController@processCheckOut')->name('checkout.process');
    Route::delete('/checkout/{id}', 'ProductController@destroyItemFromCheckout')->name('checkout.destroy');

    Route::get('/my-orders', 'HomeController@myOrders')->name('my-orders');
    Route::post('/my-orders/details', 'HomeController@getdataReceipts');
    Route::get('/my-orders/search', function () {
        return abort(404);
    });
    Route::post('/my-orders/search', 'HomeController@searchTransactions')->name('searchTransactions');

    Route::get('/my', 'HomeController@myProfile');

    Route::post('/invoice', 'HomeController@cetakFaktur');
    Route::get('/invoice', function () {
        return redirect()->back();
    });
});
Route::group(['middleware' => ['auth:admin,cashier']], function () {
    Route::get('/profile/{userid}', 'ProfileController@detailsUser')->name('user.profile');
});
Route::group(['middleware' => ['auth:admin,cashier,web']], function () {
    Route::put('/editcartqty/{id}', 'ProductController@editQtyCart_put')->name('editQtyCart.put');
});
