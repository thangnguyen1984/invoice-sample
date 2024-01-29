<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('categories', CategoryController::class);
    $router->resource('items', ItemController::class);
    $router->get('invoices/create', 'InvoiceController@create')->name("invoice.create");
    $router->get('invoices/getItemByCategory/{id}', 'InvoiceController@getItemByCategory');
    $router->get('invoices/getItemDetail/{id}', 'InvoiceController@getItemDetail');
    $router->post('invoices/{id}/saveInvoice', 'InvoiceController@saveInvoice');
    $router->get('invoices/{id}/printInvoice', 'InvoiceController@printInvoice');
    $router->resource('invoices', InvoiceController::class);
    $router->get('invoices/{id}/delete', 'InvoiceController@delete')->name("invoices.delete");
    
});
