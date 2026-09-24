<?php

use App\Http\Controllers\Back\AccountsController;
use App\Http\Controllers\Back\AdminController;
use App\Http\Controllers\Back\BlogController;
use App\Http\Controllers\Back\CourierController;
use App\Http\Controllers\Back\CustomerController;
use App\Http\Controllers\Back\FooterWidgetController;
use App\Http\Controllers\Back\FrontendController;
use App\Http\Controllers\Back\LocationController;
use App\Http\Controllers\Back\MenuController;
use App\Http\Controllers\Back\OrderController;
use App\Http\Controllers\Back\OtherPageController;
use App\Http\Controllers\Back\PageController;
use App\Http\Controllers\Back\PaymentMethodController;
use App\Http\Controllers\Back\Product\AttributeController;
use App\Http\Controllers\Back\Product\BrandController;
use App\Http\Controllers\Back\Product\CategoryController;
use App\Http\Controllers\Back\Product\ProductController;
use App\Http\Controllers\Back\Product\StockAdjustmentController;
use App\Http\Controllers\Back\Product\StockController;
use App\Http\Controllers\Back\Product\SupplerController;
use App\Http\Controllers\Back\RecycleBinController;
use App\Http\Controllers\Back\ReportController;
use App\Http\Controllers\Back\RoleController;
use App\Http\Controllers\Back\SliderController;
use App\Http\Controllers\Back\SMSController;
use App\Http\Controllers\Back\TestimonialController;
use App\Http\Controllers\MediaController;
use Illuminate\Support\Facades\Route;

// Auth
// Route::get('login',             [AuthController::class, 'login'])->name('back.login');

Route::middleware('auth', 'isAdmin')->group(function () {
    // Other pages
    Route::get('/', [OtherPageController::class, 'dashboard'])->name('dashboard_d');
    Route::get('dashboard', [OtherPageController::class, 'dashboard'])->name('dashboard');
    Route::post('top-products', [OtherPageController::class, 'topProducts'])->name('topProducts');
    Route::get('carts', [OtherPageController::class, 'carts'])->name('back.carts');
    Route::get('carts/delete/{cart}', [OtherPageController::class, 'cartDelete'])->name('back.cartDelete');
    Route::get('wishlists', [OtherPageController::class, 'wishlists'])->name('back.wishlists');
    Route::get('wishlists/delete/{favorite}', [OtherPageController::class, 'wishlistDelete'])->name('back.wishlistDelete');
    Route::get('product-quotes', [OtherPageController::class, 'productQuotes'])->name('back.productQuotes');
    Route::get('contact-messages', [OtherPageController::class, 'contactMessages'])->name('back.contactMessages');
    Route::get('contact-messages/delete/{contact_message}', [OtherPageController::class, 'contactMessageDelete'])->name('back.contactMessageDelete');
    Route::post('show', [OtherPageController::class, 'show'])->name('back.show');

    // Admin CRUD
    //update admin profile
    Route::get('profile/update-profile', [AdminController::class, 'update_profile_page'])->name('admin.update-profile');
    Route::post('profile/update-profile/action', [AdminController::class, 'update_profile'])->name('back.admins.update.action');
    Route::post('profile/update-password', [AdminController::class, 'update_password'])->name('admin.password-update');
    Route::get('admins/delete/{user}', [AdminController::class, 'removeImage'])->name('back.admins.removeImage');
    Route::resource('admins', AdminController::class, ['as' => 'back'])->except('show');

    // Customer CRUD
    Route::get('customers/select-list', [CustomerController::class, 'selectList'])->name('back.customers.selectList');
    Route::get('customers/pre-destroy/{id}', [CustomerController::class, 'preDestroy'])->name('back.customers.preDestroy');
    Route::get('customers/action/{user}/{action}', [CustomerController::class, 'action'])->name('back.customers.action');
    Route::get('customers/remove-image/{user}', [CustomerController::class, 'removeImage'])->name('back.customers.removeImage');
    Route::post('customers/table', [CustomerController::class, 'table'])->name('back.customers.table');
    Route::resource('customers', CustomerController::class, ['as' => 'back']);

    // Supplier CRUD
    Route::get('suppliers/payable', [SupplerController::class, 'payable'])->name('back.suppliers.payable');
    Route::get('suppliers/payments', [SupplerController::class, 'payments'])->name('back.suppliers.payments');
    Route::get('suppliers/add-payment', [SupplerController::class, 'addPayment'])->name('back.suppliers.addPayment');
    Route::post('suppliers/add-payment', [SupplerController::class, 'addPaymentSubmit']);
    Route::get('suppliers/payment/{id}', [SupplerController::class, 'paymentDetails'])->name('back.suppliers.paymentDetails');
    Route::post('suppliers/get-info', [SupplerController::class, 'getInfo'])->name('back.suppliers.getInfo');
    Route::resource('suppliers', SupplerController::class, ['as' => 'back']);

    // Page CRUD
    Route::get('pages/remove-image/{page}', [PageController::class, 'removeImage'])->name('back.pages.removeImage');
    Route::resource('pages', PageController::class, ['as' => 'back']);

    // Media
    Route::get('media/settings', [MediaController::class, 'settings'])->name('back.media.settings');
    Route::post('media/settings', [MediaController::class, 'settingsUpdate']);
    Route::post('media/upload', [MediaController::class, 'upload'])->name('back.media.upload');
    // Image Upload
    Route::post('media/image-upload',  [MediaController::class, 'imageUpload'])->name('imageUpload');
    Route::post('media/get-gallery', [MediaController::class, 'getGallery'])->name('back.media.getGallery');

    // Reports
    Route::get('report/overview', [ReportController::class, 'overview'])->name('back.report.overview');
    Route::get('report/product', [ReportController::class, 'product'])->name('back.report.product');
    Route::post('report/product', [ReportController::class, 'productTable']);
    Route::get('report/product/{product}', [ReportController::class, 'productDetails'])->name('back.report.productDetails');
    Route::get('report/orders', [ReportController::class, 'orders'])->name('back.report.orders');

    // Frontend
    Route::get('frontend/general', [FrontendController::class, 'general'])->name('back.frontend.general');
    Route::post('frontend/general', [FrontendController::class, 'generalStore']);
    // Slider
    Route::post('sliders/position', [SliderController::class, 'position'])->name('back.sliders.position');
    Route::get('sliders/delete/{slider}', [SliderController::class, 'destroy'])->name('back.sliders.delete');
    Route::resource('sliders', SliderController::class, ['as' => 'back']);
    // Courier
    Route::get('courier/config', [CourierController::class, 'config'])->name('back.courier.config');
    Route::post('courier/config', [CourierController::class, 'update'])->name('back.courier.update');
    // SMS
    Route::get('sms/config', [SMSController::class, 'config'])->name('back.sms.config');
    Route::post('sms/config', [SMSController::class, 'update'])->name('back.sms.updateConfig');

    // Menus
    Route::get('menus', [MenuController::class, 'index'])->name('back.menus.index');
    Route::post('menus/store', [MenuController::class, 'store'])->name('back.menus.store');
    Route::post('menus/store/menu-item', [MenuController::class, 'storeMenuItem'])->name('back.menus.storeMenuItem');
    Route::post('menus/menu-item/position', [MenuController::class, 'menuItemPosition'])->name('back.menus.menuItemPosition');
    Route::get('menus/destroy/{menu}', [MenuController::class, 'destroy'])->name('back.menus.destroy');
    Route::get('menus/item/destroy/{menu_item}', [MenuController::class, 'destroyItem'])->name('back.menus.destroyItem');
    Route::post('menus/item/edit-ajax', [MenuController::class, 'editItemAjax'])->name('back.menus.editItemAjax');
    Route::post('menus/item/update', [MenuController::class, 'updateItem'])->name('back.menus.updateItem');
    Route::get('menus/category', [MenuController::class, 'category'])->name('back.menus.category');

    // Products
    Route::prefix('product')->group(function () {
        // Category CRUD
        Route::get('categories/delete/{category}', [CategoryController::class, 'delete'])->name('back.categories.delete');
        Route::get('categories/get-sub-options', [CategoryController::class, 'getSubOptions'])->name('back.categories.getSubOptions');
        Route::post('categories/change-parent-category', [CategoryController::class, 'changeParentCategory'])->name('back.categories.changeParentCategory');
        Route::post('categories/change-product-category', [CategoryController::class, 'changeProductCategory'])->name('back.categories.changeProductCategory');
        Route::get('categories/remove-product/{product}/{category}', [CategoryController::class, 'removeProduct'])->name('back.categories.removeProduct');
        Route::get('categories/remove-image/{category}', [CategoryController::class, 'removeImage'])->name('back.categories.removeImage');
        Route::resource('categories', CategoryController::class, ['as' => 'back']);

        // Brand CRUD
        Route::get('brands/remove-image/{brand}', [BrandController::class, 'removeImage'])->name('back.brands.removeImage');
        Route::resource('brands', BrandController::class, ['as' => 'back']);

        // Product CRUD
        Route::post('products/attribute/apply', [ProductController::class, 'attributeApply'])->name('back.products.attributeApply');
        Route::post('products/table', [ProductController::class, 'table'])->name('back.products.table');
        Route::get('products/select-list', [ProductController::class, 'selectList'])->name('back.products.selectList');
        Route::post('products/product-data-json', [ProductController::class, 'productDataJson'])->name('back.products.productDataJson');
        Route::get('products/reviews', [ProductController::class, 'reviews'])->name('back.products.reviews');
        Route::get('products/reviews-action/{review}/{action}', [ProductController::class, 'reviewAction'])->name('back.products.reviewAction');
        Route::get('products/reviews-action/{review}', [ProductController::class, 'reviewDelete'])->name('back.products.reviewDelete');
        Route::post('products/change-featured', [ProductController::class, 'changeFeatured'])->name('back.products.changeFeatured');
        Route::post('products/get-bundle-item', [ProductController::class, 'getBundleItem'])->name('back.products.getBundleItem');
        Route::resource('products', ProductController::class, ['as' => 'back']);
        Route::post('products/duplicate', [ProductController::class, 'duplicate_product'])->name('back.products.duplicate_product');

        // Attribute CRUD
        Route::post('attributes/items/store', [AttributeController::class, 'itemStore'])->name('back.attributes.itemStore');
        Route::get('attributes/items/destroy/{id}', [AttributeController::class, 'itemDestroy'])->name('back.attributes.itemDestroy');
        // Route::get('attributes/items/edit/{id}', [AttributeController::class, 'itemEdit'])->name('back.attributes.itemEdit');
        Route::post('attributes/items/update', [AttributeController::class, 'itemUpdate'])->name('back.attributes.itemUpdate');
        Route::post('attributes/update', [AttributeController::class, 'updateModal'])->name('back.attributes.updateModal');
        Route::post('attributes/update-ajax', [AttributeController::class, 'updateAjax'])->name('back.attributes.updateAjax');
        Route::post('attributes/update-item-ajax', [AttributeController::class, 'updateItemAjax'])->name('back.attributes.updateItemAjax');
        Route::resource('attributes', AttributeController::class, ['as' => 'back'])->except('create');

        // Stocks
        Route::post('stocks/product-table', [StockController::class, 'productTable'])->name('back.stocks.productTable');
        Route::post('stocks/add-item', [StockController::class, 'addItem'])->name('back.stocks.addItem');
        Route::get('stocks/pre-alert', [StockController::class, 'preAlert'])->name('back.stocks.preAlert');
        Route::get('stocks/out', [StockController::class, 'out'])->name('back.stocks.out');
        Route::get('stocks/alert', [StockController::class, 'alert'])->name('back.stocks.alert');
        Route::get('stocks/history', [StockController::class, 'history'])->name('back.stoct.history');
        Route::post('stocks/history', [StockController::class, 'historyTable']);
        Route::resource('stocks', StockController::class, ['as' => 'back']);

        // Purchases
        Route::get('purchases', [StockAdjustmentController::class, 'index'])->name('back.adjustments.index');
        Route::get('purchases/create', [StockAdjustmentController::class, 'create'])->name('back.adjustments.create');
        Route::post('purchases/store', [StockAdjustmentController::class, 'store'])->name('back.adjustments.store');
        Route::post('purchases/add-item', [StockAdjustmentController::class, 'addItem'])->name('back.adjustments.addItem');
        Route::post('purchases/get-cost', [StockAdjustmentController::class, 'getCost'])->name('back.adjustments.getCost');
        // Route::get('purchases/edit/{adjustment}', [StockAdjustmentController::class, 'edit'])->name('back.adjustments.edit');
        Route::delete('purchases/delete', [StockAdjustmentController::class, 'delete'])->name('back.adjustments.delete');
        Route::get('purchases/show/{id}', [StockAdjustmentController::class, 'show'])->name('back.adjustments.show');
    });

    // Orders
    Route::post('orders/add-item', [OrderController::class, 'addItem'])->name('back.orders.addItem');
    Route::post('orders/table', [OrderController::class, 'table'])->name('back.orders.table');
    Route::get('orders/refund/{id}', [OrderController::class, 'refund'])->name('back.orders.refund');
    Route::get('orders/return-refund/{order}', [OrderController::class, 'returnRefund'])->name('back.orders.returnRefund');
    Route::post('orders/return-refund/{order}', [OrderController::class, 'returnRefundSubmit']);
    Route::get('orders/e-shipper-label/{order}', [OrderController::class, 'eShipperLabel'])->name('back.orders.eShipperLabel');
    Route::get('orders/select-courier/{order}', [OrderController::class, 'selectCourier'])->name('back.orders.selectCourier');
    Route::post('orders/select-courier/{order}', [OrderController::class, 'selectCourierSubmit']);
    Route::post('orders/customer-details', [OrderController::class, 'customerDetails'])->name('back.orders.customerDetails');
    Route::post('orders/add-product/{order}', [OrderController::class, 'addProduct'])->name('back.orders.addProduct');
    Route::post('orders/add-quantity', [OrderController::class, 'addQuantity'])->name('back.orders.addQuantity');
    Route::post('orders/return-quantity', [OrderController::class, 'returnQuantity'])->name('back.orders.returnQuantity');
    Route::post('orders/print-list', [OrderController::class, 'printList'])->name('back.orders.printList');
    Route::get('orders/failed', [OrderController::class, 'failed'])->name('back.orders.failed');
    Route::post('orders/failed/delete-multiple', [OrderController::class, 'failedDeleteMultiple'])->name('back.orders.failedDeleteMultiple');

    // Pathao
    Route::post('orders/get-pathao-info', [CourierController::class, 'getPathaoInfo'])->name('orders.getPathaoInfo');
    Route::post('orders/send-pathao-order/{id}', [CourierController::class, 'sendPathaoOrder'])->name('orders.sendPathaoOrder');

    // REDX
    Route::post('orders/get-redx-info', [CourierController::class, 'getRedexInfo'])->name('orders.getRedexInfo');
    Route::post('orders/send-redx-order/{id}', [CourierController::class, 'sendRedexOrder'])->name('orders.sendRedexOrder');

    // Steadfast
    Route::post('orders/send-steadfast-order/{id}', [CourierController::class, 'sendSteadfastOrder'])->name('orders.sendSteadfastOrder');

    // eCourier
    Route::post('orders/get-e-courier-info', [CourierController::class, 'getECourierInfo'])->name('orders.getECourierInfo');
    Route::get('courier/e-courier/search-location', [CourierController::class, 'eCourierSearchLocation'])->name('courier.eCourierSearchLocation');
    Route::post('courier/send-e-courier-order/{id}', [CourierController::class, 'sendECourierOrder'])->name('courier.sendECourierOrder');

    // Paperfly
    Route::post('courier/send-paperfly-order/{id}', [CourierController::class, 'sendPaperflyOrder'])->name('courier.sendPaperflyOrder');

    // Update Courier Status
    Route::get('orders/update-courier-status/{id}', [CourierController::class, 'updateCourierStatus'])->name('orders.updateCourierStatus');
    Route::post('orders/get-customer-data', [OrderController::class, 'getCustomerData'])->name('back.orders.getCustomerData');
    Route::post('orders/confirm-payment/{order}', [OrderController::class, 'confirmPayment'])->name('back.orders.confirmPayment');
    Route::post('orders/reject-payment/{order}', [OrderController::class, 'rejectPayment'])->name('back.orders.rejectPayment');
    Route::resource('orders', OrderController::class, ['as' => 'back']);

    // Payment Methods
    Route::resource('paymentMethods', PaymentMethodController::class, ['as' => 'back']);

    // Accounts Controller
    Route::prefix('accounts')->group(function () {
        Route::get('other-expenses', [AccountsController::class, 'otherExpenses'])->name('back.accounts.otherExpenses');
        Route::post('other-expenses/store', [AccountsController::class, 'otherExpensesStore'])->name('back.accounts.otherExpensesStore');
        Route::get('other-expenses/delete/{id}', [AccountsController::class, 'otherExpensesDelete'])->name('back.accounts.otherExpensesDelete');
        Route::post('other-expenses/edit/{id}', [AccountsController::class, 'otherExpensesUpdate']);
        Route::get('other-expenses/edit/{id}', [AccountsController::class, 'otherExpensesEdit'])->name('back.accounts.otherExpensesEdit');
        Route::get('report', [AccountsController::class, 'report'])->name('back.accounts.report');
        Route::get('courier', [AccountsController::class, 'courier'])->name('back.accounts.courier');
        Route::post('courier/receive', [AccountsController::class, 'courierReceive'])->name('back.accounts.courierReceive');
        Route::get('courier/delete/{id}', [AccountsController::class, 'courierDelete'])->name('back.accounts.courierDelete');
    });

    // Location
    Route::get('location', [LocationController::class, 'index'])->name('back.locations.index');
    Route::post('location/states/update/{id}', [LocationController::class, 'statesUpdate'])->name('back.locations.statesUpdate');

    // Recycle Bin
    Route::get('recycle-bin', [RecycleBinController::class, 'index'])->name('back.recycleBun.index');
    Route::get('recycle-bin/restore-product/{id}', [RecycleBinController::class, 'restoreProduct'])->name('back.recycleBun.restoreProduct');
    Route::get('recycle-bin/restore-category/{id}', [RecycleBinController::class, 'restoreCategory'])->name('back.recycleBun.restoreCategory');
    Route::get('recycle-bin/restore-brand/{id}', [RecycleBinController::class, 'restoreBrand'])->name('back.recycleBun.restoreBrand');

    // Role
    Route::resource('roles', RoleController::class, ['as' => 'back'])->except('show');

    // Footer Widgets CRUD
    Route::resource('footer-widgets', FooterWidgetController::class, ['as' => 'back']);

    // Testimonials
    Route::resource('testimonials', TestimonialController::class, ['as' => 'back']);

    // Blog CRUD
    Route::get('blogs/categories', [BlogController::class, 'categories'])->name('back.blogs.categories');
    Route::get('blogs/categories/create', [BlogController::class, 'categoriesCreate'])->name('back.blogs.categories.create');
    Route::get('blogs/remove-image/{blog}', [BlogController::class, 'removeImage'])->name('back.blogs.removeImage');
    Route::post('blogs/categories/store', [BlogController::class, 'categoriesStore'])->name('back.blogs.categories.store');
    Route::get('blogs/categories/edit/{id}', [BlogController::class, 'categoriesEdit'])->name('back.blogs.categories.edit');
    Route::post('blogs/categories/update/{id}', [BlogController::class, 'categoriesUpdate'])->name('back.blogs.categories.update');
    Route::delete('blogs/categories/destroy/{id}', [BlogController::class, 'categoriesDestroy'])->name('back.blogs.categories.destroy');
    Route::resource('blogs', BlogController::class, ['as' => 'back'])->except('show');
});
