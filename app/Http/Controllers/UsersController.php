<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;



class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $users = User::get(['id', 'name', 'username', 'website',  'email']);
        $users->each(function ($user) {
            $user->totalPosts = $user->posts()->count();
        });

        return inertia()->render('Users/Index', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = null;
        return inertia()->render('Users/Page', [
            'user' => $user,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users',
                'address' => 'nullable',
                'phone' => 'nullable|string|max:30',
                'website' => 'nullable',
                'company' => 'nullable',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'isRedirect' => 'sometimes|boolean',
            ]);

            $validatedData['password'] = bcrypt($validatedData['password']);

            DB::beginTransaction();
            User::create($validatedData);

            DB::commit();
            if (isset($validatedData['isRedirect']) && $validatedData['isRedirect']) {
                return redirect()->route('users.index')->with('success', 'User Created successfully');
            }
            return response()->json(['message' => 'User created successfully'], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error' => 'An error occurred while creating the user',
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
        $user = User::findOrFail($id);
        return inertia()->render('Users/Page', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'username' => 'sometimes|required|string|max:255|unique:users,username,' . $id,
                'address' => 'nullable',
                'phone' => 'nullable|string|max:30',
                'website' => 'nullable',
                'company' => 'nullable',
                'email' => 'sometimes|required|email|unique:users,email,' . $id,
                'password' => 'nullable|string|min:8|confirmed',
                'isRedirect' => 'sometimes|boolean',
            ]);

            if (isset($validatedData['password'])) {
                $validatedData['password'] = bcrypt($validatedData['password']);
            } else {
                unset($validatedData['password']);
            }

            DB::beginTransaction();
            $user = User::findOrFail($id);
            $user->update($validatedData);

            DB::commit();

            if (isset($validatedData['isRedirect']) && $validatedData['isRedirect']) {
                return redirect()->route('users.index')->with('success', 'User updated successfully');
            }
            return response()->json(['message' => 'User updated successfully'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error' => 'An error occurred while updating the user',
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
            $user = User::findOrFail($id);
            $user->delete();
            DB::commit();
            return redirect()->route('users.index')->with('success', 'User deleted successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('users.index')->with('error', 'An error occurred while deleting the user: ' . $th->getMessage());
        }
    }
}
