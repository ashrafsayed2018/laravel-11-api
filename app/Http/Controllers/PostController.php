<?php

namespace App\Http\Controllers;

use App\Http\Resources\SinglePostResource;
use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class PostController extends Controller
{
    

    public function index()
    {
        try {
            // Get all posts
            $posts = Post::all();
    
            if($posts->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'No posts found',
                ], 200);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Posts retrieved successfully',
                    'data' => $posts,
                ], 200);
            }
    
          
        } catch (\Exception $e) {
            // Return an error response if something goes wrong
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve posts',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function slug($string, $separator = '-') {
        if (is_null($string)) {
            return "";
        }
    
        $string = trim($string);
    
        $string = mb_strtolower($string, "UTF-8");;
    
        $string = preg_replace("/[^a-z0-9_\sءاأإآؤئبتثجحخدذرزسشصضطظعغفقكلمنهويةى]#u/", "", $string);
    
        $string = preg_replace("/[\s-]+/", " ", $string);
    
        $string = preg_replace("/[\s_]/", $separator, $string);
    
        return $string;
    }
    public function store(Request $request)
    {

        try {
              // Validate the request
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
            // Create the post with mass assignment
            $post = Post::create([
                'title' => $validatedData['title'],
                'content' => $validatedData['content'],
                'slug' => $this->slug($validatedData['title']), // Generate slug from title
                'user_id' => auth()->user()->id, // Get the currently authenticated user's ID
            ]);
    
            // Return a successful response with the created post data
            return response()->json([
                'status' => 'success',
                'message' => 'Post created successfully',
            ], 201);
    
        } catch (\Exception $e) {
            // Return an error response if something goes wrong
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create post',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    // show post 

    public function show($id)
    {
        try {
            // Find the post by ID or throw a 404 error if not found
            // $post = Post::with(['user', 'comments','likes'])->findOrFail($id);

            $post = new SinglePostResource(Post::findOrFail($id));

            // If found, return the post
            return response()->json([
                'status' => 'success',
                'message' => 'Post retrieved successfully',
                'data' => $post,
            ], 200);
            
        } catch (ModelNotFoundException $e) {
            // Handle the case where the post is not found
            return response()->json([
                'status' => 'error',
                'message' => 'Post not found',
            ], 404);
            
        } catch (\Exception $e) {
            // Handle any other exceptions
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve post',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    // update post

    public function update(Request $request, $id)
    {

        try {
            // Find the post by ID
            $post = Post::findOrFail($id);
      
            // Validate the request; use 'sometimes' for partial updates
            $validatedData = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'content' => 'sometimes|required|string',
            ]);
    
            // Update only the fields that are present in the request
            if (isset($validatedData['title'])) {
                $post->title = $validatedData['title'];
                $post->slug = $this->slug($validatedData['title']); // Generate slug from title
            }
            
            if (isset($validatedData['content'])) {
                $post->content = $validatedData['content'];
            }
    
            // Optionally, you may not need to update the user_id if it's not changing
            $post->user_id = auth()->user()->id; // Keep user_id as is if not changing
    
            // Save the updated post
            $post->save();
    
            // Return a successful response with the updated post data
            return response()->json([
                'status' => 'success',
                'message' => 'Post updated successfully',
                'data' => $post, // Include the updated post data
            ], 200);
    
        } catch (\Exception $e) {
            // Return an error response if something goes wrong
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update post',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    // delete post
    public function destroy($id)
    {
        try {
            // Find the post by ID
            $post = Post::findOrFail($id);
    
            // Delete the post
            $post->delete();
    
            // Return a successful response
            return response()->json([
                'status' => 'success',
                'message' => 'Post deleted successfully',
            ], 200);
    
        } catch (\Exception $e) {
            // Return an error response if something goes wrong
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete post',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
}
