<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'comments.user', 'likes'])
            ->where('is_diary', false)
            ->latest()
            ->get();

        return view('social.index', compact('posts'));
    }

    public function create()
    {
        return view('social.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'media' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:20480',
            'title' => 'nullable|string|max:255',
        ]);

        $post = new Post();
        $post->user_id = Auth::id();
        $post->content = $request->content;
        $post->title = $request->title;

        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $path = $file->store('posts', 'public');
            $post->media_url = $path;
            $post->media_type = str_starts_with($file->getMimeType(), 'video') ? 'video' : 'image';
        }

        $post->save();

        return redirect()->route('posts.index')->with('success', 'Post created successfully!');
    }

    public function like(int $postId)
    {
        $post = Post::findOrFail($postId);
        
        $existingLike = Like::where('post_id', $postId)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $action = 'unliked';
        } else {
            Like::create([
                'post_id' => $postId,
                'user_id' => Auth::id()
            ]);
            $action = 'liked';
        }

        return response()->json([
            'action' => $action,
            'like_count' => $post->likes()->count()
        ]);
    }

    public function comment(Request $request, int $postId)
    {
        $request->validate([
            'content' => 'required|string|max:500'
        ]);

        $comment = Comment::create([
            'post_id' => $postId,
            'user_id' => Auth::id(),
            'content' => $request->content
        ]);

        $comment->load('user');

        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'content' => $comment->content,
                'user_display_name' => $comment->user->display_name ?? 'Anonymous',
                'created_at' => $comment->created_at->diffForHumans()
            ]
        ]);
    }
}