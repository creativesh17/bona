<?php

namespace App\Http\Controllers\Author;

use App\Models\Tag;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Notifications\NewAuthorPost;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $posts = Auth::User()->posts()->latest()->get();
        return view('author.post.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        $categories = Category::all();
        $tags = Tag::all();
        return view('author.post.create', compact('categories', 'tags'));
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
        $post->is_approved = false;
        $post->save();

        $post->categories()->attach($request->categories);
        $post->tags()->attach($request->tags);

        $users = User::where('role_id', 1)->get();
        Notification::send($users, new NewAuthorPost($post));

        if($post) {
            Session::flash('success', 'Post Created Successfully');
            return redirect()->route('author.post.index');
        }else {
            Session::flash('error', 'An Error Occurred');
            return redirect()->route('author.post.index');
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post) {

        if($post->user_id != Auth::id()) {
            Session::flash('error', 'Not Authorized');
            return back();
        }

        return view('author.post.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post) {

        if($post->user_id != Auth::id()) {
            Session::flash('error', 'Not Authorized');
            return back();
        }

        $categories = Category::all();
        $tags = Tag::all();
        return view('author.post.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post) {

        if($post->user_id != Auth::id()) {
            Session::flash('error', 'Not Authorized');
            return back();
        }

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
        $post->is_approved = false;
        $post->save();

        $post->categories()->sync($request->categories);
        $post->tags()->sync($request->tags);

        if($post) {
            Session::flash('success', 'Post Updated Successfully');
            return redirect()->route('author.post.index');
        }else {
            Session::flash('error', 'An Error Occurred');
            return redirect()->route('author.post.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post) {

        if($post->user_id != Auth::id()) {
            Session::flash('error', 'Not Authorized');
            return back();
        }

        if(Storage::disk('public')->exists('post/'.$post->image)) {
            Storage::disk('public')->delete('post/'.$post->image);
        }

        $post->categories()->detach();
        $post->tags()->detach();

        $del = $post->delete();

        if($del) {
            Session::flash('success', 'Post Deleted Successfully');
            return redirect()->route('author.post.index');
        }else {
            Session::flash('error', 'An Error Occurred');
            return redirect()->route('author.post.index');
        }
    }
}
