<?php

use App\Http\Controllers\Front\AuthController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\OrderController;
use App\Http\Controllers\Front\PageController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Other Pages
Route::get('/', [PageController::class, 'homepage'])->name('homepage');
Route::post('load-product-ajax', [PageController::class, 'loadProductAjax'])->name('loadProductAjax');
Route::get('category/{id}', [PageController::class, 'category'])->name('category');
Route::get('search', [PageController::class, 'search'])->name('search');
Route::get('product/{product}', [PageController::class, 'product'])->name('product');
Route::post('product/{product}/review', [PageController::class, 'storeReview'])->name('product.review.store');
Route::get('shop', [PageController::class, 'allProducts'])->name('shop');
Route::get('all-products', [PageController::class, 'allProducts'])->name('allProducts');
Route::get('today-deals', [PageController::class, 'todayDeals'])->name('todayDeals');
Route::get('contact-us', [PageController::class, 'contactUs'])->name('contactUs');
Route::post('contact-us', [PageController::class, 'storeContactUs'])->name('contactUs.store');
Route::post('single-product/get-variation-price', [PageController::class, 'variationPrice'])->name('product.variationPrice');
Route::get('blogs', [PageController::class, 'blogs'])->name('blogs');
Route::get('blog/{id}', [PageController::class, 'blog'])->name('blog');

// Cart
Route::get('cart', [CartController::class, 'cart'])->name('cart');
Route::get('checkout', [CartController::class, 'checkout'])->name('checkout');
Route::post('cart-info', [CartController::class, 'cartInfo'])->name('cartInfo');
Route::get('cart/direct-order', [CartController::class, 'directOrder'])->name('cart.directOrder');
Route::post('cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('cart/update', [CartController::class, 'update'])->name('cart.update');

// Order
// Route::get('checkout', [OrderController::class, 'checkout'])->name('order.checkout');
Route::post('order', [OrderController::class, 'order'])->name('order');
Route::post('order_form_product/{id}', [OrderController::class, 'order_form_product'])->name('order_form_product');
Route::post('order-failed-track-saas', [OrderController::class, 'orderFailedTrackSaas'])->name('orderFailedTrackSaas');
Route::get('thank-you/{id}', [OrderController::class, 'orderComDetails'])->name('orderComDetails');
Route::get('thank-you-missing/{id}', [OrderController::class, 'orderComDetailsMissing'])->name('orderComDetailsMissing');
Route::get('order-track', [OrderController::class, 'track'])->name('order.track');

// Auth
Auth::routes();
Route::get('auth/order-details/{id}', [AuthController::class, 'orderDetails'])->name('auth.orderDetails');

Auth::routes();

// Custom Sitemap
Route::get('sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::prefix('sitemap')->group(function () {
    // Route::get('/', [SitemapController::class, 'index'])->name('sitemap');
    Route::get('products-{page}.xml', [SitemapController::class, 'products'])->name('sitemap.products');
    // Route::get('products.xml', [SitemapController::class, 'products'])->name('sitemap.products');
    Route::get('product-categories.xml', [SitemapController::class, 'productCat'])->name('sitemap.product.categories');
    Route::get('articles.xml', [SitemapController::class, 'articles'])->name('sitemap.articles');
    Route::get('article-categories.xml', [SitemapController::class, 'articleCat'])->name('sitemap.article.categories');
    Route::get('pages.xml', [SitemapController::class, 'pages'])->name('sitemap.pages');
});

// Test Routes
// Route::get('test',              [TestController::class, 'test'])->name('test');
Route::get('cache-clear',       [TestController::class, 'cacheClear']);
Route::get('cache-clear-admin', [TestController::class, 'cacheClearAdmin'])->name('cacheClearAdmin');
// Route::get('config',            [TestController::class, 'config'])->name('config');

// Database Page
Route::get('{product_slug}', [LandingController::class, 'productSlug'])->name('landing.productSlug');

Route::get('p/{page}', [PageController::class, 'page'])->name('page');
