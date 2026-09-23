<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Middleware\RolePermissionMiddleware;
use App\Models\State;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(RolePermissionMiddleware::class)->only('index', 'statesUpdate');
    }

    public function index(){
        $states = State::where('country_id', 19)->orderBy('name')->get();

        return view('back.locations.index', compact('states'));
    }

    public function statesUpdate($id, Request $request){
        $request->validate([
            'name' => 'required|max:191'
        ]);

        $state = State::findORFail($id);
        $state->name = $request->name;
        $state->save();

        return redirect()->back()->with('success-alert', 'District updated!');
    }
}
