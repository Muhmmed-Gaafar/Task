<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    public function index()
    {
        $posts = auth()->user()->posts()->withTrashed()->orderBy('pinned', 'desc')->get();
        return PostResource::collection($posts);
    }
    public function store(PostRequest $request)
    {
        $path = $request->file('cover_image')->store('posts');
        $post = auth()->user()->posts()->create([
            'title' => $request->title,
            'body' => $request->body,
            'cover_image' => $path,
            'pinned' => $request->pinned,
        ]);
        $post->tags()->sync($request->tags);
        return new PostResource($post);
    }
    public function show(Post $post)
    {
        $this->authorize('view', $post);
        return new PostResource($post);
    }
    public function update(PostRequest $request, Post $post)
    {
        $this->authorize('update', $post);

        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            Storage::delete($post->cover_image);
            $data['cover_image'] = $request->file('cover_image')->store('posts');
        }

        $post->update($data);
        $post->tags()->sync($request->tags);

        return new PostResource($post);
    }
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();

        return response()->json(['message' => 'Post softly deleted.']);
    }
    public function trashed()
    {
        $posts = auth()->user()->posts()->onlyTrashed()->get();
        return PostResource::collection($posts);
    }
    public function restore($id)
    {
        $post = auth()->user()->posts()->withTrashed()->find($id);
        $this->authorize('restore', $post);
        $post->restore();

        return response()->json(['message' => 'Post restored.']);
    }
}

