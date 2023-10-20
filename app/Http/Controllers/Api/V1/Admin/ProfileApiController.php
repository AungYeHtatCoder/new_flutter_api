<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
class ProfileApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
{
    $user = User::find(Auth::user()->id);

    if (auth()->user()->hasRole('Admin')) {
        return response()->json([
            'role' => 'Admin',
            'user' => $user,
        ], 200);
    } else {
        return response()->json([
            'role' => 'User',
            'user' => $user,
        ], 200);
    }
}

    // public function index()
    // {
    //     $user = auth()->user();
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'User Profile',
    //         'data' => $user
    //     ], 200);
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    // Update the specified resource in storage.
// public function update(Request $request)
// {
//     dd($request->all());
//     if ($request->hasFile('profile')) {
//         $profile = $request->file('profile');
//         $ext = $profile->getClientOriginalExtension();

//         // Checking the file extension
//         if ($ext === "png" || $ext === "jpeg" || $ext === "jpg") {
//             $user = User::find(Auth::user()->id);

//             // Delete existing profile if it exists
//             if ($user->profile) {
//                 File::delete(public_path('assets/img/profile/' . $user->profile));
//             }

//             // Save the new image
//             $filename = uniqid('profile') . '.' . $ext;
//             $profile->move(public_path('assets/img/profile/'), $filename);

//             // Update the profile in the database
//             $user->update([
//                 'profile' => $filename
//             ]);

//             return response()->json([
//                 'success' => true,
//                 'message' => 'Profile has been updated',
//             ], 200);

//         } else {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Please use a valid file type!',
//             ], 400);
//         }
//     } else {
//         return response()->json([
//             'success' => false,
//             'message' => 'No profile image uploaded',
//         ], 400);
//     }
// }
public function update(Request $request, User $profile)
{
    try {
        \Log::info('Received data:', $request->all());
        // Validate the request data
        $request->validate([
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust validation rules as needed
        ]);

        $data = [];

        // Check if a new profile image has been uploaded
        $newImage = $request->file('profile');

        if ($newImage) {
            $mainFolder = 'profile_image/' . Str::random();
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

            $data['profile'] = URL::to(Storage::url($path));
            $data['profile_mime'] = $newImage->getClientMimeType();
            $data['profile_size'] = $newImage->getSize();
            
            // If there is an old image, delete it
            if ($profile->profile) {
                $oldImagePath = str_replace(URL::to('/'), '', $profile->profile);
                Storage::delete($oldImagePath);
            }
        }

        // Update the user profile data
        $profile->update($data);

        // Return a JSON response indicating success
        return response()->json(['message' => 'Profile updated successfully', 'data' => $profile]);
    } catch (\Exception $e) {
        // Handle any database errors
        return response()->json(['message' => 'Profile update failed', 'error' => $e->getMessage()], 500);
    }
}
// public function update(UserRequest $request, User $profile)
// {
//     // Debugging: Print the request data to the log
//     \Log::info($request->all());

//     $data = $request->validated();

//     // Check if a new profile image has been uploaded
//     $newImage = $request->file('profile');
    
//     if ($newImage) {
//         $mainFolder = 'profile_image/' . Str::random();
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

//         $data['profile'] = URL::to(Storage::url($path));
//         $data['profile_mime'] = $newImage->getClientMimeType();
//         $data['profile_size'] = $newImage->getSize();
        
//         // If there is an old image, delete it
//         if ($profile->profile) {
//             $oldImagePath = str_replace(URL::to('/'), '', $profile->profile);
//             Storage::delete($oldImagePath);
//         }
//     }

//     try {
//         // Update the user profile data
//         $profile->update($data);
//     } catch (\Exception $e) {
//         // Handle any database errors
//         return response()->json(['message' => 'Profile update failed'], 500);
//     }

//     // Return a JSON response indicating success
//     return response()->json(['message' => 'Profile updated successfully', 'data' => $profile]);
// }

// public function update(UserRequest $request, User $profile)
// {
//     $data = $request->validated();

//     // Check if a new profile image has been uploaded
//     $newImage = $request->file('profile');
    
//     if ($newImage) {
//         $mainFolder = 'profile_image/' . Str::random();
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

//         $data['profile'] = URL::to(Storage::url($path));
//         $data['profile_mime'] = $newImage->getClientMimeType();
//         $data['profile_size'] = $newImage->getSize();
        
//         // If there is an old image, delete it
//         if ($profile->profile) {
//             $oldImagePath = str_replace(URL::to('/'), '', $profile->profile);
//             Storage::delete($oldImagePath);
//         }
//     }

//     try {
//         // Update the user profile data
//         $profile->update($data);
//     } catch (\Exception $e) {
//         // Handle any database errors
//         return response()->json(['message' => 'Profile update failed'], 500);
//     }

//     // Return a JSON response indicating success
//     return response()->json(['message' => 'Profile updated successfully', 'data' => $profile]);
// }
// public function update(UserRequest $request, User $profile)
// {
//     try {
//         $data = $request->validated();

//         /** @var \Illuminate\Http\UploadedFile $image */
//         $image = $data['profile'] ?? null;
        
//         if ($image) {
//             $relativePath = $this->saveImage($image);
//             $data['profile'] = URL::to(Storage::url($relativePath));
//             $data['profile_mime'] = $image->getClientMimeType();
//             $data['profile_size'] = $image->getSize();

//             if ($profile->image) {
//                 Storage::deleteDirectory('/public/' . dirname($profile->image));
//             }
//         }

//         $profile->update($data);

//         return response()->json(['message' => 'Profile updated successfully'], 200);

//     } catch (\Exception $e) {
//         return response()->json(['error' => 'Failed to update profile', 'details' => $e->getMessage()], 400);
//     }
// }

    public function saveImage(UploadedFile $image)
    {
        $path = 'profile_image/' . Str::random();
        //$path = 'images/product_image';

        if (!Storage::exists($path)) {
            Storage::makeDirectory($path, 0755, true);
        }
        if (!Storage::putFileAS('public/' . $path, $image, $image->getClientOriginalName())) {
            throw new \Exception("Unable to save file \"{$image->getClientOriginalName()}\"");
        }

        return $path . '/' . $image->getClientOriginalName();
    }


// Password change function
public function changePassword(Request $request)
{
    $request->validate([
        'old_password' => 'required',
        'password' => 'required|confirmed|min:8',
    ]);

    $user = User::find(Auth::user()->id);

    if (Hash::check($request->old_password, $user->password)) {
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password has been updated',
        ], 200);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Old password does not match!',
        ], 400);
    }
}

// Phone and address update function
public function PhoneAddressChange(Request $request)
{
    $request->validate([
        'phone' => 'required',
        'address' => 'required',
    ]);

    $user = User::find(Auth::user()->id);
    $user->update([
        'phone' => $request->phone,
        'address' => $request->address,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Phone and address have been updated',
    ], 200);
}




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}