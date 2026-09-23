<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;
use Info;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct() {
        $this->middleware(function ($request, $next) {
            if(auth()->check()){
                if(auth()->user()->email == 'admin@me.com'){
                    $role = cache()->remember('super_admin_role', 60*60*24*30, function(){
                        return Info::getSuperRole();
                    });

                    $role_routes = $role['routes'] ?? [];
                    $role_groups = $role['groups'] ?? [];
                }else{
                    $role_cache_key = 'user_role_' . auth()->user()->id;
                    $role = cache()->remember($role_cache_key, 60*60*24*30, function(){
                        return Role::find(auth()->user()->role_id);
                    });

                    $role_routes = $role->only_routes_arr ?? [];
                    $role_groups = $role->groups_arr ?? [];
                }
            }else{
                $role_routes = [];
                $role_groups = [];
            }

            View::share ('role_routes', $role_routes);
            View::share ('role_groups', $role_groups);

            return $next($request);
        });

        $data = cache()->remember('general_settings', (60 * 60 * 24 * 90), function()
        {
            return Info::SettingsGroupKey('general');
        });
        View::share ('settings_g', $data);
    }

}
