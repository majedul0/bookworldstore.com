<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Widget;
use Illuminate\Http\Request;

class FooterWidgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus = Menu::latest('id')->get();
        $widgets = Widget::orderBy('position')->get();

        return view('back.frontend.footer-widgets.index', compact('menus', 'widgets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'type' => 'required',
            'position' => 'required'
        ]);

        $widget = new Widget;
        $widget->title = $request->title;
        $widget->type = $request->type;
        $widget->position = $request->position;
        $widget->text = $request->text;
        $widget->menu_id = $request->menu;
        $widget->placement = 'Footer';
        $widget->save();

        return redirect()->back()->with('success-alert', 'Widget created successful.');
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
    public function edit($id)
    {
        $menus = Menu::latest('id')->get();
        $widget = Widget::findOrFail($id);

        return view('back.frontend.footer-widgets.edit', compact('menus', 'widget'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $widget = Widget::findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            'type' => 'required',
            'position' => 'required'
        ]);

        $widget->title = $request->title;
        $widget->type = $request->type;
        $widget->position = $request->position;
        $widget->text = $request->text;
        $widget->menu_id = $request->menu;
        $widget->save();

        return redirect()->route('back.footer-widgets.index')->with('success-alert', 'Widget updated successful.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $widget = Widget::findOrFail($id);
        $widget->delete();

        return redirect()->route('back.footer-widgets.index')->with('success-alert', 'Widget deleted successful.');
    }
}
