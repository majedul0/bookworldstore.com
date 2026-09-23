<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\ContactMessage;
use App\Models\Order\OrderProduct;
use App\Models\Page;
use App\Models\Product\Category;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductData;
use App\Models\Product\Review;
use App\Models\Slider;
use App\Models\Testimonial;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    // Homepage
    public function homepage(Request $request)
    {
        $sliders = cache()->remember('home_sliders', (60 * 60 * 24 * 90), function(){
            return Slider::active()->get();
        });
        $featured_categories = cache()->remember('home_featured_categories', (60 * 60 * 24 * 90), function(){
            return Category::with('Categories')->where('feature', 1)->active()->get();
        });
        $featured_products = cache()->remember('home_featured_products', (60 * 60 * 24 * 90), function(){
            return Product::active()->get();
        });

        return view('front.homepage', compact('sliders', 'featured_products', 'featured_categories'));
    }

    public function loadProductAjax(Request $request){
        $products = Product::active();
        if($request->category){
            $product_ids = ProductCategory::where('category_id', $request->category)->pluck('product_id')->toArray();

            $products->whereIn('id', $product_ids);
        }
        $products = $products->skip($request->skip)->take($request->take)->get();

        if(!count($products)){
            return [
                'status' => false,
                'message' => 'No more products!',
            ];
        }

        $output_html = '';
        foreach($products as $product){
            $output_html .= view('front.layouts.product-loop', [
                'product' => $product
            ])->render();
        }

        return [
            'status' => true,
            'html' => $output_html
        ];
    }

    public function category($id){
        $category = Category::where('id', $id)->active()->firstOrFail();
        // $products = ProductRepo::filter($category->id, [], [], [], [], 0, 24, 'DESC');
        $product_ids = ProductCategory::where('category_id', $category->id)->pluck('product_id')->toArray();
        $products = Product::whereIn('id', $product_ids)->active()->paginate(25);

        return view('front.category', compact('category', 'products'));
    }

    public function search(Request $request){
        $search = $request->search;
        $products = Product::query();
        $products->active();
        $products->where(function($q) use ($search){
            $q->where('id', $search)->orWhere('title', 'LIKE', "%$search%");
        });
        $total_count = $products->count();
        $products = $products->paginate(28);

        return view('front.search', compact('products', 'total_count', 'search'));
    }

    public function product($product){
        $product = Product::active()->findOrFail($product);

        // Manually selected related products (admin dashboard) get priority
        $related_products = $product->RelatedProducts()->active()->get();

        // Fallback: products from the same categories
        if(!$related_products->count()){
            $cids = $product->Categories->pluck('id')->toArray();
            $related_products = ProductCategory::with('Product')
                ->join('products', 'products.id', '=', 'product_categories.product_id')
                ->where('products.status', 1)
                ->where('products.deleted_at', null)
                ->whereIn('category_id', $cids)
                ->where('product_categories.product_id', '!=', $product->id)
                ->select('product_categories.product_id')
                ->distinct()
                ->latest('product_categories.product_id')
                ->take(24)->get();
            $related_products = $related_products->pluck('Product');
        }

        if($product->type == 'Variable'){
            $first_price = $product->VariableProductData->sortBy('sale_price')->first()->sale_price ?? null;
            $last_price = $product->VariableProductData->sortByDesc('sale_price')->first()->sale_price ?? null;
        }else{
            $first_price = null;
            $last_price = null;
        }

        $reviews = Review::where('product_id', $product->id)->where('status', 1)->latest('id')->get();
        $rating_counts = Review::where('product_id', $product->id)->where('status', 1)
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating');

        return view('front.product', compact('product', 'first_price', 'last_price', 'related_products', 'reviews', 'rating_counts'));
    }

    public function storeReview(Request $request, $product)
    {
        $request->validate([
            'rating'  => 'required|integer|between:1,5',
            'review'  => 'required|string|max:2000',
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:100',
        ]);

        Review::create([
            'product_id' => $product,
            'status'     => 2,
            'rating'     => $request->rating,
            'review'     => $request->review,
            'title'      => $request->title,
            'name'       => $request->name,
            'email'      => $request->email,
            'user_id'    => auth()->id(),
        ]);

        return redirect()->back()->with('review_submitted', true);
    }

    public function allProducts(){
        $products = Product::active()->select('id', 'title', 'type', 'image_path', 'image', 'regular_price', 'sale_price', 'slug')->paginate(28);

        return view('front.allProducts', compact('products'));
    }

    public function todayDeals(){
        $top_sold_products = OrderProduct::query()
            ->join('orders', 'orders.id', '=', 'order_products.order_id')
            ->where('orders.status', 'Completed')
            ->where('order_products.created_at', '>=', Carbon::now()->subDays(7))
            ->whereNull('order_products.deleted_at')
            ->whereNull('orders.deleted_at')
            ->select('order_products.product_id', DB::raw('SUM(order_products.quantity) as total_sold'))
            ->groupBy('order_products.product_id');

        $products = Product::query()
            ->leftJoinSub($top_sold_products, 'top_sold_products', function($join){
                $join->on('products.id', '=', 'top_sold_products.product_id');
            })
            ->where('products.status', 1)
            ->whereNotNull('top_sold_products.total_sold')
            ->select('products.id', 'products.title', 'products.type', 'products.image_path', 'products.image', 'products.regular_price', 'products.sale_price', 'products.slug')
            ->addSelect(DB::raw('top_sold_products.total_sold as total_sold'))
            ->orderByDesc('total_sold')
            ->latest('products.id')
            ->paginate(28);

        return view('front.allHotDeals', compact('products'));
    }

    public function contactUs(){
        return view('front.contactUs');
    }

    public function storeContactUs(Request $request){
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'nullable|email|max:100',
            'phone'   => 'nullable|string|max:30',
            'message' => 'required|string|max:5000',
        ], [
            'name.required'    => 'Please enter your name.',
            'message.required' => 'Please write your message.',
        ]);

        if(!$request->email && !$request->phone){
            return redirect()->back()->withInput()
                ->withErrors(['email' => 'Please provide an e-mail or a phone number so we can reply.']);
        }

        $contact_message = ContactMessage::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'message' => $request->message,
            'ip'      => $request->ip(),
        ]);

        $to = \Info::SettingsGroupKey('general')['email'] ?? null;

        if($to){
            try{
                Mail::raw(
                    "Name: {$contact_message->name}\n"
                    . "Email: " . ($contact_message->email ?: '-') . "\n"
                    . "Phone: " . ($contact_message->phone ?: '-') . "\n\n"
                    . $contact_message->message,
                    function($mail) use ($to, $contact_message){
                        $mail->to($to)->subject('New contact message from ' . $contact_message->name);

                        if($contact_message->email)
                            $mail->replyTo($contact_message->email, $contact_message->name);
                    }
                );
            }catch(\Throwable $e){
                // The message is already stored, so a mail failure must not break the visitor's flow.
                Log::error('Contact form mail failed: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('contact_submitted', true);
    }

    public function variationPrice(Request $request){
        $output = [
            'status' => false,
            'sale_price' => '',
            'old_price' => null,
            'sku' => '',
            'product_data_id' => '',
            'stock' => ''
        ];

        $product = $request->product;
        $attr_values = $request->values;

        $attr_values_string = implode(',', $attr_values) . ',';

        $product_data = ProductData::where('product_id', $product)->where('attribute_item_ids', $attr_values_string)->first();

        if($product_data){
            $output['status'] = true;
            $output['sale_price'] = $product_data->sale_price;
            $output['old_price'] = $product_data->regular_price;
            $output['sku'] = $product_data->sku_code;
            $output['product_data_id'] = $product_data->id;
            $output['stock'] = $product_data->stock;
        }

        return $output;
    }

    public function page($page)
    {
        $page = Page::where('slug', $page)->active()->firstOrFail();
        return view('front.page', compact('page'));
    }

    public function blogs(){
        $blogs = Blog::where('status', 1)->orderBy('id', 'DESC')->paginate(10);

        return view('front.blogs', compact('blogs'));
    }

    public function blog($id){
        $blog = Blog::where('status', 1)->findOrFail($id);

        $categories_id = $blog->Categories->pluck('id')->toArray();

        $related_blogs = Blog::whereHas('Categories', function($q) use($categories_id){
            $q->whereIn('blog_categories.category_id', $categories_id);
        })->whereNot('id', $id)->latest('id')->take(4)->get();

        return view('front.blog', compact('blog', 'related_blogs'));
    }
}
