<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolePermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->user()->email == 'admin@me.com'){
            return $next($request);
        }

        // Get Permissions
        $role_cache_key = 'user_role_' . auth()->user()->id;
        $role = cache()->remember($role_cache_key, 60*60*24*30, function(){
            return Role::find(auth()->user()->role_id);
        });
        $route_name = $request->route()->getName();
        if($role && in_array($route_name, $role->only_routes_arr)){
            return $next($request);
        }

        abort(403, 'Unauthorized Action!');
    }
}
