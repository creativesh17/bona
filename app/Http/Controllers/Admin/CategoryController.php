<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $categories = Category::latest()->get();
        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required|unique:categories',
            'image' => 'required|mimes:jpg,bmp,png,jpeg',
        ],[
            'name.required' => 'Category Name is Required'
        ]);

        // php artisan storage:link 17:00
        $slug = Str::slug($request['name'], '-');

        if($request->hasFile('image')) {
            // $currentDate = Carbon::now()->toDateString();
            // $imagename = $slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();
            $image = $request->file('image');
            $imageName = "category_".$slug."_".time()."_".uniqid().".".$image->getClientOriginalExtension();


            // check category dir exists
            if(!Storage::disk('public')->exists('category')) {
                Storage::disk('public')->makeDirectory('category');
            }
            // resize image for catgory

            $manager = new ImageManager(Driver::class);
            $category = $manager->read($image)->resize(1600, 479)->toJpeg();
            Storage::disk('public')->put('category/'.$imageName, $category);

            // check category slider dir exists
            if(!Storage::disk('public')->exists('category/slider')) {
                Storage::disk('public')->makeDirectory('category/slider');
            }
            // resize image for catgory
            $slider = $manager->read($image)->resize(500, 333)->toJpeg();
            Storage::disk('public')->put('category/slider/'.$imageName, $slider);

        } else {
            $imageName = 'default.png';
        }

        $category = new Category();
        $category->name = $request->name;
        $category->slug = $slug;
        $category->image = $imageName;
        $category->save();

        if($category) {
            Session::flash('success', 'Category Created Successfully');
            return redirect()->route('admin.category.index');
        }else {
            Session::flash('error', 'An Error Occurred');
            return redirect()->route('admin.category.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {
        $category = Category::find($id);
        return view('admin.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        $this->validate($request, [
            'name' => 'required',
            'image' => 'mimes:jpg,bmp,png,jpeg',
        ],[
            'name.required' => 'Category Name is Required'
        ]);

        $category = Category::find($id);
        $slug = Str::slug($request['name'], '-');

        if($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = "category_".$slug."_".time()."_".uniqid().".".$image->getClientOriginalExtension();


            // check category dir exists
            if(!Storage::disk('public')->exists('category')) {
                Storage::disk('public')->makeDirectory('category');
            }
            // delete old image
            if(Storage::disk('public')->exists('category/'.$category->image)) {
                Storage::disk('public')->delete('category/'.$category->image);
            }

            // resize image for catgory
            $manager = new ImageManager(Driver::class);
            $categoryImage = $manager->read($image)->resize(1600, 479)->toJpeg();
            Storage::disk('public')->put('category/'.$imageName, $categoryImage);

            // check category slider dir exists
            if(!Storage::disk('public')->exists('category/slider')) {
                Storage::disk('public')->makeDirectory('category/slider');
            }

            // delete old image
            if(Storage::disk('public')->exists('category/slider/'.$category->image)) {
                Storage::disk('public')->delete('category/slider/'.$category->image);
            }

            // resize image for catgory
            $slider = $manager->read($image)->resize(500, 333)->toJpeg();
            Storage::disk('public')->put('category/slider/'.$imageName, $slider);

        } else {
            $imageName = $category->image;
        }

        $category->name = $request->name;
        $category->slug = $slug;
        $category->image = $imageName;
        $category->save();

        if($category) {
            Session::flash('success', 'Category Updated Successfully');
            return redirect()->route('admin.category.index');
        }else {
            Session::flash('error', 'An Error Occurred');
            return redirect()->route('admin.category.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        $category = Category::find($id);

        if(Storage::disk('public')->exists('category/'.$category->image)) {
            Storage::disk('public')->delete('category/'.$category->image);
        }
        if(Storage::disk('public')->exists('category/slider/'.$category->image)) {
            Storage::disk('public')->delete('category/slider/'.$category->image);
        }

        // $del = Category::where('cate_status', 1)->where('cate_id', $id)->delete();
        $del = $category->delete();

        if($del) {
            Session::flash('success', 'Category Deleted Successfully');
            return redirect()->route('admin.category.index');
        }else {
            Session::flash('error', 'An Error Occurred');
            return redirect()->route('admin.category.index');
        }
    }
}
