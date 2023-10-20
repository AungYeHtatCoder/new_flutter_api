<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use App\Models\Admin\Banner;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Http\Requests\BannerRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class BannerController extends Controller
{
    public function index(){
        $banners = Banner::latest()->get();
        return view('Admin.banners.index', compact('banners'));
    }

    public function create(){
        return view('Admin.banners.create');
    }
    
    public function store(BannerRequest $request)
{
    //dd($request->all());
    $data = $request->validated();

    // Check if a new image has been uploaded
    $newImage = $request->file('image');

    if ($newImage) {
        $mainFolder = 'banners/' . Str::random();
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
    }

    Banner::create($data);

    return redirect('/admin/banners/')->with('success', 'Banner Created.');
}

    // public function store(BannerRequest $request){
    //     $data = $request->validated();
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
    //     //$image->save($data);
    //     Banner::create($data);

    //     return redirect('/admin/banners/')->with('success', "Banner Created.");
    // }

    public function view($id){
        $banner = Banner::find($id);
        return view('Admin.banners.view', compact('banner'));
    }

    public function edit($id){
        $banner = Banner::find($id);
        return view('Admin.banners.edit', compact('banner'));
    }
    public function update(BannerRequest $request, $id)
{
    // Find the Banner model by its ID or throw a 404 error if not found
    $banner = Banner::findOrFail($id);

    $data = $request->validated();

    // Check if a new image has been uploaded
    $newImage = $request->file('image');

    if ($newImage) {
        $mainFolder = 'banners/' . Str::random();
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
        if ($banner->image) {
            // Extract the relative path from the full URL.
            $oldImagePath = str_replace(URL::to('/'), '', $banner->image);
            Storage::deleteDirectory(dirname($oldImagePath));
        }
    }

    // Update the banner record with new data
    $banner->update($data);

    // Redirect back with a success message
    return redirect('/admin/banners/')->with('success', 'Banner Updated.');
}
//     public function update(BannerRequest $request, Banner $banner)
// {
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

//         // If there is an old image, delete it
//         if ($banner->image) {
//             // Extract the relative path from the full URL.
//             $oldImagePath = str_replace(URL::to('/'), '', $banner->image);
//             Storage::deleteDirectory(dirname($oldImagePath));
//         }
//     }

//     // Update the banner record with new data
//     $banner->update($data);

//     return redirect('/admin/banners/')->with('success', 'Banner Updated.');
// }

    // public function update(BannerRequest $request, Banner $banner){
    //      $data = $request->validated();

    // /** @var \Illuminate\Http\UploadedFile $uploadedImage */
    // $uploadedImage = $data['image'] ?? null;

    // // Check if image was given and save on local file system
    // if ($uploadedImage) {
    //     $relativePath = $this->saveImage($uploadedImage);
    //     $data['image'] = URL::to(Storage::url($relativePath));
    //     $data['image_mime'] = $uploadedImage->getClientMimeType();
    //     $data['image_size'] = $uploadedImage->getSize();

    //     // If there is an old image, delete it
    //     if ($banner->image) {
    //         // Extract the relative path from the full URL.
    //         $oldImagePath = str_replace(URL::to('/'), '', $banner->image);
    //         Storage::deleteDirectory(dirname($oldImagePath));
    //     }
    // }
    
    // $banner->update($data);
    // return redirect('/admin/banners/')->with('success', "Banner Updated.");
      // }

    public function statusChange(Request $request, $id){
        $request->validate([
            'status' => 'required'
        ]);
        $banner = Banner::find($id);
        if(!$banner){
            return redirect()->back()->with('error', "Banner Not Found!");
        }
        $banner->update([
            'status' => $request->status
        ]);
        return redirect('/admin/banners/')->with('success', "Banner Status Updated.");
    }

    //delete
    public function delete(Request $request){
        $id = $request->id;
        $banner = Banner::find($id);
        if(!$banner){
            return redirect()->back()->with('error', "Banner Not Found!");
        }
        //image delelte
        File::delete(public_path('assets/img/banners/' . $banner->image));
        Banner::destroy($id);
        return redirect()->back()->with('success', 'Banner Removed.');
    }

    public function saveImage(UploadedFile $image)
    {
        $path = 'banner_image/' . Str::random();
        //$path = 'images/product_image';

        if (!Storage::exists($path)) {
            Storage::makeDirectory($path, 0755, true);
        }
        if (!Storage::putFileAS('public/' . $path, $image, $image->getClientOriginalName())) {
            throw new \Exception("Unable to save file \"{$image->getClientOriginalName()}\"");
        }

        return $path . '/' . $image->getClientOriginalName();
    }
}