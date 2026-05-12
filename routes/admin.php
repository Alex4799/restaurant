<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseItemController;
use App\Http\Controllers\ReduceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\StoreItemController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\TransferItemController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {

    Route::get('list',[AdminController::class,'list'])->name('admin#list');
    Route::post('create',[AdminController::class,'create'])->name('admin#create');
    Route::put('update',[AdminController::class,'update'])->name('admin#update');
    Route::delete('delete',[AdminController::class,'delete'])->name('admin#delete')->middleware('verifyAdmin');

    Route::prefix('report')->group(function () {
        Route::get('summary',[ReportController::class,'summary'])->name('admin#reportSummary');
        Route::get('product',[ReportController::class,'product'])->name('admin#reportProduct');
    });

    Route::prefix('profile')->group(function () {
        Route::get('/',[AuthController::class,'profile'])->name('admin#profile');
        Route::post('update',[AuthController::class,'profileUpdate'])->name('admin#profileUpdate');
        Route::post('change/password',[AuthController::class,'changePassword'])->name('admin#changePassword');
    });

    Route::prefix('category')->group(function () {
        Route::get('/',[CategoryController::class,'list'])->name('admin#categoryList');
        Route::post('create',[CategoryController::class,'create'])->name('admin#categoryCreate');
        Route::put('update',[CategoryController::class,'update'])->name('admin#categoryUpdate');
        Route::delete('delete',[CategoryController::class,'delete'])->name('admin#categoryDelete')->middleware('verifyAdmin');
    });

    Route::prefix('product')->group(function () {
        Route::get('/',[ProductController::class,'list'])->name('admin#productList');
        Route::post('create',[ProductController::class,'create'])->name('admin#productCreate');
        Route::get('view/{id}',[ProductController::class,'view'])->name('admin#productView');
        Route::put('update',[ProductController::class,'update'])->name('admin#productUpdate');
        Route::delete('delete',[ProductController::class,'delete'])->name('admin#productDelete')->middleware('verifyAdmin');
    });

    Route::prefix('shop')->group(function () {
        Route::get('/',[ShopController::class,'list'])->name('admin#shopList');
        Route::post('create',[ShopController::class,'create'])->name('admin#shopCreate');
        Route::get('view/{id}',[ShopController::class,'view'])->name('admin#shopView');
        Route::put('update',[ShopController::class,'update'])->name('admin#shopUpdate');
        Route::delete('delete',[ShopController::class,'delete'])->name('admin#shopDelete')->middleware('verifyAdmin');
    });

    Route::prefix('table')->group(function () {
        Route::post('create',[TableController::class,'create'])->name('admin#tableCreate');
        Route::put('update',[TableController::class,'update'])->name('admin#tableUpdate');
        Route::delete('delete',[TableController::class,'delete'])->name('admin#tableDelete')->middleware('verifyAdmin');
    });

    Route::prefix('store')->group(function () {
        Route::post('create',[StoreController::class,'create'])->name('admin#storeCreate');
        Route::get('view/{id}',[StoreController::class,'view'])->name('admin#storeView');
        Route::put('update',[StoreController::class,'update'])->name('admin#storeUpdate');
        Route::delete('delete',[StoreController::class,'delete'])->name('admin#storeDelete')->middleware('verifyAdmin');

        Route::prefix('item')->group(function () {
            Route::post('create',[StoreItemController::class,'create'])->name('admin#storeItemCreate');
            Route::put('update',[StoreItemController::class,'update'])->name('admin#storeItemUpdate');
            Route::delete('delete',[StoreItemController::class,'delete'])->name('admin#storeItemDelete')->middleware('verifyAdmin');
            Route::post('generate/barcode',[StoreItemController::class,'generateBarCode'])->name('admin#storeItemBarcodeGenerate');
        });
    });

    Route::prefix('currency')->group(function () {
        Route::get('/',[CurrencyController::class,'list'])->name('admin#currencyList');
        Route::post('create',[CurrencyController::class,'create'])->name('admin#currencyCreate');
        Route::put('update',[CurrencyController::class,'update'])->name('admin#currencyUpdate');
        Route::delete('delete',[CurrencyController::class,'delete'])->name('admin#currencyDelete')->middleware('verifyAdmin');
    });

    Route::prefix('payment')->group(function () {
        Route::get('/',[PaymentController::class,'list'])->name('admin#paymentList');
        Route::post('create',[PaymentController::class,'create'])->name('admin#paymentCreate');
        Route::put('update',[PaymentController::class,'update'])->name('admin#paymentUpdate');
        Route::delete('delete',[PaymentController::class,'delete'])->name('admin#paymentDelete')->middleware('verifyAdmin');
    });

    Route::prefix('promotion')->group(function () {
        Route::get('/',[PromotionController::class,'list'])->name('admin#promotionList');
        Route::post('create',[PromotionController::class,'create'])->name('admin#promotionCreate');
        Route::get('view/{id}',[PromotionController::class,'view'])->name('admin#promotionView');
        Route::put('update',[PromotionController::class,'update'])->name('admin#promotionUpdate');
        Route::delete('delete',[PromotionController::class,'delete'])->name('admin#promotionDelete')->middleware('verifyAdmin');
        Route::get('check',[PromotionController::class,'check'])->name('admin#promotionCheck');
    });

    Route::prefix('tax')->group(function () {
        Route::get('/',[TaxController::class,'list'])->name('admin#taxList');
        Route::post('create',[TaxController::class,'create'])->name('admin#taxCreate');
        Route::put('update',[TaxController::class,'update'])->name('admin#taxUpdate');
        Route::delete('delete',[TaxController::class,'delete'])->name('admin#taxDelete')->middleware('verifyAdmin');
        Route::get('check',[TaxController::class,'check'])->name('admin#texCheck');
    });

    Route::prefix('delivery')->group(function () {
        Route::get('/',[DeliveryController::class,'list'])->name('admin#deliveryList');
        Route::post('create',[DeliveryController::class,'create'])->name('admin#deliveryCreate');
        Route::put('update',[DeliveryController::class,'update'])->name('admin#deliveryUpdate');
        Route::delete('delete',[DeliveryController::class,'delete'])->name('admin#deliveryDelete')->middleware('verifyAdmin');
        Route::get('changeStatus/{status}',[DeliveryController::class,'changeStatus'])->name('admin#deliveryStatusChange');
    });

    Route::prefix('supplier')->group(function () {
        Route::get('/',[SupplierController::class,'list'])->name('admin#supplierList');
        Route::post('create',[SupplierController::class,'create'])->name('admin#supplierCreate');
        Route::put('update',[SupplierController::class,'update'])->name('admin#supplierUpdate');
        Route::delete('delete',[SupplierController::class,'delete'])->name('admin#supplierDelete')->middleware('verifyAdmin');
    });

    Route::prefix('purchase')->group(function () {
        Route::get('/',[PurchaseController::class,'list'])->name('admin#purchaseList');
        Route::post('create',[PurchaseController::class,'create'])->name('admin#purchaseCreate');
        Route::get('view/{id}',[PurchaseController::class,'view'])->name('admin#purchaseView');
        Route::put('update',[PurchaseController::class,'update'])->name('admin#purchaseUpdate');
        Route::delete('delete',[PurchaseController::class,'delete'])->name('admin#purchaseDelete')->middleware('verifyAdmin');

        Route::prefix('item')->group(function () {
            Route::post('create',[PurchaseItemController::class,'create'])->name('admin#purchaseItemCreate');
            Route::put('update',[PurchaseItemController::class,'update'])->name('admin#purchaseItemUpdate');
            Route::delete('delete',[PurchaseItemController::class,'delete'])->name('admin#purchaseItemDelete')->middleware('verifyAdmin');
        });
    });

    Route::prefix('transfer')->group(function () {
        Route::get('/',[TransferController::class,'list'])->name('admin#transferList');
        Route::post('create',[TransferController::class,'create'])->name('admin#transferCreate');
        Route::get('view/{id}',[TransferController::class,'view'])->name('admin#transferView');
        Route::put('update',[TransferController::class,'update'])->name('admin#transferUpdate');
        Route::delete('delete',[TransferController::class,'delete'])->name('admin#transferDelete')->middleware('verifyAdmin');

        Route::prefix('item')->group(function () {
            Route::post('create',[TransferItemController::class,'create'])->name('admin#transferItemCreate');
            Route::put('update',[TransferItemController::class,'update'])->name('admin#transferItemUpdate');
            Route::delete('delete',[TransferItemController::class,'delete'])->name('admin#transferItemDelete')->middleware('verifyAdmin');
        });
    });

    Route::prefix('reduce')->group(function () {
        Route::get('/',[ReduceController::class,'list'])->name('admin#reduceList');
        Route::post('create',[ReduceController::class,'create'])->name('admin#reduceCreate');
        Route::put('update',[ReduceController::class,'update'])->name('admin#reduceUpdate');
        Route::delete('delete',[ReduceController::class,'delete'])->name('admin#reduceDelete')->middleware('verifyAdmin');
    });

    Route::prefix('seller')->group(function () {
        Route::prefix('cart')->group(function () {
            Route::get('list',[CartController::class,'cartList'])->name('admin#cartList');
            Route::get('add',[CartController::class,'addCart'])->name('admin#productAddCart');
            Route::post('add/barocde',[CartController::class,'addCartBarcode'])->name('admin#addCartBarcode');
            Route::get('change/qty',[CartController::class,'cartChangeQty'])->name('admin#changeQty');
            Route::get('remove',[CartController::class,'removeCart'])->name('admin#productRemoveCart');
            Route::get('remvove/all/item',[CartController::class,'removeCartAll'])->name('admin#productRemoveCartAll');
            Route::put('update/note',[CartController::class,'updateNote'])->name('admin#cartUpdateNote');
            Route::get('get',[CartController::class,'getCartData'])->name('admin#getCartData');
            Route::get('get/store/item',[CartController::class,'getStoreItem'])->name('admin#getStoreItemCart');
            Route::post('create',[CartController::class,'createCart'])->name('admin#createCart');
            Route::get('checkOut',[CartController::class,'checkOut'])->name('admin#checkOut');
        });

        Route::prefix('order')->group(function () {
            Route::post('/',[OrderController::class,'order'])->name('admin#order');
            Route::get('list',[OrderController::class,'list'])->name('admin#orderList');
            Route::get('summary/{id}',[OrderController::class,'orderSummary'])->name('admin#orderSummary');
            Route::get('generate/invoice/{id}',[OrderController::class,'generateInvoice'])->name('admin#orderGenerateInvoice');
            Route::post('finish',[OrderController::class,'orderFinish'])->name('admin#orderFinish');
        });
    });

    Route::prefix('waiter')->group(function () {
        Route::get('table',[SellController::class,'table_waiter'])->name('admin#tableListWaiter');
        Route::get('table/view/{id}',[SellController::class,'tableView_waiter'])->name('admin#tableViewWaiter');
        Route::post('table/finish/order',[SellController::class,'tableFinish_waiter'])->name('admin#tableFinishOrder');
        Route::get('get/data',[SellController::class,'getData'])->name('admin#getWaiterData');
        Route::get('change/receive/item/{id}',[SellController::class,'changeReceive'])->name('admin#changeReceiveItem');
    });

    Route::prefix('kitchen')->group(function () {
        Route::get('list',[KitchenController::class,'list'])->name('admin#kitchenList');
        Route::get('waiter',[KitchenController::class,'lit_waiter'])->name('admin#kitchenListWaiter');
        Route::get('change/status/{id}/{status}',[KitchenController::class,'changeStatus'])->name('admin#changeStatus');
        Route::get('get/data',[KitchenController::class,'getData'])->name('admin#getKitchenData');
    });
});
