<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogHomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        // Access the user information associated with the blog post
        $user = $this->users;

        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => $this->image,
            'description' => $this->description,
            'user_id' => $this->user_id,
            'user_name' => $user->name, // Access user's name
            'user_email' => $user->email, // Access user's email
            //'created_at' => $this->created_at,
            // created_at and updated_at are automatically converted to Carbon instances
            // so we can use the diffForHumans() method to return a human-readable time
            'created_at' => $this->created_at->diffForHumans(),
            // 
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}