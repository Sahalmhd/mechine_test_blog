<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PostRequest;  // Import the custom request class


class PostController extends Controller
{
    public function index(Request $request)
    {
        // Start a query builder instance
        $query = Post::query();
    
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($query) use ($searchTerm) {
                $query->where('name', 'LIKE', '%' . $searchTerm . '%')
                      ->orWhere('content', 'LIKE', '%' . $searchTerm . '%');
            });
        }
    
        // Get paginated result
        $posts = $query->paginate(10); // Adjust pagination as needed
    
        // Check if the request is AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('partials.posts_list', compact('posts'))->render(),
            ]);
        }
    
        // Return the view for normal requests
        return view('index', compact('posts'));
    }
    
    // Store a new post
    public function store(PostRequest $request)
    {
      
        $post = new Post();
        $post->name = $request->name;
        $post->author = Auth::user()->name;  // Get the name of the authenticated user using Auth facade
        $post->content = $request->content;
        $post->date = now();  // Use the current timestamp for the date

        if ($request->hasFile('image')) {
            $post->image = $request->file('image')->store('images', 'public');
        }

        $post->save();

        return response()->json([
            'success' => true,
            'post' => $post
        ]);
    }



    // Edit post (for pre-filling the form)
    public function edit($id)
{
    // Fetch the post by ID
    $post = Post::findOrFail($id);

    // Return the post data, including the image URL, in JSON format
    return response()->json([
        'success' => true,
        'post' => [
            'id' => $post->id,
            'name' => $post->name,
            'content' => $post->content,
            'author' => $post->author,
            'date' => $post->created_at->format('Y-m-d'), // Adjust the format as needed
            'image' => $post->image ? asset('storage/' . $post->image) : null, // Full URL to the image, or null if no image
        ]
    ]);
}
// Update a post
public function update(PostRequest $request, $id)
{
    $post = Post::findOrFail($id);

    // Update the fields with validated data
    $post->name = $request->name;
    $post->content = $request->content;
    $post->author = $request->author;  // Get the author from the request (or use the authenticated user if needed)

    // Handle image upload if present
    if ($request->hasFile('image')) {
        $post->image = $request->file('image')->store('images', 'public');
    }

    $post->save(); // Save the updated post

    return response()->json([
        'success' => true,
        'post' => $post,
    ]);
}

    // Delete post
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json([
            'success' => true
        ]);
    }
    
}
