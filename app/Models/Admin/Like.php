<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;
    protected $fillable = [
        'blog_id', 'user_id', 'like'
    ];

    public function blogs(){
        return $this->belongsTo(Blog::class);
    }

    public function users(){
        return $this->belongsTo(User::class);
    }
}
