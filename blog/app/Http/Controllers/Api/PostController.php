<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    // List all posts with pagination
    public function index()
    {
        // Optionally paginate results
        $posts = Post::paginate(10); // Change the number to your preference

        return response()->json([
            'success' => true,
            'data' => $posts,
        ]);
    }

    // Store a new post
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'author' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Optional image field
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create the post using validated data
        $post = Post::create([
            'name' => $request->name,
            'content' => $request->content,
            'author' => $request->author,
            'image' => $request->hasFile('image') ? $request->file('image')->store('images', 'public') : null,
            'date' => now(),  // Use the current timestamp for the date field
        ]);
        
        
        return response()->json([
            'success' => true,
            'data' => $post,
        ], 201); // 201 Created
    }

    // Update an existing post
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'author' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Optional image field
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Update the post with validated data
        $post->name = $request->name;  // Use 'name' instead of 'title'
        $post->content = $request->content;
        $post->author = $request->author;

        // Handle image upload if present
        if ($request->hasFile('image')) {
            $post->image = $request->file('image')->store('images', 'public');
        }

        $post->save();

        return response()->json([
            'success' => true,
            'data' => $post,
        ]);
    }

    // Delete a post
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully.',
        ]);
    }
}
