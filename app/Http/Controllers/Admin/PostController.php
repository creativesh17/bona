<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use App\Models\Post;
use App\Models\Category;
use App\Notifications\AuthorPostApproved;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $posts = Post::latest()->get();
        return view('admin.post.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.post.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {

        $this->validate($request, [
            'title' => 'required',
            'image' => 'required',
            'categories' => 'required',
            'tags' => 'required',
            'body' => 'required'
        ],[
            'title.required' => 'post title is required'
        ]);

        $slug = Str::slug($request['title'], '-');

        if($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = "post_".$slug."_".time()."_".uniqid().".".$image->getClientOriginalExtension();

            if(!Storage::disk('public')->exists('post')) {
                Storage::disk('public')->makeDirectory('post');
            }

            $manager = new ImageManager(Driver::class);
            $postImage = $manager->read($image)->resize(1600, 1066)->toJpeg();
            Storage::disk('public')->put('post/'.$imageName, $postImage);

        } else {
            $imageName = 'default.png';
        }

        $post = new Post();
        $post->user_id = Auth::id();
        $post->title = $request->title;
        $post->slug = $slug;
        $post->image = $imageName;
        $post->body = $request->body;
        if(isset($request->status)) {
            $post->status = true;
        } else {
            $post->status = false;
        }
        $post->is_approved = true;
        $post->save();

        $post->categories()->attach($request->categories);
        $post->tags()->attach($request->tags);

        if($post) {
            Session::flash('success', 'Post Created Successfully');
            return redirect()->route('admin.post.index');
        }else {
            Session::flash('error', 'An Error Occurred');
            return redirect()->route('admin.post.index');
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post) {
        return view('admin.post.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post) {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.post.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post) {
        $this->validate($request, [
            'title' => 'required',
            'image' => 'image',
            'categories' => 'required',
            'tags' => 'required',
            'body' => 'required'
        ],[
            'title.required' => 'post title is required'
        ]);

        $slug = Str::slug($request['title'], '-');

        if($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = "post_".$slug."_".time()."_".uniqid().".".$image->getClientOriginalExtension();

            if(!Storage::disk('public')->exists('post')) {
                Storage::disk('public')->makeDirectory('post');
            }
            // delete old image
            if(Storage::disk('public')->exists('post/'.$post->image)) {
                Storage::disk('public')->delete('post/'.$post->image);
            }

            $manager = new ImageManager(Driver::class);
            $postImage = $manager->read($image)->resize(1600, 1066)->toJpeg();
            Storage::disk('public')->put('post/'.$imageName, $postImage);

        } else {
            $imageName = $post->image;
        }

        $post->user_id = Auth::id();
        $post->title = $request->title;
        $post->slug = $slug;
        $post->image = $imageName;
        $post->body = $request->body;
        if(isset($request->status)) {
            $post->status = true;
        } else {
            $post->status = false;
        }
        $post->is_approved = true;
        $post->save();

        $post->categories()->sync($request->categories);
        $post->tags()->sync($request->tags);

        if($post) {
            Session::flash('success', 'Post Updated Successfully');
            return redirect()->route('admin.post.index');
        }else {
            Session::flash('error', 'An Error Occurred');
            return redirect()->route('admin.post.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post) {
        if(Storage::disk('public')->exists('post/'.$post->image)) {
            Storage::disk('public')->delete('post/'.$post->image);
        }

        $post->categories()->detach();
        $post->tags()->detach();

        $del = $post->delete();

        if($del) {
            Session::flash('success', 'Post Deleted Successfully');
            return redirect()->route('admin.post.index');
        }else {
            Session::flash('error', 'An Error Occurred');
            return redirect()->route('admin.post.index');
        }
    }


    public function pending(Post $post) {
        $posts = Post::where('is_approved', false)->get();
        return view('admin.post.pending', compact('posts'));
    }

    public function approval($id) {

        $post = Post::findOrFail($id);

        if($post->is_approved == false) {
            $post->is_approved = true;
            $post->save();

            $post->user->notify(new AuthorPostApproved($post));

            Session::flash('success', 'Post Approved Successfully');
            return redirect()->route('admin.post.pending', $post->id);
        } else {
            Session::flash('success', 'Already Approved!');
            return redirect()->route('admin.post.pending', $post->id);
        }

    }


}
