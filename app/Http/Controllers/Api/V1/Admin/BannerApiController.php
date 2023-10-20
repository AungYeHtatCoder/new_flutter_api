<?php

namespace App\Http\Controllers\Api\V1\Admin;

use Illuminate\Support\Str;
use App\Models\Admin\Banner;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Http\Requests\BannerRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage; // Import the Storage facade
use App\Http\Resources\Admin\BannerResource;
use Illuminate\Validation\ValidationException;
class BannerApiController extends Controller
{
    public function index()
    {
        $banners = Banner::all();
        return BannerResource::collection($banners);
    }

    public function show($id)
    {
        $banner = Banner::findOrFail($id);
        return new BannerResource($banner);
    }

    public function store(BannerRequest $request)
{
    // Validate the request and get the validated data
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

    try {
        // Create a new banner record
        $banner = Banner::create($data);
    } catch (\Exception $e) {
        // Handle any database errors
        return response()->json(['message' => 'Banner creation failed'], 500);
    }

    // Return a JSON response indicating success with the created banner data
    return response()->json(['message' => 'Banner Created', 'data' => $banner], 201);
}
//     public function store(BannerRequest $request)
// {
//     $data = $request->validated();

//     /** @var \Illuminate\Http\UploadedFile $image */
//     $image = $data['image'] ?? null;

//     // Check if image was given and save on local file system
//     if ($image) {
//         $relativePath = $this->saveImage($image);
//         $data['image'] = URL::to(Storage::url($relativePath));
//         $data['image_mime'] = $image->getClientMimeType();
//         $data['image_size'] = $image->getSize();
//     }

//     $banner = Banner::create($data);

//     // Return a JSON response
//     return response()->json([
//         'message' => 'Banner Created.',
//         'banner' => $banner
//     ], 201);
// }


//     public function store(Request $request){
//     // Validation
//     $validator = Validator::make($request->all(), [
//         'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
//     ]);

//     if ($validator->fails()) {
//         return response()->json(['error' => $validator->errors()], 401);
//     }

//     // Handling Image
//     $image = $request->file('image');
//     $ext = $image->getClientOriginalExtension();
//     $filename = uniqid('banner') . '.' . $ext;
    
//     // Save the image in a directory in your public folder or any storage disk
//     $path = $image->storeAs('public/assets/img/banners', $filename);  // Update the path as needed

//     // Create new banner
//     $banner = Banner::create([
//         'image' => $filename
//     ]);
//         return response()->json(['success' => 'Banner Created', 'banner' => $banner], 200);

// }
public function update(BannerRequest $request, $id)
{
    $banner = Banner::findOrFail($id); // Find the banner by ID

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

    // Return a JSON response indicating success
    return response()->json(['message' => 'Banner Updated', 'data' => $banner], 200);
}

// public function update(BannerRequest $request, Banner $banner)
// {
//     $data = $request->validated();

//     /** @var \Illuminate\Http\UploadedFile $uploadedImage */
//     $uploadedImage = $data['image'] ?? null;

//     // Check if an image was given and save on local file system
//     if ($uploadedImage) {
//         $relativePath = $this->saveImage($uploadedImage);
//         $data['image'] = URL::to(Storage::url($relativePath));
//         $data['image_mime'] = $uploadedImage->getClientMimeType();
//         $data['image_size'] = $uploadedImage->getSize();

//         // If there is an old image, delete it
//         if ($banner->image) {
//             // Extract the relative path from the full URL.
//             $oldImagePath = str_replace(URL::to('/'), '', $banner->image);
//             Storage::deleteDirectory(dirname($oldImagePath));
//         }
//     }

//     $banner->update($data);

//     // Return a JSON response
//     return response()->json([
//         'message' => 'Banner Updated.',
//         'banner' => $banner
//     ], 200);
// }

//     public function update(Request $request, $id)
// {
//     // Validation rules for the image only
//     // $validator = Validator::make($request->all(), [
//     //     'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
//     // ]);

//     // // Handle validation failure
//     // if ($validator->fails()) {
//     //     return response()->json(['error' => $validator->errors()], 401);
//     // }

//     // Find the existing banner or fail
//     $banner = Banner::findOrFail($id);

//     // Handling Image
//     if ($request->hasFile('image')) {
//         // Delete the old image file
//         Storage::delete('public/assets/img/banners/' . $banner->image);

//         // Upload the new image
//         $image = $request->file('image');
//         $ext = $image->getClientOriginalExtension();
//         $filename = uniqid('banner') . '.' . $ext;
//         $path = $image->storeAs('public/assets/img/banners', $filename);

//         // Update the image field
//         $banner->image = $filename;
//     }

//     // Save the changes
//     $banner->save();

//     return response()->json(['success' => 'Banner Image Updated', 'banner' => $banner], 200);
// }


    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->delete();

        return response()->json(['message' => 'Banner deleted'], 200);
    }
    
    public function statusChange(Request $request, $id): JsonResponse
    {
        $request->validate([
            'status' => 'required'
        ]);
    
        $banner = Banner::find($id);
    
        if (!$banner) {
            return response()->json(['error' => 'Banner Not Found!'], 404);
        }
    
        $banner->update([
            'status' => $request->status
        ]);
    
        return response()->json(['success' => 'Banner Status Updated.'], 200);
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