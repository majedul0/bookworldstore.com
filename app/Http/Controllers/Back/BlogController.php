<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Product\Category;
use App\Repositories\MediaRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogs = Blog::get();

        return view('back.blog.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('category_id', null)->where('for', 'blog')->active()->get();

        return view('back.blog.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $v_data = [
            'title' => 'required|max:255',
        ];

        if($request->file('image')){
            $v_data['image'] = 'mimes:jpg,png,jpeg,gif';
        }

        $request->validate($v_data);

        $blog = new Blog;
        $blog->title = $request->title;
        $blog->description = $request->description;
        $blog->short_description = $request->short_description;
        $blog->meta_description = $request->meta_description;
        $blog->meta_tags = $request->meta_tags;

        if($request->file('image')){
            $uploaded_file = MediaRepo::upload($request->file('image'));
            $blog->image = $uploaded_file['file_name'];
            $blog->image_path = $uploaded_file['file_path'];
            $blog->media_id = $uploaded_file['media_id'];
        }

        $blog->save();

        foreach((array)$request->categories as $category){
            $blog_category = new BlogCategory;
            $blog_category->blog_id = $blog->id;
            $blog_category->category_id = $category;
            $blog_category->save();
        }

        return redirect()->back()->with('success-alert', 'Blog created successful.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        $categories = Category::where('category_id', null)->where('for', 'blog')->active()->get();
        $blog_categories = $blog->Categories->pluck('id')->toArray();

        return view('back.blog.edit', compact('blog', 'categories', 'blog_categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blog $blog)
    {
        $v_data = [
            'title' => 'required|max:255',
        ];

        if($request->file('image')){
            $v_data['image'] = 'mimes:jpg,png,jpeg,gif';
        }
        $request->validate($v_data);

        $blog->title = $request->title;
        $blog->description = $request->description;
        $blog->short_description = $request->short_description;
        $blog->meta_description = $request->meta_description;
        $blog->meta_tags = $request->meta_tags;
        if($request->file('image')){
            $uploaded_file = MediaRepo::upload($request->file('image'));
            $blog->image = $uploaded_file['file_name'];
            $blog->image_path = $uploaded_file['file_path'];
            $blog->media_id = $uploaded_file['media_id'];
        }

        $blog->save();

        // Update Category
        BlogCategory::whereNotIn('category_id', (array)$request->categories)->where('blog_id', $blog->id)->delete();
        foreach((array)$request->categories as $category){
            $blog_category = BlogCategory::where('blog_id', $blog->id)->where('category_id', $category)->first();

            if(!$blog_category){
                $blog_category = new BlogCategory;
                $blog_category->blog_id = $blog->id;
                $blog_category->category_id = $category;
                $blog_category->save();
            }
        }

        return redirect()->back()->with('success-alert', 'Blog created successful.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        $blog->delete();

        // Delete Menu Relation
        DB::table('menu_items')->where('relation_with', 'blog')->where('relation_id', $blog->id)->delete();

        return redirect()->route('back.blogs.index')->with('success-alert', 'Blog deleted successful.');
    }

    public function categories(){
        $categories = Category::where('category_id', null)->where('for', 'blog')->latest('id')->get();

        return view('back.blog.categories', compact('categories'));
    }

    public function categoriesCreate(){
        $categories = Category::where('for', 'blog')->latest('id')->get();
        return view('back.blog.categoriesCreate', compact('categories'));
    }

    public function removeImage(Blog $blog){
        $blog->image = null;
        $blog->media_id = null;
        $blog->image_path = null;
        $blog->save();

        return redirect()->back()->with('success-alert', 'Image deleted successful.');
    }

    public function categoriesStore(Request $request){
        $v_data = [
            'title' => 'required|max:255',
        ];

        if($request->file('image')){
            $v_data['image'] = 'mimes:jpg,png,jpeg,gif';
        }

        $request->validate($v_data);

        $category = new Category;
        $category->title = $request->title;
        $category->for = 'blog';
        $category->description = $request->description;
        $category->short_description = $request->short_description;
        $category->meta_description = $request->meta_description;
        $category->meta_tags = $request->meta_tags;

        if($request->file('image')){
            $uploaded_file = MediaRepo::upload($request->file('image'));
            $category->image = $uploaded_file['full_file_name'];
            $category->media_id = $uploaded_file['media_id'];
        }

        $category->save();

        return redirect()->back()->with('success-alert', 'Category created successful.');
    }

    public function categoriesEdit($id){
        $category = Category::where('for', 'blog')->findOrFail($id);

        return view('back.blog.categoriesEdit', compact('category'));
    }

    public function categoriesUpdate(Request $request, $id)
    {
        $category = Category::where('for', 'blog')->findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
        ]);

        $category->title = $request->title;
        $category->short_description = $request->short_description;
        $category->description = $request->description;
        $category->meta_description = $request->meta_description;
        $category->meta_tags = $request->meta_tags;

        if($request->file('image')){
            $uploaded_file = MediaRepo::upload($request->file('image'));
            $category->image = $uploaded_file['full_file_name'];
            $category->media_id = $uploaded_file['media_id'];
        }

        $category->save();

        return redirect()->back()->with('success-alert', 'Category updated successful.');
    }

    public function categoriesDestroy($id){
        $category = Category::where('for', 'blog')->findOrFail($id);
        $category->delete();

        return redirect()->route('back.blogs.categories')->with('success-alert', 'Category deleted successful.');
    }
}
