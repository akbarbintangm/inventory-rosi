<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Dashboards\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\NotaTerimaController;
use App\Http\Controllers\Order\DueOrderController;
use App\Http\Controllers\Order\OrderCompleteController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Order\OrderPendingController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\ProductExportController;
use App\Http\Controllers\Product\ProductImportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Purchase\PurchaseController;
use App\Http\Controllers\Quotation\QuotationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BahanBakuController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return redirect('/login');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('dashboard/', [DashboardController::class, 'index'])->name('dashboard');

    // User management is restricted to the owner, as specified in the thesis.
    Route::resource('/users', UserController::class)->middleware('permission:user.manage');
    Route::put('/user/change-password/{username}', [UserController::class, 'updatePassword'])
        ->middleware('permission:user.manage')
        ->name('users.updatePassword');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::get('/profile/store-settings', [ProfileController::class, 'store_settings'])->name('profile.store.settings');
    Route::post('/profile/store-settings', [ProfileController::class, 'store_settings_store'])->name('profile.store.settings.store');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('/quotations', QuotationController::class)
        ->only(['create', 'store'])
        ->middleware('permission:quotation.create');
    Route::resource('/quotations', QuotationController::class)
        ->only(['index', 'show'])
        ->middleware('permission:quotation.view');
    Route::resource('/quotations', QuotationController::class)
        ->only('destroy')
        ->middleware('permission:quotation.delete');

    Route::resource('/customers', CustomerController::class)
        ->only(['create', 'store'])
        ->middleware('permission:customer.create');
    Route::resource('/customers', CustomerController::class)
        ->only(['index', 'show'])
        ->middleware('permission:customer.view');
    Route::resource('/customers', CustomerController::class)
        ->only(['edit', 'update'])
        ->middleware('permission:customer.edit');
    Route::resource('/customers', CustomerController::class)
        ->only('destroy')
        ->middleware('permission:customer.delete');

    Route::resource('/suppliers', SupplierController::class)
        ->only(['create', 'store'])
        ->middleware('permission:supplier.create');
    Route::resource('/suppliers', SupplierController::class)
        ->only(['index', 'show'])
        ->middleware('permission:supplier.view');
    Route::resource('/suppliers', SupplierController::class)
        ->only(['edit', 'update'])
        ->middleware('permission:supplier.edit');
    Route::resource('/suppliers', SupplierController::class)
        ->only('destroy')
        ->middleware('permission:supplier.delete');

    Route::resource('/categories', CategoryController::class)
        ->only(['create', 'store'])
        ->middleware('permission:category.create');
    Route::resource('/categories', CategoryController::class)
        ->only(['index', 'show'])
        ->middleware('permission:category.view');
    Route::resource('/categories', CategoryController::class)
        ->only(['edit', 'update'])
        ->middleware('permission:category.edit');
    Route::resource('/categories', CategoryController::class)
        ->only('destroy')
        ->middleware('permission:category.delete');

    Route::resource('/units', UnitController::class)
        ->only(['create', 'store'])
        ->middleware('permission:unit.create');
    Route::resource('/units', UnitController::class)
        ->only(['index', 'show'])
        ->middleware('permission:unit.view');
    Route::resource('/units', UnitController::class)
        ->only(['edit', 'update'])
        ->middleware('permission:unit.edit');
    Route::resource('/units', UnitController::class)
        ->only('destroy')
        ->middleware('permission:unit.delete');

    // Route Products
    Route::get('products/import/', [ProductImportController::class, 'create'])
        ->middleware('permission:product.import')->name('products.import.view');
    Route::post('products/import/', [ProductImportController::class, 'store'])
        ->middleware('permission:product.import')->name('products.import.store');
    Route::get('products/export/', [ProductExportController::class, 'create'])
        ->middleware('permission:product.export')->name('products.export.store');
    Route::resource('/products', ProductController::class)
        ->only(['create', 'store'])->middleware('permission:product.create');
    Route::resource('/products', ProductController::class)
        ->only(['index', 'show'])->middleware('permission:product.view');
    Route::resource('/products', ProductController::class)
        ->only(['edit', 'update'])->middleware('permission:product.edit');
    Route::resource('/products', ProductController::class)
        ->only('destroy')->middleware('permission:product.delete');

    // bahan baku
    //Route::get(url: '/bahanbaku'. action \App\Livewire\bahanbaku\showbahan::class)->name(name: 'bahanbaku.showbahan');
    Route::resource('/bahanbakus', BahanBakuController::class)
        ->only(['create', 'store'])->middleware('permission:raw_material.create');
    Route::resource('/bahanbakus', BahanBakuController::class)
        ->only(['index', 'show'])->middleware('permission:raw_material.view');
    Route::resource('/bahanbakus', BahanBakuController::class)
        ->only(['edit', 'update'])->middleware('permission:raw_material.edit');
    Route::resource('/bahanbakus', BahanBakuController::class)
        ->only('destroy')->middleware('permission:raw_material.delete');

    // Route Nota Terima
    Route::get('/notaterima', [NotaTerimaController::class, 'index'])
        ->middleware('permission:nota_receipt.view')->name('notaterima.index');
    Route::post('/notaterima/print', [NotaTerimaController::class, 'printNota'])
        ->middleware('permission:nota_receipt.print')->name('notaterima.print');

    // Route Laporan
    Route::get('/laporan', [ReportController::class, 'index'])
        ->middleware('permission:report.view')->name('reports.index');
    Route::post('/laporan/print', [ReportController::class, 'printReport'])
        ->middleware('permission:report.print')->name('reports.print');
    Route::post('/laporan/export', [ReportController::class, 'export'])
        ->middleware('permission:report.export')->name('reports.export');

    
    // Route POS
    Route::get('/pos', [PosController::class, 'index'])->middleware('permission:order.create')->name('pos.index');
    Route::post('/pos/cart/add', [PosController::class, 'addCartItem'])->middleware('permission:order.create')->name('pos.addCartItem');
    Route::post('/pos/cart/update/{rowId}', [PosController::class, 'updateCartItem'])->middleware('permission:order.create')->name('pos.updateCartItem');
    Route::delete('/pos/cart/delete/{rowId}', [PosController::class, 'deleteCartItem'])->middleware('permission:order.create')->name('pos.deleteCartItem');

    Route::post('/pos/invoice', [PosController::class, 'createInvoice'])->middleware('permission:order.create')->name('pos.createInvoice');
    Route::post('invoice/create/', [InvoiceController::class, 'create'])->middleware('permission:order.create')->name('invoice.create');

    // Route Orders
    Route::get('/orders', [OrderController::class, 'index'])->middleware('permission:order.view')->name('orders.index');
    Route::get('/orders/pending', OrderPendingController::class)->middleware('permission:order.view')->name('orders.pending');
    Route::get('/orders/complete', OrderCompleteController::class)->middleware('permission:order.view')->name('orders.complete');

    Route::get('/orders/create', [OrderController::class, 'create'])->middleware('permission:order.create')->name('orders.create');
    Route::post('/orders/store', [OrderController::class, 'store'])->middleware('permission:order.create')->name('orders.store');

    // SHOW ORDER
    Route::get('/orders/{order}', [OrderController::class, 'show'])->middleware('permission:order.view')->name('orders.show');
    Route::put('/orders/update/{order}', [OrderController::class, 'update'])->middleware('permission:order.update')->name('orders.update');
    Route::delete('/orders/cancel/{order}', [OrderController::class, 'cancel'])->middleware('permission:order.cancel')->name('orders.cancel');

    // DUES
    Route::get('due/orders/', [DueOrderController::class, 'index'])->middleware('permission:order.view')->name('due.index');
    Route::get('due/order/view/{order}', [DueOrderController::class, 'show'])->middleware('permission:order.view')->name('due.show');
    Route::get('due/order/edit/{order}', [DueOrderController::class, 'edit'])->middleware('permission:order.update')->name('due.edit');
    Route::put('due/order/update/{order}', [DueOrderController::class, 'update'])->middleware('permission:order.update')->name('due.update');

    // TODO: Remove from OrderController
    Route::get('/orders/details/{order_id}/download', [OrderController::class, 'downloadInvoice'])
        ->middleware('permission:order.print')->name('order.downloadInvoice');


    // Route Purchases
    Route::get('/purchases/approved', [PurchaseController::class, 'approvedPurchases'])->middleware('permission:purchase.view')->name('purchases.approvedPurchases');
    Route::get('/purchases/report', [PurchaseController::class, 'purchaseReport'])->middleware('permission:purchase.report')->name('purchases.purchaseReport');
    Route::get('/purchases/report/export', [PurchaseController::class, 'getPurchaseReport'])->middleware('permission:purchase.report')->name('purchases.getPurchaseReport');
    Route::post('/purchases/report/export', [PurchaseController::class, 'exportPurchaseReport'])->middleware('permission:purchase.report')->name('purchases.exportPurchaseReport');

    Route::get('/purchases', [PurchaseController::class, 'index'])->middleware('permission:purchase.view')->name('purchases.index');
    Route::get('/purchases/create', [PurchaseController::class, 'create'])->middleware('permission:purchase.create')->name('purchases.create');
    Route::post('/purchases', [PurchaseController::class, 'store'])->middleware('permission:purchase.create')->name('purchases.store');

    //Route::get('/purchases/show/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');
    Route::get('/purchases/{purchase}', [PurchaseController::class, 'show'])->middleware('permission:purchase.view')->name('purchases.show');

    //Route::get('/purchases/edit/{purchase}', [PurchaseController::class, 'edit'])->name('purchases.edit');
    Route::get('/purchases/{purchase}/edit', [PurchaseController::class, 'edit'])->middleware('permission:purchase.approve')->name('purchases.edit');
    Route::post('/purchases/update/{purchase}', [PurchaseController::class, 'update'])->middleware('permission:purchase.approve')->name('purchases.update');
    Route::delete('/purchases/delete/{purchase}', [PurchaseController::class, 'destroy'])->middleware('permission:purchase.delete')->name('purchases.delete');

    // Route Quotations
    // Route::get('/quotations/{quotation}/edit', [QuotationController::class, 'edit'])->name('quotations.edit');
    Route::post('/quotations/complete/{quotation}', [QuotationController::class, 'update'])->middleware('permission:quotation.complete')->name('quotations.update');
    Route::delete('/quotations/delete/{quotation}', [QuotationController::class, 'destroy'])->middleware('permission:quotation.delete')->name('quotations.delete');
});

require __DIR__.'/auth.php';
