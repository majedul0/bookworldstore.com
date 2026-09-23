<?php

namespace App\Http\Controllers\Back\Product;

use App\Http\Controllers\Controller;
use App\Http\Middleware\RolePermissionMiddleware;
use App\Models\Product\Attribute;
use App\Models\Product\Brand;
use App\Models\Product\Category;
use App\Models\Product\Product;
use App\Models\Product\ProductAttribute;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductData;
use App\Models\Product\ProductMedia;
use App\Models\Product\Review;
use App\Models\ProductBundleItem;
use App\Repositories\JsonResponse;
use App\Repositories\MediaRepo;
use App\Repositories\ProductRepo;
use Illuminate\Http\Request;
use \Cviebrock\EloquentSluggable\Services\SlugService;
use Info;

class ProductController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(RolePermissionMiddleware::class)->only('create', 'store', 'edit', 'update', 'index', 'table', 'destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $products = Product::latest('id')->get();

        return view('back.product.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::with('Categories')->where('category_id', null)->where('for', 'product')->active()->get();
        $brands = Brand::active()->get();
        $attributes = Attribute::active()->get();

        return view('back.product.create', compact('categories', 'brands', 'attributes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'short_description' => 'max:1000',
            'description' => 'required',
            'type' => 'required',
            'sale_price' => 'required',
            'category' => 'required',
            //'expire_date' => 'date',
            // 'categories' => 'required',
            'image' => 'required|mimes:jpg,png,jpeg,gif',
        ]);

        // // Check SKU
        // if ($request->type == 'Simple') {
        //     if($request->sku_code){
        //         $check_sku = ProductData::where('sku_code', $request->sku_code)->first();
        //         if($check_sku){
        //             return redirect()->back()->withInput()->with('error', 'SKU must be unique!');
        //         }
        //     }
        // }
        // if($request->type == 'Variable' && count((array)$request->sku_code)){
        //     $check_sku = ProductData::whereIn('sku_code', (array)$request->sku_code)->where('sku_code', '!=', null)->first();
        //     if($check_sku){
        //         return redirect()->back()->with('error', 'SKU must be unique!');
        //     }
        // }

        $bundle_items = (array)$request->products;
        if ($request->type == 'Bundle') {
            if (!count($bundle_items)) {
                return redirect()->back()->with('error', 'Please select some bundle item!');
            }
        }

        $product = new Product;
        $product->title = $request->title;
        // $slug = SlugService::createSlug(Product::class, 'slug', $request->title);
        // $product->slug = $slug;

        $product->type = $request->type;
        $product->expire_date = $request->expire_date;
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->brand_id = $request->brand;
        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;
        $product->meta_tags = $request->meta_tags;
        $product->stock_alert_quantity = $request->stock_alert_quantity ?? 0;
        $product->stock_pre_alert_quantity = $request->stock_pre_alert_quantity ?? 0;
        $product->custom_label = $request->custom_label;
        $product->youtube_video_embed = $request->youtube_video_embed;

        // Product media relation
        if ($request->file('image')) {
            $uploaded_file = MediaRepo::upload($request->file('image'));
            $product->image = $uploaded_file['file_name'];
            $product->image_path = $uploaded_file['file_path'];
            $product->media_id = $uploaded_file['media_id'];
        }

        $product->save();

        // Simple Attributes
        foreach ((array)$request->simpl_attributes as $attribute) {
            $product_attribute = new ProductAttribute;
            $product_attribute->product_id = $product->id;
            $product_attribute->attribute_id = $attribute;
            $product_attribute->type = 'Simple';
            $product_attribute->save();
        }

        // Product Data
        if ($request->type == 'Simple' || $request->type == 'Bundle') {
            $product_data = new ProductData;
            $product_data->type = $request->type;
            $product_data->product_id = $product->id;
            $product_data->regular_price = $request->regular_price;
            $product_data->sale_price = $request->sale_price;
            $product_data->cost = $request->product_cost ?? 0;
            // $product_data->sku_code = $request->sku_code;
            // $product_data->shipping_weight = $request->shipping_weight;
            // $product_data->shipping_width = $request->shipping_width;
            // $product_data->shipping_height = $request->shipping_height;
            // $product_data->shipping_length = $request->shipping_length;
            $product_data->rack_number = $request->rack_number;
            $product_data->unit = $request->unit;
            $product_data->unit_amount = $request->unit_amount;

            // $product_data->promotion_price = $request->promotion_price;
            // $product_data->promotion_start_date = Carbon::parse($request->promotion_start_date);
            // $product_data->promotion_end_date = Carbon::parse($request->promotion_end_date);
            $product_data->save();
        } else {
            foreach ($request->attribute_item_ids as $key => $attribute_item_ids) {
                $product_data = new ProductData;
                $product_data->type = $request->type;
                $product_data->product_id = $product->id;

                $product_data->attribute_item_ids = $attribute_item_ids;
                $product_data->regular_price = $request->regular_price[$key];
                $product_data->sale_price = $request->sale_price[$key];
                $product_data->cost = $request->product_cost[$key] ?? 0;
                $product_data->rack_number = $request->rack_number[$key];
                $product_data->unit = $request->unit[$key] ?? null;
                $product_data->unit_amount = $request->unit_amount[$key] ?? null;

                // Upload image
                if (isset($request->variation_image[$key])) {
                    $uploaded_file = MediaRepo::upload($request->variation_image[$key]);
                    $product->image = $uploaded_file['file_name'];
                    $product->image_path = $uploaded_file['file_path'];
                    $product->media_id = $uploaded_file['media_id'];
                }

                // Store Discount Percent
                if ($product_data->regular_price && $product_data->sale_price) {
                    $discount = $product_data->regular_price - $product_data->sale_price;

                    $save_in_one = $discount / $product_data->regular_price;
                    $percent_amount = (int) ($save_in_one * 100);

                    $product_data->discount_percent = $percent_amount;
                }

                $product_data->save();
            }

            // Variable attributes
            foreach ($request->variable_attributes as $attribute) {
                $product_attribute = new ProductAttribute;
                $product_attribute->product_id = $product->id;
                $product_attribute->attribute_id = $attribute;
                $product_attribute->type = 'Variable';
                $product_attribute->save();
            }
        }

        // Create product category
        if ($request->category && count((array)$request->category)) {
            foreach ((array)$request->category as $category) {
                ProductRepo::insertProductCategory($product->id, $category);
            }
        }
        if ($request->sub_category && count((array)$request->sub_category)) {
            foreach ((array)$request->sub_category as $sub_category) {
                ProductRepo::insertProductCategory($product->id, $sub_category);
            }
        }
        if ($request->sub_sub_category && count((array)$request->sub_sub_category)) {
            foreach ((array)$request->sub_sub_category as $sub_sub_category) {
                ProductRepo::insertProductCategory($product->id, $sub_sub_category);
            }
        }

        // Bundle Items
        if ($request->type == 'Bundle') {
            foreach ($bundle_items as $bundle_item) {
                $bundle_product_data = ProductData::where('type', 'Simple')->where('product_id', $bundle_item)->first();

                if ($bundle_product_data) {
                    $product_bundle_item = new ProductBundleItem();
                    $product_bundle_item->product_id = $product->id;
                    $product_bundle_item->bundle_product_id = $bundle_item;
                    $product_bundle_item->product_data_id = $bundle_product_data->id;
                    $product_bundle_item->save();
                }
            }
        }

        // Product media relation
        foreach ((array)$request->gallery_id as $key => $media_id) {
            $product_media = ProductMedia::where('media_id', $media_id)->where('product_id', $product->id)->first();

            if (!$product_media) {
                $product_media = new ProductMedia;
                $product_media->media_id = $media_id;
                $product_media->product_id = $product->id;
                $product_media->position = $key;
                $product_media->save();
            }
        }

        // Related products
        $this->syncRelatedProducts($product, (array)$request->related_products);

        ProductRepo::index($product->id);

        return redirect()->back()->with('success-alert', 'Product created successfully.');
    }

    public function duplicate_product(Request $request)
    {
        // Find the original product
        $originalProduct = Product::findOrFail($request->id);


        // Create a new product instance with the same attributes
        $newProduct = $originalProduct->replicate();
        $newProduct->title = $originalProduct->title . ' (Dublicate)';
        $newProduct->save();

        // product_attributes start
        $product_attributes_array = ProductAttribute::where('product_id', $originalProduct->id)->get();
        if (count($product_attributes_array) > 0) {
            foreach ($product_attributes_array as $attribute) {
                $product_attribute = $attribute->replicate();
                $product_attribute->product_id = $newProduct->id;;
                $product_attribute->save();
            }
        }
        // product_attributes end

        // Duplicate Product Data
        $productDataRecords = ProductData::where('product_id', $originalProduct->id)->get();

        if (count($productDataRecords)) {
            foreach ($productDataRecords as $data) {
                $newProductData = $data->replicate();
                $newProductData->product_id = $newProduct->id; // Link to new product
                $newProductData->save();
            }
        }

        // Product media relation
        $product_medias_array = ProductMedia::where('product_id', $originalProduct->id)->get();

        if (count($product_medias_array)) {
            foreach ($product_medias_array as $data) {
                $product_media = $data->replicate();
                $product_media->product_id = $newProduct->id;
                $product_media->save();
            }
        }

        // ProductCategory
        $productCategory_array = ProductCategory::where('product_id', $originalProduct->id)->get();

        if (count($productCategory_array)) {
            foreach ($productCategory_array as $data) {
                $productCategory = $data->replicate();
                $productCategory->product_id = $newProduct->id;
                $productCategory->save();
            }
        }

        // Related products
        $related_products = $originalProduct->RelatedProducts->pluck('id')->toArray();
        $this->syncRelatedProducts($newProduct, $related_products);

        // Index the new product
        ProductRepo::index($newProduct->id);

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Product duplicated successfully.',
        ]);
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $categories = Category::where('category_id', null)->where('for', 'product')->active()->get();
        $brands = Brand::active()->get();
        $attributes = Attribute::active()->get();

        $product_categories = $product->Categories->pluck('id')->toArray();
        $product_simple_attributes = $product->Attributes->pluck('id')->toArray();
        $product_variable_attributes = $product->VariableAttributes->pluck('id')->toArray();

        $product_main_categories = $product->Categories->where('category_id', null)->pluck('id')->toArray();
        $product_sub_categories = Category::whereIn('category_id', $product_main_categories)->active()->get();

        return view('back.product.edit', compact('product', 'categories', 'brands', 'attributes', 'product_categories', 'product_simple_attributes', 'product_variable_attributes', 'product_sub_categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //dd($request->all());
        $v_data = [
            'title' => 'required|max:255',
            'short_description' => 'max:1000',
            'description' => 'required',
            //'expire_date' => 'date',
            'category' => 'required',
            'type' => 'required',
            /*"shipping_weight" => 'numeric',
            "shipping_width" => 'numeric',
            "shipping_height" => 'numeric',
            "shipping_length" => 'numeric',*/
            // 'categories' => 'required'
        ];

        if ($request->type == 'Simple') {
            $v_data['sale_price'] = 'required|numeric';
            $v_data['manage_stock_by'] = 'required';
            if ($request->manage_stock_by == 'Other') {
                $v_data['source_product'] = 'required';
                $v_data['stock_conversion_fact'] = 'required';
            }
        }

        $request->validate($v_data);

        $bundle_items = (array)$request->products;
        if ($request->type == 'Bundle') {
            if (!count($bundle_items)) {
                return redirect()->back()->with('error', 'Please select some bundle item!');
            }
        }

        // Check SKU
        // if ($product->type == 'Simple') {
        //     if($request->sku_code && $product->ProductData && $product->ProductData->sku_code != $request->sku_code){
        //         $check_sku = ProductData::where('sku_code', $request->sku_code)->first();
        //         if($check_sku){
        //             return redirect()->back()->withInput()->with('error', 'SKU must be unique!');
        //         }
        //     }
        // }
        // if($product->type == 'Variable' && count((array)$request->update_sku_code)){
        //     // Check Existing
        //     $variable_sku = $product->VariableProductData->pluck('sku_code')->toArray();
        //     $check_sku = ProductData::whereNotIn('sku_code', $variable_sku)->whereIn('sku_code', (array)$request->update_sku_code)->where('sku_code', '!=', null)->first();
        //     if($check_sku){
        //         return redirect()->back()->with('error', 'SKU must be unique!');
        //     }

        //     // Check New
        //     if(count((array)$request->sku_code)){
        //         $check_sku = ProductData::whereIn('sku_code', (array)$request->sku_code)->where('sku_code', '!=', null)->first();
        //         if($check_sku){
        //             return redirect()->back()->with('error', 'SKU must be unique!');
        //         }
        //     }
        // }

        if ($product->title != $request->title) {
            $slug = SlugService::createSlug(Product::class, 'slug', $request->title);
            $product->slug = $slug;
        }

        $product->title = $request->title;
        $product->type = $request->type;

        $product->expire_date = $request->expire_date;
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->brand_id = $request->brand;
        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;
        $product->meta_tags = $request->meta_tags;
        $product->stock_alert_quantity = $request->stock_alert_quantity ?? 0;
        $product->stock_pre_alert_quantity = $request->stock_pre_alert_quantity ?? 0;
        $product->custom_label = $request->custom_label;
        $product->youtube_video_embed = $request->youtube_video_embed;

        // Product media relation
        if ($request->file('image')) {
            $uploaded_file = MediaRepo::upload($request->file('image'));
            $product->image = $uploaded_file['file_name'];
            $product->image_path = $uploaded_file['file_path'];
            $product->media_id = $uploaded_file['media_id'];
        }

        $product->save();

        // Simple Attributes
        ProductAttribute::whereNotIn('attribute_id', (array)$request->simple_attributes)->where('product_id', $product->id)->where('type', 'Simple')->delete();
        foreach ((array)$request->simple_attributes as $attribute) {
            $product_attribute = ProductAttribute::where('product_id', $product->id)->where('attribute_id', $attribute)->where('type', 'Simple')->first();

            if (!$product_attribute) {
                $product_attribute = new ProductAttribute;
                $product_attribute->product_id = $product->id;
                $product_attribute->attribute_id = $attribute;
                $product_attribute->type = 'Simple';
                $product_attribute->save();
            }
        }

        // Product Data
        if ($product->type == 'Simple' || $product->type == 'Bundle') {
            $product_data = $product->ProductData;

            // Create new if data not exist
            if (!$product_data) {
                $product_data = new ProductData;
                $product_data->type = $product->type;
                $product_data->product_id = $product->id;
            }

            $product_data->regular_price = $request->regular_price;
            $product_data->sale_price = $request->sale_price;
            $product_data->cost = $request->product_cost ?? 0;
            $product_data->unit = $request->unit;
            $product_data->unit_amount = $request->unit_amount;
            $product_data->stock_managed_by = $request->manage_stock_by;
            $product_data->stock_product_data_id = $request->source_product;
            $product_data->stock_conversion_fact = $request->stock_conversion_fact;

            // Update Discount Percent
            if ($product_data->regular_price && $product_data->sale_price) {
                $discount = $product_data->regular_price - $product_data->sale_price;

                $save_in_one = $discount / $product_data->regular_price;
                $percent_amount = (int) ($save_in_one * 100);

                $product_data->discount_percent = $percent_amount;
            }

            $product_data->save();
        } else {
            // Delete data
            ProductData::whereNotIn('id', (array)$request->product_data_ids)->where('type', $product->type)->where('product_id', $product->id)->delete();

            // Insert New
            foreach ((array)$request->attribute_item_ids as $key => $attribute_item_ids) {
                $product_data = new ProductData;
                $product_data->type = $product->type;
                $product_data->product_id = $product->id;

                $product_data->attribute_item_ids = $attribute_item_ids;
                $product_data->regular_price = $request->regular_price[$key] ?? 0;
                $product_data->sale_price = $request->sale_price[$key];
                $product_data->cost = $request->product_cost[$key] ?? 0;
                $product_data->unit = $request->unit[$key] ?? null;
                $product_data->unit_amount = $request->unit_amount[$key] ?? null;

                // Update Discount Percent
                if ($product_data->regular_price && $product_data->sale_price) {
                    $discount = $product_data->regular_price - $product_data->sale_price;

                    $save_in_one = $discount / $product_data->regular_price;
                    $percent_amount = (int) ($save_in_one * 100);

                    $product_data->discount_percent = $percent_amount;
                }

                // Upload image
                if (isset($request->variation_image[$key])) {
                    $uploaded_file = MediaRepo::upload($request->variation_image[$key]);
                    $product_data->image = $uploaded_file['file_name'];
                    $product_data->image_path = $uploaded_file['file_path'];
                    $product_data->media_id = $uploaded_file['media_id'];
                }

                $product_data->save();
            }

            // Update Existing
            foreach ((array)$request->product_data_ids as $key => $product_data_id) {
                $product_data = ProductData::find($product_data_id);

                $product_data->attribute_item_ids = $request->update_attribute_item_ids[$key];
                $product_data->regular_price = $request->update_regular_price[$key];
                $product_data->sale_price = $request->update_sale_price[$key];
                $product_data->cost = $request->update_product_cost[$key] ?? 0;
                $product_data->rack_number = $request->update_rack_number[$key];
                $product_data->unit = $request->update_unit[$key] ?? null;
                $product_data->unit_amount = $request->update_unit_amount[$key] ?? null;

                // Upload image
                $file_input_name = "update_" . $product_data_id . "variation_image";

                if (isset($request[$file_input_name])) {
                    $uploaded_file = MediaRepo::upload($request[$file_input_name]);
                    $product_data->image = $uploaded_file['file_name'];
                    $product_data->image_path = $uploaded_file['file_path'];
                    $product_data->media_id = $uploaded_file['media_id'];
                }

                $product_data->save();
            }

            // Variable attributes
            ProductAttribute::whereNotIn('attribute_id', (array)$request->variable_attributes)->where('product_id', $product->id)->delete();

            foreach ($request->variable_attributes as $attribute) {
                $product_attribute = ProductAttribute::where('product_id', $product->id)->where('attribute_id', $attribute)->first();

                if (!$product_attribute) {
                    $product_attribute = new ProductAttribute;
                    $product_attribute->product_id = $product->id;
                    $product_attribute->attribute_id = $attribute;
                    $product_attribute->type = 'Variable';
                    $product_attribute->save();
                }
            }
        }

        // Update product category
        $new_categories = [];
        $new_categories = array_merge($new_categories, (array)$request->category);
        if ($request->sub_category && count((array)$request->sub_category)) {
            $new_categories = array_merge($new_categories, (array)$request->sub_category);
        }
        if ($request->sub_sub_category && count((array)$request->sub_sub_category)) {
            $new_categories = array_merge($new_categories, (array)$request->sub_sub_category);
        }

        ProductCategory::whereNotIn('category_id', $new_categories)->where('product_id', $product->id)->delete();

        // Insert New
        foreach ((array)$request->category as $category) {
            $category_relation = ProductCategory::where('category_id', $category)->where('product_id', $product->id)->first();
            if (!$category_relation) {
                ProductRepo::insertProductCategory($product->id, $category);
            }
        }
        if ($request->sub_category && count((array)$request->sub_category)) {
            foreach ((array)$request->sub_category as $sub_category) {
                $category_relation = ProductCategory::where('category_id', $sub_category)->where('product_id', $product->id)->first();
                if (!$category_relation) {
                    ProductRepo::insertProductCategory($product->id, $sub_category);
                }
            }
        }
        if ($request->sub_sub_category && count((array)$request->sub_sub_category)) {
            foreach ((array)$request->sub_sub_category as $sub_sub_category) {
                $category_relation = ProductCategory::where('category_id', $sub_sub_category)->where('product_id', $product->id)->first();
                if (!$category_relation) {
                    ProductRepo::insertProductCategory($product->id, $sub_sub_category);
                }
            }
        }

        // Product media relation update
        ProductMedia::whereNotIn('media_id', (array)$request->old_gallery_id)->where('product_id', $product->id)->delete();

        foreach ((array)$request->gallery_id as $key => $media_id) {
            $product_media = ProductMedia::where('media_id', $media_id)->where('product_id', $product->id)->first();

            if (!$product_media) {
                $product_media = new ProductMedia;
                $product_media->media_id = $media_id;
                $product_media->product_id = $product->id;
                $product_media->position = $key;
                $product_media->save();
            }
        }

        if ($request->type == 'Bundle') {
            ProductBundleItem::where('product_id', $product->id)->whereNotIn('bundle_product_id', $bundle_items)->delete();
            foreach ($bundle_items as $bundle_item) {
                $bundle_product_data = ProductData::where('type', 'Simple')->where('product_id', $bundle_item)->first();

                if ($bundle_product_data) {
                    $product_bundle_item = ProductBundleItem::where('bundle_product_id', $bundle_item)->first();

                    if (!$product_bundle_item) {
                        $product_bundle_item = new ProductBundleItem;

                        $product_bundle_item->product_id = $product->id;
                        $product_bundle_item->bundle_product_id = $bundle_item;
                        $product_bundle_item->product_data_id = $bundle_product_data->id;
                        $product_bundle_item->save();
                    }
                }
            }
        }

        // Related products
        $this->syncRelatedProducts($product, (array)$request->related_products);

        ProductRepo::index($product->id);

        return redirect()->back()->with('success-alert', 'Product updated successful.');
    }

    // Keep the manually selected related products in sync, in the selected order
    private function syncRelatedProducts(Product $product, array $related_products)
    {
        $sync_data = [];
        $position = 0;

        foreach ($related_products as $related_product_id) {
            if (!$related_product_id || $related_product_id == $product->id) {
                continue;
            }

            $sync_data[$related_product_id] = ['position' => $position++];
        }

        $product->RelatedProducts()->sync($sync_data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->back()->with('success-alert', 'Product deleted successfully.');
    }

    // Apply attribute
    public function attributeApply(Request $request)
    {
        $attributes = Attribute::whereIn('id', $request->attr_ids)->get();

        return view('back.product.extra.applyAttribute', compact('attributes'));
    }

    // Table
    public function table(Request $request)
    {
        // Get Data
        $columns = array(
            0 => 'id',
            1 => 'title',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $query = Product::query();

        if ($request->request_type == 'pre_alert') {
            $query->whereRaw('stock < stock_pre_alert_quantity');
        } elseif ($request->request_type == 'alert') {
            $query->whereRaw('stock < stock_alert_quantity');
        } elseif ($request->request_type == 'stock_out') {
            $query->where('stock', '<=', 0);
        }

        // Search
        if ($request->input('search.value')) {
            $search = $request->input('search.value');
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhere('title', 'LIKE', "%{$search}%");
            });
        }
        if ($request->id_search) {
            $query->where('id', $request->id_search);
        }

        if ($request->with_deleted) {
            $query->withTrashed();
        }

        // Count Items
        $totalFiltered = $query->count();
        if ($limit == "-1") {
            $query->skip($start)->limit($totalFiltered);
        } else {
            $query->skip($start)->limit($limit);
        }
        $query = $query->orderBy($order, $dir)->get();

        $output = array();
        foreach ($query as $key => $data) {
            if ($dir == 'desc') {
                $nestedData['sl'] = ($key + $start) + 1;
            } else {
                $nestedData['sl'] = ($totalFiltered - ($start + $key));
            }
            // $nestedData['sl_desc'] = ($totalFiltered - $start) + 1;
            $nestedData['sku'] = $data->ProductData->sku_code ?? 'N/A';
            $nestedData['id'] = $data->id;
            $nestedData['depertment'] = $data->Categories[0]->title ?? 'N/A';
            $nestedData['name'] = $data->deleted_at ? $data->title : '<a href="#" onclick="return detailItem(`product`, ' . $data->id . ');">' . $data->title . '</a>';
            $nestedData['image'] = '<img src="' . $data->img_paths['small'] . '" style="width:35px">';
            $nestedData['type'] = $data->type;
            $nestedData['regular_price'] = $data->regular_price;
            $nestedData['sale_price'] = $data->sale_price;
            $nestedData['stock'] = (($data->deleted_at || $data->type == 'Bundle') ? 'N/A' : $data->stock);
            $nestedData['total_sales'] = $data->deleted_at ? 'N/A' : count($data->OrderProducts);
            $nestedData['featured'] = $data->deleted_at ? 'N/A' : '<label id="' . $data->id . '" class="c_switcher_switch"><input ' . ($data->featured ? 'checked' : '') . ' type="checkbox" data-id="' . $data->id . '"><span class="c_switcher_slider c_switcher_round"></span></label>';
            // $nestedData['featured'] = view('switcher::switch', ['table' => 'products', 'data' => $data, 'column' => 'featured'])->render();
            $nestedData['clearance_sale'] = view('switcher::switch', ['table' => 'products', 'data' => $data, 'column' => 'clearance_sale'])->render();
            $nestedData['status'] = $data->deleted_at ? 'N/A' : view('switcher::switch', ['table' => 'products', 'data' => $data])->render();
            $nestedData['stock_action'] = $data->deleted_at ? 'N/A' : view('back.product.extra.action-btns', compact('data'))->with('type', 'stock')->render();
            $nestedData['action'] = view('back.product.extra.action-btns', compact('data'))->with('type', 'general')->render();
            $nestedData['action_report'] = '<a href="' . route('back.report.productDetails', $data->id) . '" class="btn btn-success btn-sm"><i class="fas fa-eye"></i> Sale Details</a>';
            $output[] = $nestedData;
        }

        // Output
        $output = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalFiltered),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $output
        );
        return response()->json($output);
    }

    public function selectList(Request $request)
    {
        $search = $request->q;
        $products = Product::where(function ($q) use ($search) {
            $q->where('id', $search)->orWhere('title', 'LIKE', "%{$search}%");
        });
        if ($request->for == 'purchase') {
            $products->whereIn('type', ['Simple', 'Variable']);
        } elseif ($request->for == 'ProductDataID') {
            $products->where('type', 'Simple');
        }
        if ($request->type) {
            $products->where('type', $request->type);
        }
        $products = $products->active(100)->get();

        // Output
        $output = array();
        foreach ($products as $product) {
            if ($request->for == 'ProductDataID') {
                if ($product->ProductData) {
                    $output[] = ['id' => $product->ProductData->id, 'text' => ($product->id . ' - ' . $product->title)];
                }
            } else {
                $output[] = ['id' => $product->id, 'text' => ($product->id . ' - ' . $product->title)];
            }
        }

        return response()->json($output);
    }

    public function productDataJson(Request $request)
    {
        $product_data = ProductData::find($request->product_data_id);

        $settings = Info::Settings('general', 'stock_out_can_order');
        if ($settings == 'Yes' || ($product_data && $product_data->stock > 0)) {
            return JsonResponse::withData($product_data);
        }
        return JsonResponse::noData();
    }

    public function reviews()
    {
        $reviews = Review::get();

        return view('back.product.reviews', compact('reviews'));
    }

    public function reviewAction(Review $review, $action)
    {
        $review->status = $action;
        $review->save();

        // Flash Product Reviews/Ratings
        ProductRepo::flashReviewRating($review->product_id);

        return redirect()->back()->with('success-alert', 'Review updated successfully.');
    }
    public function reviewDelete(Review $review)
    {
        $review->delete();

        return redirect()->back()->with('success-alert', 'Review deleted successfully.');
    }

    public function changeFeatured(Request $request)
    {
        $product = Product::find($request->id);
        if ($product) {
            if ($product->featured == 1) {
                $new_val = 0;
            } else {
                $new_val = 1;
            }

            $product->featured = $new_val;
            $product->save();

            return 'success';
        }
        return 'error';
    }

    public function getBundleItem(Request $request)
    {
        $product = Product::find($request->product);

        return view('back.product.extra.bundleItem', compact('product'));
    }
}
