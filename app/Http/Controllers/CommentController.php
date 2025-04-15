<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::with('user', 'post')->latest()->paginate(20);
        return view('comments.index', compact('comments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $postId = null)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        // get id from route param kalo gaada di req
        $postId = $postId ?: $request->input('post_id');
        $post = Post::findOrFail($postId);

        Comment::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        // return redirect()->route('posts.show', $post)->with('success', 'Comment added successfully!');
        return redirect()->back()->with('success', 'Comment added successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        // kalo komen sendiri & admin/staff boleh hapus
        if (Auth::id() !== $comment->user_id && !in_array(Auth::user()->role, ['admin', 'staff'])) {
            return redirect()->back()->with('error', 'You are not authorized to delete this comment.');
        }

        $post = $comment->post;
        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully!');
    }
}
