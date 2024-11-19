<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class CommentController extends Controller
{
    
    public function store(Request $request)
    {
         try {
            
             $request->validate([
                 'content' => 'required|string',
                 'post_id' => 'required|integer'
             ]);
            $comment = Comment::create([
                'content' => $request->content,
                'post_id' => $request->post_id,
                'user_id' => auth()->user()->id
            ]);
             if($comment){
                 return response()->json([
                     'status' => 'success',
                     'message' => 'Comment created successfully',
                 ], 201);
             } else {
                 return response()->json([
                     'status' => 'error',
                     'message' => 'Failed to create comment',
                 ], 500);
             }

         } catch (\Exception $th) {
             
             return response()->json([
                 'status' => 'error',
                 'message' => $th->getMessage(),
             ], 500);
         }
    }

    // update comment
    public function update(Request $request, $id)
    {
        try {
            // Find the comment by ID
            $comment = Comment::findOrFail($id);
  
            // Validate the request
            $validatedData = $request->validate([
                'content' => 'required|string',
            ]);
  
            // Update the comment
            $comment->content = $validatedData['content'];
            $comment->save();
  
            // Return a successful response
            return response()->json([
                'status' => 'success',
                'message' => 'Comment updated successfully',
            ], 200);
        } catch (\Exception $e) {
            // Return an error response
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // delete comment
    public function destroy($id)
    {
        try {
            // Find the comment by ID
            $comment = Comment::findOrFail($id);
  
            // Delete the comment
            $comment->delete();
  
            // Return a successful response
            return response()->json([
                'status' => 'success',
                'message' => 'Comment deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            // Return an error response
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
