<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Blog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Http\Requests\BlogRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
class BlogController extends Controller
{
    public function index(){
        $blogs = Blog::with('users')->latest()->get();
        // return $blogs;
        return view('Admin.blogs.index', compact('blogs'));
    }

    public function create(){
        return view('Admin.blogs.create');
    }
    
    public function store(BlogRequest $request)
{
    $data = $request->validated();
    // user_id
    $data['user_id'] = Auth::user()->id;

    /** @var \Illuminate\Http\UploadedFile $image */
    $image = $data['image'] ?? null;

    // Check if image was given and save on local file system
    if ($image) {
        $mainFolder = 'blog_images/' . Str::random(); // Modify the folder structure as needed
        $filename = $image->getClientOriginalName();

        // Store the new image with specified visibility settings
        $path = Storage::putFileAs(
            'public/' . $mainFolder,
            $image,
            $filename,
            [
                'visibility' => 'public',
                'directory_visibility' => 'public'
            ]
        );

        $data['image'] = URL::to(Storage::url($path));
        $data['image_mime'] = $image->getClientMimeType();
        $data['image_size'] = $image->getSize();
    }

    Blog::create($data);

    return redirect('/admin/blogs/')->with('success', 'Blog Created.');
}

    
//     public function store(BannerRequest $request)
// {
//     //dd($request->all());
//     $data = $request->validated();

//     // Check if a new image has been uploaded
//     $newImage = $request->file('image');

//     if ($newImage) {
//         $mainFolder = 'banners/' . Str::random();
//         $filename = $newImage->getClientOriginalName();

//         // Store the new image with specified visibility settings
//         $path = Storage::putFileAs(
//             'public/' . $mainFolder,
//             $newImage,
//             $filename,
//             [
//                 'visibility' => 'public',
//                 'directory_visibility' => 'public'
//             ]
//         );

//         $data['image'] = URL::to(Storage::url($path));
//         $data['image_mime'] = $newImage->getClientMimeType();
//         $data['image_size'] = $newImage->getSize();
//     }

//     Banner::create($data);

//     return redirect('/admin/banners/')->with('success', 'Banner Created.');
// }

    // public function store(BlogRequest $request){
    //     $data = $request->validated();
    //     // user_id
    //     $data['user_id'] = Auth::user()->id;
    //     //dd($data);

    //     /** @var \Illuminate\Http\UploadedFile $image */
    //     $image = $data['image'] ?? null;
    //     // Check if image was given and save on local file system
    //     if ($image) {
    //         $relativePath = $this->saveImage($image);
    //         $data['image'] = URL::to(Storage::url($relativePath));
    //         $data['image_mime'] = $image->getClientMimeType();
    //         $data['image_size'] = $image->getSize();
    //     }
    //     Blog::create($data);
    //     return redirect('/admin/blogs/')->with('success', "Blog Created.");
    // }

    public function view($id){
        $blog = Blog::withCount(['likes', 'comments'])->where('id', $id)->first();
        // return $blog;
        return view('Admin.blogs.view', compact('blog'));
    }

    public function edit($id){
        $blog = Blog::find($id);
        return view('Admin.blogs.edit', compact('blog'));
    }
    
    public function update(Request $request, $id)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'image' => 'nullable|image', // Adjust validation rules as needed
    ]);

    $blog = Blog::findOrFail($id);

    $data = $request->all();

    // Check if a new image has been uploaded
    $newImage = $request->file('image');

    if ($newImage) {
        $mainFolder = 'blog_images/' . Str::random(); // Modify the folder structure as needed
        $filename = $newImage->getClientOriginalName();

        // Store the new image with specified visibility settings
        $path = Storage::putFileAs(
            'public/' . $mainFolder,
            $newImage,
            $filename,
            [
                'visibility' => 'public',
                'directory_visibility' => 'public'
            ]
        );

        $data['image'] = URL::to(Storage::url($path));
        $data['image_mime'] = $newImage->getClientMimeType();
        $data['image_size'] = $newImage->getSize();

        // If there is an old image, delete it
        if ($blog->image) {
            // Extract the relative path from the full URL.
            $oldImagePath = str_replace(URL::to('/'), '', $blog->image);
            Storage::delete($oldImagePath);
        }
    }

    // You can add the user_id here if needed
    $data['user_id'] = Auth::user()->id;
    $blog->update($data);

    return redirect('/admin/blogs/')->with('success', 'Blog Updated.');
}
//     public function update(Request $request, $id)
// {
//     try {
//         // Retrieve the blog using the provided ID
//         $blog = Blog::findOrFail($id);

//         $data = $request->all();
//         $data['user_id'] = Auth::user()->id;

//         // Check if an image has been uploaded
//         if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
//             // Delete the old image if it exists
//             if ($blog->image) {
//                 Storage::delete('public/' . $blog->image);
//             }

//             // Save the new image and retrieve its path
//             $uploadedImage = $data['image']; // Store the uploaded file instance
//             $relativePath = $this->saveImage($uploadedImage);

//             // Update the image data for the blog
//             $data['image'] = URL::to(Storage::url($relativePath));
//             $data['image_mime'] = $uploadedImage->getClientMimeType();
//             $data['image_size'] = $uploadedImage->getSize();
//         }

//         // Use the update() method to update the blog with the new data
//         $blog->update($data);

//         return redirect('/admin/blogs/')->with('success', "Blog Updated.");

//     } catch (\Exception $e) {
//         Log::error("Failed to update blog: " . $e->getMessage());
//         return redirect('/admin/blogs/')->with('error', "Failed to update blog.");
//     }
// }




//     public function update(BlogRequest $request, Blog $blog)
// {
//     try {
//         $data = $request->validated();
//         $data['user_id'] = Auth::user()->id;

//         // If an image has been uploaded
//         if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
//             // If an old image exists, delete it
//             if ($blog->image) {
//                 Storage::delete('public/' . $blog->image);
//             }

//             // Save the new image and get its path
//             $uploadedImage = $data['image']; // Store the instance
//             $relativePath = $this->saveImage($uploadedImage);

//             // Update the image data
//             $data['image'] = URL::to(Storage::url($relativePath));
//             $data['image_mime'] = $uploadedImage->getClientMimeType();
//             $data['image_size'] = $uploadedImage->getSize();
//         }

//         // Update the blog with the new data
//         $blog->fill($data);
//         $blog->save();

//         return redirect('/admin/blogs/')->with('success', "Blog Updated.");

//     } catch (\Exception $e) {
//         Log::error("Failed to update blog: " . $e->getMessage());
//         return redirect('/admin/blogs/')->with('error', "Failed to update blog.");
//     }
// }

//     public function update(BlogRequest $request, Blog $blog)
// {
//     try {
//         $data = $request->validated();
//         $data['user_id'] = Auth::user()->id;

//         $image = $data['image'] ?? null;
        
//         // Handling the image
//         if ($image) {
//             if ($blog->image) {
//                 Storage::delete('public/' . $blog->image);
//             }

//             $relativePath = $this->saveImage($image);
//             $data['image'] = URL::to(Storage::url($relativePath));
//             $data['image_mime'] = $image->getClientMimeType();
//             $data['image_size'] = $image->getSize();
//         }

//         // Explicitly setting title and description
//         $blog->title = $data['title'];
//         $blog->description = $data['description'];

//         if (isset($data['image'])) {
//             $blog->image = $data['image'];
//             $blog->image_mime = $data['image_mime'];
//             $blog->image_size = $data['image_size'];
//         }

//         $blog->user_id = $data['user_id'];

//         // Saving the blog model
//         $blog->save();

//         return redirect('/admin/blogs/')->with('success', "Blog Updated.");

//     } catch (\Exception $e) {
//         Log::error("Failed to update blog: " . $e->getMessage());
//         return redirect('/admin/blogs/')->with('error', "Failed to update blog.");
//     }
// }

    // public function update(BlogRequest $request, Blog $blog)
    // {
    //     //dd($request->all());

    //     $data = $request->validated();
    //     $data['user_id'] = Auth::user()->id;

    //     $image = $data['image'] ?? null;
        
    //     if ($image) {
    //         // Handle old image if exists
    //         if ($blog->image) {
    //             Storage::delete('public/' . $blog->image);
    //         }

    //         $relativePath = $this->saveImage($image);
    //         $data['image'] = URL::to(Storage::url($relativePath));
    //         $data['image_mime'] = $image->getClientMimeType();
    //         $data['image_size'] = $image->getSize();
    //     }

    //     try {
    //         $blog->update($data);
    //         return redirect('/admin/blogs/')->with('success', "Blog Updated.");
    //     } catch (\Exception $e) {
    //         Log::error("Failed to update blog: " . $e->getMessage());
    //         return redirect('/admin/blogs/')->with('error', "Failed to update blog.");
    //     }
    // }

    public function delete(Request $request){
        $id = $request->id;
        $blog = Blog::find($id);
        if(!$blog){
            return redirect()->back()->with('error', "Blog Not Found!");
        }
        Blog::destroy($id);
        return redirect('/admin/blogs/')->with('success', "Blog Removed.");
    }

    public function saveImage(UploadedFile $image)
    {
        $path = 'blog_image/' . Str::random();

        if (!Storage::exists($path)) {
            Storage::makeDirectory($path, 0755, true);
        }

        if (!Storage::putFileAs('public/' . $path, $image, $image->getClientOriginalName())) {
            throw new \Exception("Unable to save file \"{$image->getClientOriginalName()}\"");
        }

        return $path . '/' . $image->getClientOriginalName();
    }
    // public function saveImage(UploadedFile $image)
    // {
    //     $path = 'blog_image/' . Str::random();
    //     //$path = 'images/product_image';

    //     if (!Storage::exists($path)) {
    //         Storage::makeDirectory($path, 0755, true);
    //     }
    //     if (!Storage::putFileAS('public/' . $path, $image, $image->getClientOriginalName())) {
    //         throw new \Exception("Unable to save file \"{$image->getClientOriginalName()}\"");
    //     }

    //     return $path . '/' . $image->getClientOriginalName();
    // }
}