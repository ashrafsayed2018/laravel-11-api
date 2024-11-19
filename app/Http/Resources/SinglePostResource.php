<?php

namespace App\Http\Resources;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SinglePostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'post_title' => $this->title,
            'post_content' => $this->content,
            'author' => User::find($this->user_id),
            'comments' => Comment::where('post_id', $this->id)->get(),
            'comment_count' => Comment::where('post_id', $this->id)->count(),
            'like_count' => $this->likes()->count(),
            'published_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
