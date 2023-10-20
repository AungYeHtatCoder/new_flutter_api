<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\User\HomeApiController;
use App\Http\Controllers\Api\V1\Admin\AuthApiController;
use App\Http\Controllers\Api\V1\Admin\RolesApiController;
use App\Http\Controllers\Api\V1\Admin\UsersApiController;
use App\Http\Controllers\Api\V1\Admin\BannerApiController;
use App\Http\Controllers\Api\V1\Admin\ProfileApiController;
use App\Http\Controllers\Api\V1\Admin\BlogPostApiController;
use App\Http\Controllers\Api\V1\Admin\PermissionsApiController;
use App\Http\Controllers\Api\V1\User\NoAuth\UserBlogPostApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/auth/register', [AuthApiController::class, 'createUser']);
Route::post('/auth/login', [AuthApiController::class, 'loginUser']);
//Route::post('/auth/logout', [AuthApiController::class, 'logoutUser']);
Route::get('/blog-detail/{id}', [HomeApiController::class, 'blogDetail']);

Route::middleware(['auth:sanctum'])->group(function () {
Route::apiResource('/profiles', ProfileApiController::class);
Route::put('/profile/{profile}', [ProfileApiController::class, 'update'])->name('api.profile.update');
Route::put('/phone-address-change', [ProfileApiController::class, 'PhoneAddressChange']);
Route::put('/change-password', [ProfileApiController::class, 'changePassword'])->name('changePassword');

Route::post('/auth/logout', [AuthApiController::class, 'logoutUser']);
Route::post('/like/{id}', [HomeApiController::class, 'like']);
Route::post('/comment/create/{id}', [HomeApiController::class, 'addComment']);
Route::put('/comment/edit/', [HomeApiController::class, 'editComment']);
Route::delete('/comment/delete/', [HomeApiController::class, 'deleteComment']);


});
// user 
Route::post('/search', [HomeApiController::class, 'search']);

// Route::middleware(['award.points'])->get('/', [HomeApiController::class, 'index'])->name('welcome');
Route::middleware(['auth:sanctum', 'award.points'])->get('/', [HomeApiController::class, 'index'])->name('welcome');


//Route::get('/', [HomeApiController::class, 'index'])->name('home');
//test
Route::get('/home', [HomeApiController::class, 'home']);
//test

Route::get('/banners', [HomeApiController::class, 'banners']);
//Route::post('/like/{id}', [HomeApiController::class, 'like']);
//Route::middleware('auth:sanctum')->post('/like/{id}', [HomeApiController::class, 'like']);

//blog post
Route::get('/blog-posts', [UserBlogPostApiController::class, 'index']);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'App\Http\Controllers\Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    // Permissions
    Route::apiResource('permissions', PermissionsApiController::class);
    // permissions update route
    Route::put('permissions/{permission}', [PermissionsApiController::class, 'update']);

    // Roles
    Route::apiResource('roles', RolesApiController::class);

    // Users
    Route::apiResource('users', UsersApiController::class);

    // profile resource rotues
    // PhoneAddressChange
    Route::put('/phone-address-change', [ProfileApiController::class, 'PhoneAddressChange']);
    Route::put('/change-password', [ProfileApiController::class, 'changePassword'])->name('changePassword');
    
    // Blog Post
    Route::post('blog-posts/media', [BlogPostApiController::class, 'storeMedia'])->name('blog-posts.storeMedia');
    Route::apiResource('blog-posts', BlogPostApiController::class);
    // blog post detail route
    Route::get('blog-posts/{id}', [BlogPostApiController::class, 'showDetail']);
    Route::apiResource('banners', BannerApiController::class);
    Route::put('banners/{banner}', [BannerApiController::class, 'update']);
    Route::post('/banners/statusChange/{id}', [BannerApiController::class, 'statusChange']);
});