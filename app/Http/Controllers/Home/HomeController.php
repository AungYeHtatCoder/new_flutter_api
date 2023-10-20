<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Admin\Blog;
use App\Models\Admin\Comment;
use App\Models\Admin\Like;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index(){
        $blogs = Blog::withCount(['likes', 'comments'])->latest()->paginate(9);

        foreach($blogs as $blog){
            $blog->desc = Str::limit($blog->description, 250, '...');
        }
        // return $blogs;

        return view('welcome', compact('blogs'));
    }
    public function search(Request $request){
        if(!$request->search){
            return redirect()->back();
        }
        $search = $request->search;
        $blogs = Blog::where('title', 'like', '%' . $search . '%')->latest()->paginate(9);
        return view('welcome', compact('blogs', 'search'));
    }

    public function like($id){
        $blog = Blog::find($id);
        if(!$blog){
            return redirect()->back()->with('error', "Blog Not Found!");
        }
        $like = Like::where('user_id', Auth::user()->id)->where('blog_id', $id)->first();
        if($like){
            $like->delete();
            return redirect()->back()->with('success', "UnLiked");
        }else{
            Like::create([
                'user_id' => Auth::user()->id,
                'blog_id' => $id,
                'like' => 1
            ]);
            return redirect()->back()->with('success', "Liked");
        }
    }

    public function blogDetail($id){
        $blog = Blog::with(['likes', 'comments'])->withCount(['likes', 'comments'])->findOrFail($id);
        $comments = Comment::with('users')->where('blog_id', $id)->latest()->get();
        // return $comments;
        return view('blog-detail', compact('blog', 'comments'));
    }
    public function comment(Request $request, $id)
{
    $blog = Blog::find($id);
    
    if (!$blog) {
        return redirect()->back()->with('error', "Blog Not Found!");
    }
    
    $request->validate([
        'comment' => 'required'
    ]);
    
    // Check if there is an authenticated user
    if (Auth::check()) {
        Comment::create([
            'blog_id' => $id,
            'user_id' => Auth::user()->id,
            'comment' => $request->comment
        ]);

        return redirect()->back()->with('success', "Comment Created.");
    } else {
        return redirect()->route('home')->with('error', 'You must be logged in to leave a comment.');
    }
}

    //comment section
    // public function comment(Request $request, $id){
    //     $blog = Blog::find($id);
    //     if(!$blog){
    //         return redirect()->back()->with('error', "Blog Not Found!");
    //     }
    //     $request->validate([
    //         'comment' => 'required'
    //     ]);
        
    //     Comment::create([
    //         'blog_id' => $id,
    //         'user_id' => Auth::user()->id,
    //         'comment' => $request->comment
    //     ]);
    //     return redirect()->back()->with('success', "Comment Created.");
    // }



    public function commentEdit(Request $request){
        $request->validate([
            'comment' => 'required'
        ]);
        $comment = Comment::find($request->id);
        if(!$comment){
            return redirect()->back()->with('error', "Comment Not Found!");
        }
        $comment->update([
            'comment' => $request->comment
        ]);
        return redirect()->back()->with('success', "Comment Updated.");
    }

    public function commentDelete(Request $request){
        $comment = Comment::find($request->id);
        if(!$comment){
            return redirect()->back()->with('error', "Comment Not Found!");
        }
        Comment::destroy($request->id);
        return redirect()->back()->with('success', "Comment Deleted.");
    }

    public function login(){
        return view('login');
    }

    public function register(){
        return view('register');
    }
}