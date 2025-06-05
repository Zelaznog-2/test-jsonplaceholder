<?php

namespace App\Http\Controllers;

use App\Models\Posts;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $downloadComments = asset('exports/user_comment_counts.json');
        $posts = Posts::get(['id', 'user_id', 'title', 'body']);
        $posts->each(function ($post) {
            $post->username = $post->user->name ?? 'Unknown User';
            $post->totalComments = $post->comments()->count();
        });

        return inertia()->render('Posts/Index', [
            'posts' => $posts,
            // 'downloadComments' => $downloadComments,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $post = null;
        $users = User::get(['id', 'name']);
        return inertia()->render('Posts/Page', [
            'post' => $post,
            'users' => $users,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'title' => 'required|string|max:255',
                'body' => 'required|string',
                'isRedirect' => 'sometimes|boolean',
            ]);

            DB::beginTransaction();
            $post = new Posts($validatedData);
            $post->save();

            DB::commit();

            if (isset($validatedData['isRedirect']) && $validatedData['isRedirect']) {
                return redirect()->route('posts.index');
            }

            return response()->json(['message' => 'Post created successfully', 'post' => $post], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error' => 'An error occurred while creating the post',
                'message' => $th->getMessage(),
            ], 500);
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
    public function edit(string $id)
    {
        try {
            $post = Posts::findOrFail($id);
            $post->comments;
            $users = User::get(['id', 'name']);
            return inertia()->render('Posts/Page', [
                'post' => $post,
                'users' => $users,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Post not found',
                'message' => $th->getMessage(),
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $post = Posts::findOrFail($id);
            $validatedData = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'body' => 'sometimes|required|string',
                'user_id' => 'sometimes|required|exists:users,id',
                'isRedirect' => 'sometimes|boolean',
            ]);

            DB::beginTransaction();
            $post->update($validatedData);

            DB::commit();

            if (isset($validatedData['isRedirect']) && $validatedData['isRedirect']) {
                return redirect()->route('posts.index');
            }

            return response()->json(['message' => 'Post updated successfully', 'post' => $post], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error' => 'An error occurred while updating the post',
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $post = Posts::findOrFail($id);
            DB::beginTransaction();
            $post->delete();
            DB::commit();

            return redirect()->route('posts.index')->with('success', 'Post deleted successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error' => 'An error occurred while deleting the post',
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Download the comments count file.
     *
     * @return void
     */
    public function downloadComments()
    {
        $filePath = storage_path('app/public/exports/user_comment_counts.json');
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return response()->download($filePath, 'user_comment_counts.json', [
            'Content-Type' => 'application/json',
        ]);
    }
}
