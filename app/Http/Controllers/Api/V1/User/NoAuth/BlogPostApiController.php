<?php

namespace App\Http\Controllers\Api\V1\User\NoAuth;

use App\Models\Admin\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\BlogPostResource;

class BlogPostApiController extends Controller
{
    public function index()
    {
        return new BlogPostResource(Blog::with(['users'])->get());
    }
}