<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'post_id' => 'required|exists:posts,id',
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'body' => 'required|string',
            ]);

            DB::beginTransaction();
            $comment = new Comments($validatedData);
            $comment->save();

            DB::commit();
            return response()->json(['message' => 'Comment created successfully', 'comment' => $comment], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error' => 'An error occurred while creating the comment',
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|max:255',
                'body' => 'sometimes|required|string',
            ]);

            DB::beginTransaction();
            $comment = Comments::findOrFail($id);
            $comment->update($validatedData);

            DB::commit();
            return response()->json(['message' => 'Comment updated successfully', 'comment' => $comment], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error' => 'An error occurred while updating the comment',
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
            DB::beginTransaction();
            $comment = Comments::findOrFail($id);
            $comment->delete();

            DB::commit();
            return redirect()->route('posts.index')->with('success', 'Comments deleted successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error' => 'An error occurred while deleting the comment',
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
