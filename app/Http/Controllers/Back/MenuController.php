<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Middleware\RolePermissionMiddleware;
use App\Models\Blog;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Product\Brand;
use App\Models\Product\Category;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(RolePermissionMiddleware::class)->only('index', 'store', 'storeMenuItem', 'menuItemPosition', 'destroy', 'destroyItem', 'editItemAjax', 'updateItem', 'category');
    }

    public function index(Request $request){
        $menus = Menu::latest('id')->get();
        $pages = Page::active()->get();
        $blogs = Blog::active()->get();
        $product_categories = Category::where('category_id', null)->where('for', 'product')->active()->get();
        $brands = Brand::active()->get();

        // Menu Items
        if($request->menu){
            $menu_items = MenuItem::where('menu_id', $request->menu)->where('menu_item_id', null)->orderBy('position')->get();
        }else{
            $menu_items = [];
        }

        return view('back.frontend.menus.index', compact('menus', 'pages', 'blogs', 'menu_items', 'product_categories', 'brands'));
    }

    public function store(Request $request){
        $request->validate([
            'menu_name' => 'required|max:255'
        ]);

        $menu = new Menu;
        $menu->name = $request->menu_name;
        $menu->save();

        return redirect()->back()->with('success-alert', 'Menu created successful.');
    }

    public function storeMenuItem(Request $request){
        if(!$request->menu_id){
            return redirect()->back()->with('error-alert', 'Please select a menu!');
        }

        if($request->relation_with == 'custom_link'){$v_data = [
                'url' => 'required|max:255',
                'text' => 'required|max:255'
            ];
        }else{
            $v_data = [
                'relation_with' => 'required',
                'relation_id' => 'required'
            ];
        }

        $request->validate($v_data);

        // dd($request->menu_id);

        if($request->relation_with == 'custom_link'){
            $menu = new MenuItem;
            $menu->menu_id = $request->menu_id;

            $menu->url = $request->url;
            $menu->text = $request->text;
            $menu->save();
        }else{
            foreach((array)$request->relation_id as $relation_id){
                $menu = new MenuItem;
                $menu->menu_id = $request->menu_id;

                $menu->relation_with = $request->relation_with;
                $menu->relation_id = $relation_id;
                $menu->save();
            }
        }


        return redirect()->back()->with('success-alert', 'Item addedd successful.');
    }

    // Menu Item Position
    public function menuItemPosition(Request $request){
        $request->validate([
            'menu_item_json' => 'required'
        ]);

        $menu_items = json_decode($request->menu_item_json);

        foreach($menu_items as $key => $menu_item){
            $item_q = MenuItem::find($menu_item->id);
            $item_q->position = $key;
            $item_q->menu_item_id = null;
            $item_q->save();

            // dd($item->children);
            if(isset($menu_item->children)){
                foreach($menu_item->children as $key => $children_1){
                    $item_2_q = MenuItem::find($children_1->id);
                    $item_2_q->position = $key;
                    $item_2_q->menu_item_id = $item_q->id;
                    $item_2_q->save();

                    if(isset($children_1->children)){
                        foreach($children_1->children as $key => $children_2){
                            $item_3_q = MenuItem::find($children_2->id);
                            $item_3_q->position = $key;
                            $item_3_q->menu_item_id = $item_2_q->id;
                            $item_3_q->save();

                            if(isset($children_2->children)){
                                foreach($children_2->children as $key => $children_3){
                                    $item_4_q = MenuItem::find($children_3->id);
                                    $item_4_q->position = $key;
                                    $item_4_q->menu_item_id = $item_3_q->id;
                                    $item_4_q->save();

                                    if(isset($children_3->children)){
                                        foreach($children_3->children as $key => $children_4){
                                            $item_5_q = MenuItem::find($children_4->id);
                                            $item_5_q->position = $key;
                                            $item_5_q->menu_item_id = $item_4_q->id;
                                            $item_5_q->save();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return redirect()->back()->with('success-alert', 'Menu updated successful.');
    }

    public function destroy(Menu $menu){
        $menu->delete();

        return redirect()->route('back.menus.index')->with('success-alert', 'Menu deleted successful.');
    }

    public function destroyItem(MenuItem $menu_item){
        $menu_item->delete();

        return redirect()->back()->with('success-alert', 'Menu item deleted successful.');
    }

    public function editItemAjax(Request $request){
        $menu_item = MenuItem::find($request->item_id);

        if($menu_item){
            return [
                'status' => true,
                'data' => $menu_item
            ];
        }

        return ['status' => false];
    }

    public function updateItem(Request $request){
        $menu_item = MenuItem::findOrFail($request->item_id);
        $menu_item->url = $request->url;
        $menu_item->text = $request->text;

        $menu_item->save();

        return redirect()->back()->with('success-alert', 'Menu item updated successful.');
    }

    public function category(){
        $categories = Category::where('category_id', null)->where('status', 1)->orderBy('position')->get();

        return view('back.frontend.menus.category', compact('categories'));
    }
}
