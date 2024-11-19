<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
  // add or remove like
  public function likeOrUnlike(Request $request, $id)
  {
        try {
            // Find the post by ID
            $post = Post::findOrFail($id);
  
            // Check if the user has already liked the post
            if ($post->likes()->where('user_id', auth()->user()->id)->exists()) {
                // If the user has already liked the post, remove the like
                $post->likes()->where('user_id', auth()->user()->id)->delete();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Post unliked successfully'
                ]);
            } else {
                // If the user has not yet liked the post, add the like
                $post->likes()->create([
                    'user_id' => auth()->user()->id,
                ]);
                      // Return a successful response
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Post liked successfully'
                    ]);
            }
  
      
        } catch (\Exception $e) {
            // Handle the exception and return an error response
            return response()->json([
                'status' => 'error',
                'error' => 'An error occurred while liking the post'
            ], 500);
        }
  }
}
