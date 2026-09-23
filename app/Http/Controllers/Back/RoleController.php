<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Middleware\RolePermissionMiddleware;
// use App\Http\Middleware\RolePermission;
use App\Models\Role;
use Illuminate\Http\Request;
use Info;
use Illuminate\Support\Facades\Artisan;

class RoleController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(RolePermissionMiddleware::class)->only('create', 'store', 'index', 'edit', 'update', 'destroy');
    }

    public function __destruct()
    {
        Artisan::call('cache:clear');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::orderBy('name')->get();

        return view('back.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $items = Info::routesItems();

        return view('back.roles.create', compact('items'));
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
            'name' => 'required|max:255',
            'route' => 'required'
        ]);

        $routes = (array)$request->route;

        if(!count($routes)){
            return redirect()->back()->with('error', 'Please select some checkbox!');
        }

        $route_arr = array();
        $group_arr = array();
        foreach($routes as $route){
            $route_explode = explode('::', $route);

            $route_arr[] = $route_explode[1];
            $group_arr[] = $route_explode[0];
        }
        $group_arr = array_unique($group_arr);

        $role = new Role;
        $role->name = $request->name;
        $role->routes = json_encode($route_arr);
        $role->groups = json_encode($group_arr);
        $role->save();

        return redirect()->route('back.roles.index')->with('success', 'Role created successfully');
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
    public function edit(Role $role)
    {
        $items = Info::routesItems();

        // $items_arr = array();
        // foreach($items as $item){
        //     foreach($item['routes'] as $route){
        //         $route_explode = explode('||', $route['route']);

        //         foreach($route_explode as $route_explode_item){
        //             $items_arr[] = $route_explode_item;
        //         }
        //     }
        //     // $route_explode = explode('||', $item['']);
        //     // foreach($route_explode as $route_explode_item){
        //     //     $items_arr[] = $route_explode_item;
        //     // }
        // }

        return view('back.roles.edit', compact('role', 'items'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|max:255',
            'route' => 'required'
        ]);

        $routes = (array)$request->route;

        if(!count($routes)){
            return redirect()->back()->with('error', 'Please select some checkbox!');
        }

        $route_arr = array();
        $group_arr = array();
        foreach($routes as $route){
            $route_explode = explode('::', $route);

            $route_arr[] = $route_explode[1];
            $group_arr[] = $route_explode[0];
        }
        $group_arr = array_unique($group_arr);

        $role->name = $request->name;
        $role->routes = json_encode($route_arr);
        $role->groups = json_encode($group_arr);
        $role->save();

        return redirect()->back()->with('success', 'Role update successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('back.roles.index')->with('success', 'Role deleted successfully.');
    }
}
