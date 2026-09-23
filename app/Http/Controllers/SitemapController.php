<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Page;
use App\Models\Product\Product;
use App\Models\Product\Category;

class SitemapController extends Controller
{
    public function index()
    {
        $product = Product::active()->latest('id')->first();
        $product_category = Category::where('status', 1)->where('for', 'product')->latest('id')->first();
        $article = Blog::where('status', 1)->latest('id')->first();
        $article_category = Category::where('status', 1)->where('for', 'blog')->latest('id')->first();

        $product_count = Product::active()->count();
        $total_product_page = ceil($product_count / 1000);

        return response()->view('sitemap.index', [
            'product' => $product,
            'product_category' => $product_category,
            'article' => $article,
            'total_product_page' => $total_product_page,
            'article_category' => $article_category,
        ])->header('Content-Type', 'text/xml');
    }

    public function products($page){
        $skip = ($page * 1000) - 1000;
        $q = Product::active()->latest('id')->skip($skip)->take(1000)->get();

        return response()->view('sitemap.products', [
            'query' => $q,
        ])->header('Content-Type', 'text/xml');
    }

    public function productCat(){
        $q = Category::where('status', 1)->where('for', 'product')->latest('id')->get();
        return response()->view('sitemap.productCategories', [
            'query' => $q,
        ])->header('Content-Type', 'text/xml');
    }

    public function articles(){
        $q = Blog::where('status', 1)->latest('id')->get();
        return response()->view('sitemap.articles', [
            'query' => $q,
        ])->header('Content-Type', 'text/xml');
    }

    public function articleCat(){
        $q = Category::where('status', 1)->where('for', 'blog')->latest('id')->get();
        return response()->view('sitemap.articleCategories', [
            'query' => $q,
        ])->header('Content-Type', 'text/xml');
    }
    public function pages(){
        $pages = Page::where('status', 1)->latest('id')->get();

        return response()->view('sitemap.pages', compact('pages'))->header('Content-Type', 'text/xml');
    }

    public function feed(){
        $products = Product::active()->take(5000)->latest('id')->get();

        // $posts = Post::with('Categories')->where('status', 1)->latest('id')->get();

        // return response()->view('sitemap.feed', compact('posts'))->header('Content-Type', 'text/xml');
        return response()->view('sitemap.feed', compact('products'))->header('Content-Type', 'text/xml');

        // $products = Product::active()->latest('id')->get();

        // return response()->view('sitemap.feed', compact('products'))->header('Content-Type', 'text/xml');

        // $posts = Post::with('Categories')->where('status', 1)->latest('id')->get();

        // return response()->view('sitemap.feed', compact('posts'))->header('Content-Type', 'text/xml');
    }
}
