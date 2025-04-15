<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\PostExport;
use Maatwebsite\Excel\Facades\Excel;

class AdminPostController extends Controller
{
    // alternating staff -- admin
    protected function getRoutePrefix()
    {
        if (Auth::user()->role === 'admin') {
            return 'admin';
        } else {
            return 'staff';
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Post::with(['user', 'province']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(content) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('province_id')) {
            $query->where('province_id', $request->province_id);
        }

        // staff regional limit
        if (Auth::user()->role === 'staff' && Auth::user()->province_id) {
            $query->where('province_id', Auth::user()->province_id);
        }

        switch ($request->input('sort')) {
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

        $posts = $query->latest()->paginate(10)->withQueryString();

        $prefix = $this->getRoutePrefix();
        return view($prefix . '.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $prefix = $this->getRoutePrefix();
        return view($prefix . '.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'province_id' => 'required|exists:provinces,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:pending,in_progress,resolved',
            'status_note' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // staff regional limit guard
        if (Auth::user()->role === 'staff' && Auth::user()->province_id != $validated['province_id']) {
            return redirect()->back()
                ->withErrors(['province_id' => 'You can only create posts for your assigned province.'])
                ->withInput();
        }

        // image upload
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('post-images', 'public');
        }

        $post = Post::create([
            'user_id' => $validated['user_id'],
            'province_id' => $validated['province_id'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'status' => $validated['status'],
            'status_note' => $validated['status_note'] ?? null,
            'image_path' => $validated['image_path'] ?? null,
            'views' => 0,
            'likes' => 0,
        ]);

        $prefix = $this->getRoutePrefix();
        return redirect()->route($prefix . '.posts.store', $post)
            ->with('success', 'Post created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        // staff regional limit guard
        if (Auth::user()->role === 'staff' && Auth::user()->province_id != $post->province_id) {
            abort(403, 'You can only view posts from your assigned province.');
        }

        $post->load(['user', 'province', 'comments.user']);

        $prefix = $this->getRoutePrefix();
        return view($prefix . '.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        // staff regional limit guard
        if (Auth::user()->role === 'staff' && Auth::user()->province_id != $post->province_id) {
            abort(403, 'You can only edit posts from your assigned province.');
        }

        $prefix = $this->getRoutePrefix();
        return view($prefix . '.posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        if (Auth::user()->role === 'staff' && Auth::user()->province_id != $post->province_id) {
            abort(403, 'You can only update posts from your assigned province.');
        }

        $validated = $request->validate([
            'province_id' => 'required|exists:provinces,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:pending,in_progress,resolved',
            'status_note' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if (Auth::user()->role === 'staff' && $validated['province_id'] != Auth::user()->province_id) {
            return redirect()->back()
                ->withErrors(['province_id' => 'You can only assign posts to your own province.'])
                ->withInput();
        }

        // image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($post->image_path && Storage::disk('public')->exists($post->image_path)) {
                Storage::disk('public')->delete($post->image_path);
            }

            $validated['image_path'] = $request->file('image')->store('post-images', 'public');
        }

        $post->update([
            'province_id' => $validated['province_id'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'status' => $validated['status'],
            'status_note' => $validated['status_note'] ?? null,
            'image_path' => $validated['image_path'] ?? $post->image_path,
        ]);

        $prefix = $this->getRoutePrefix();
        return redirect()->route($prefix . '.posts.show', $post)
            ->with('success', 'Post updated successfully');
    }

    /**
     * Update just the status of a post.
     */
    public function updateStatus(Request $request, Post $post)
    {
        if (Auth::user()->role === 'staff' && Auth::user()->province_id != $post->province_id) {
            abort(403, 'You can only update posts from your assigned province.');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,resolved',
            'status_note' => 'nullable|string',
        ]);

        $post->update([
            'status' => $validated['status'],
            'status_note' => $validated['status_note'] ?? $post->status_note,
        ]);

        return redirect()->back()->with('success', 'Post status updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if (Auth::user()->role === 'staff' && Auth::user()->province_id != $post->province_id) {
            abort(403, 'You can only delete posts from your assigned province.');
        }

        if ($post->image_path && Storage::disk('public')->exists($post->image_path)) {
            Storage::disk('public')->delete($post->image_path);
        }

        // delete comments juga
        $post->comments()->delete();
        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post deleted successfully');
    }

    public function export(Request $request)
    {
        $query = Post::with(['user', 'province']);

        // apply filters (supaya yang di export sama sama yang di index)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(content) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('province_id')) {
            $query->where('province_id', $request->province_id);
        }

        if (Auth::user()->role === 'staff' && Auth::user()->province_id) {
            $query->where('province_id', Auth::user()->province_id);
        }

        $posts = $query->get();

        $fileName = 'posts_' . date('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new PostExport($posts), $fileName);
    }
}
