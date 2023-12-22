<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $tags = Tag::latest()->get();
        return view('admin.tag.index', compact('tags'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        return view('admin.tag.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {

        $this->validate($request, [
            'name' => 'required|unique:tags',
        ]);

        $tag = new Tag();
        $tag->name = $request->name;
        $tag->slug = Str::slug($request->name);
        $tag->created_at = Carbon::now()->toDateTimeString();
        $tag->save();

        if($tag) {
            Session::flash('success', 'Tag Created Successfully');
            return redirect()->route('admin.tag.index');
        }else {
            Session::flash('error', 'An Error Occurred');
            return redirect()->route('admin.tag.index');
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {
        $tag = Tag::find($id);
        return view('admin.tag.edit', compact('tag'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {

        $tag = Tag::find($id);
        $tag->name = $request->name;
        $tag->slug = Str::slug($request->name);
        $tag->save();

        if($tag) {
            Session::flash('success', 'Tag Updated Succesfully!');
            return redirect()->route('admin.tag.index');
        }else {
            Session::flash('error', 'An Error Occurred');
            return redirect()->route('admin.tag.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {

        $tag = Tag::find($id)->delete();

        if($tag) {
            Session::flash('success', 'Tag Deleted Succesfully!');
            return redirect()->back();
        }else {
            Session::flash('error', 'An Error Occurred');
            return redirect()->back();
        }
    }

}
