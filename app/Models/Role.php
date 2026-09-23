<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    public function getRoutesArrAttribute(){
        return json_decode($this->routes);
    }

    public function getOnlyRoutesArrAttribute(){
        $route_arr = [];

        foreach($this->routes_arr as $route){
            $route_explode = explode('||', $route);
            foreach($route_explode as $route_explode_item){
                $route_arr[] = $route_explode_item;
            }
        }

        return (array)$route_arr;
    }

    public function getGroupsArrAttribute(){
        return (array)json_decode($this->groups);
    }
}
