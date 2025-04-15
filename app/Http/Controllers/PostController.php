<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Post::with(['province', 'user']);

        // Apply province filter
        if ($request->filled('province_id')) {
            $query->where('province_id', $request->province_id);
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(content) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        // Apply sorting
        switch ($request->sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'most_viewed':
                $query->orderBy('views', 'desc');
                break;
            case 'most_liked':
                $query->orderBy('likes', 'desc');
                break;
            default:
                $query->latest();
        }

        $posts = $query->paginate(12)->withQueryString();
        $provinces = Province::orderBy('name')->get();

        return view('posts.index', compact('posts', 'provinces'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'province_id' => 'required|exists:provinces,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload if present
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('post-images', 'public');
        }

        // Create post
        $post = Post::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'province_id' => $validated['province_id'],
            'user_id' => Auth::user()->id,
            'status' => 'pending',
            'image_path' => $imagePath ?? null,
            'views' => 0,
            'likes' => 0,
        ]);

        return redirect()->route('posts.show', $post)
            ->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        // Get viewed posts from session or initialize empty array
        $viewedPosts = session()->get('viewed_posts', []);

        // Check if this post has already been viewed in this session
        if (!in_array($post->id, $viewedPosts)) {
            // Add post ID to viewed posts array and save to session
            $viewedPosts[] = $post->id;
            session()->put('viewed_posts', $viewedPosts);

            // Increment the view count
            $post->increment('views');
        }

        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'province_id' => 'required|exists:provinces,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload if present
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($post->image_path) {
            $oldImagePath = str_replace(asset('storage/'), '', $post->image_path);
            Storage::disk('public')->delete($oldImagePath);
            }

            // Store the new image
            $imagePath = $request->file('image')->store('post-images', 'public');
            $validated['image_path'] = asset('storage/' . $imagePath);
        }

        $post->update($validated);

        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Delete the image file if it exists
        if ($post->image_path) {
            $imagePath = str_replace(asset('storage/'), '', $post->image_path);
            Storage::disk('public')->delete($imagePath);
        }

        // Delete the post
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }

    // public function resolve(Request $request, Post $post)
    // {
    //     $user = Auth::user();

    //     if ($user->role !== 'staff' || $user->province_id !== $post->province_id) {
    //         abort(403);
    //     }

    //     $post->update([
    //         'status' => 'resolved',
    //         'staff_note' => $request->input('staff_note')
    //     ]);

    //     return back();
    // }

    public function like(Post $post)
    {
        // Get current post likes from session or initialize empty array
        $likedPosts = session()->get('liked_posts', []);

        // Check if this post has already been liked in this session
        if (!in_array($post->id, $likedPosts)) {
            // Add post ID to liked posts array and save to session
            $likedPosts[] = $post->id;
            session()->put('liked_posts', $likedPosts);

            // Increment the like count
            $post->increment('likes');

            return redirect()->back()->with('success', 'Post liked successfully!');
        }

        return redirect()->back()->with('info', 'You have already liked this post.');
    }
}
