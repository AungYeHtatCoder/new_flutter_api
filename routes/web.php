<?php

use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\PermissionsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\UserLogActivityController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Home\HomeController;
use Illuminate\Support\Facades\Artisan;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Auth::routes();
Route::middleware(['award.points'])->get('/', [HomeController::class, 'index'])->name('welcome');
//Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/like/{id}', [HomeController::class, 'like']);
Route::post('/comment/create/{id}', [HomeController::class, 'comment']);
Route::post('/comment/edit/', [HomeController::class, 'commentEdit']);
Route::post('/comment/delete/', [HomeController::class, 'commentDelete']);
Route::get('/login', [HomeController::class, 'login'])->name('login');
Route::get('/register', [HomeController::class, 'register'])->name('register');
Route::get('/blog-detail/{id}', [HomeController::class, 'blogDetail']);
Route::post('/search', [HomeController::class, 'search']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'App\Http\Controllers\Admin', 'middleware' => ['auth']], function () {
    //Route::get('/', [HomeController::class, 'index'])->name('home');
    // Permissions
    Route::delete('permissions/destroy', [PermissionsController::class, 'massDestroy'])->name('permissions.massDestroy');
    Route::resource('permissions', PermissionsController::class);

    // Roles
    Route::delete('roles/destroy', [RolesController::class, 'massDestroy'])->name('roles.massDestroy');
    Route::resource('roles', RolesController::class);

    // Users
    Route::delete('users/destroy', [UsersController::class, 'massDestroy'])->name('users.massDestroy');
    Route::resource('users', UsersController::class);
    // profile resource rotues
    Route::resource('profiles', ProfileController::class);
    //Route::post('/profiles/update/', [ProfileController::class, 'profileChange']);
    // brand_categories resource rotues
    // change password route with auth id
    Route::put('/change-password', [ProfileController::class, 'changePassword'])->name('changePassword');
    // PhoneAddressChange route with auth id route with put method
    Route::put('/change-phone-address', [ProfileController::class, 'PhoneAddressChange'])->name('changePhoneAddress');
    // user log activities route
    Route::get('add-to-log', [App\Http\Controllers\Admin\UserLogActivityController::class, 'store'])->name('logActivity.store');
    Route::get('logActivity', [App\Http\Controllers\Admin\UserLogActivityController::class, 'index'])->name('logActivity');
    Route::delete('/admin/logActivity/{id}', [UserLogActivityController::class, 'destroy'])->name('logActivity.destroy');
    Route::get('/admin/logActivity/{id}', [UserLogActivityController::class, 'show'])->name('logActivity.show');


    //ads banner crud
    Route::get('/banners', [BannerController::class, 'index'])->name('banners');
    Route::get('/banners/create/', [BannerController::class, 'create']);
    Route::post('/banners/create/', [BannerController::class, 'store']);
    Route::get('/banners/view/{id}', [BannerController::class, 'view']);
    Route::get('/banners/edit/{id}', [BannerController::class, 'edit']);
    Route::post('/banners/edit/{id}', [BannerController::class, 'update']);
    Route::post('/banners/delete/', [BannerController::class, 'delete']);
    Route::post('/banners/statusChange/{id}', [BannerController::class, 'statusChange']);

    //blog crud
    Route::get('/blogs', [BlogController::class, 'index']);
    Route::get('/blogs/create/', [BlogController::class, 'create']);
    Route::post('/blogs/create/', [BlogController::class, 'store']);
    Route::get('/blogs/view/{id}', [BlogController::class, 'view']);
    Route::get('/blogs/edit/{id}', [BlogController::class, 'edit']);
    Route::post('/blogs/edit/{id}', [BlogController::class, 'update']);
    Route::post('/blogs/delete/', [BlogController::class, 'delete']);

    });

    // csrf token error fix 
    Route::get('/csrf-token', function () {
        return response()->json(['csrfToken' => csrf_token()]);
    });
    
    // artisan storage link route
    Route::get('/storage-link', function () {
        Artisan::call('storage:link');
        return 'success';
    });