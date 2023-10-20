<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    // protected $fillable = [
    //     'title', 'image', 'image_mime', 'image_size',
    //      'description', 'user_id'
    // ];
    // protected $fillable = ['title', 'image', 'image_mime', 'image_size', 'description', 'user_id'];
protected $fillable = ['title', 'description', 'image', 'image_mime', 'image_size', 'user_id'];


    // Define the "users" relationship as a "belongsTo" relationship
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function likes(){
        return $this->hasMany(Like::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }
}