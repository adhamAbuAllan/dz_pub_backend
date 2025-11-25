<?php

//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Auth controllers
use App\Http\Controllers\RegisterController;
// use App\Http\Controllers\Auth\CompleteProfileController;
 use App\Http\Controllers\LoginController;
// use App\Http\Controllers\Auth\LogoutController;

// User info
// use App\Http\Controllers\UserInfoController;

// Promotion controllers
// use App\Http\Controllers\Promation\PromationController;
// use App\Http\Controllers\Promation\LocationController;
// use App\Http\Controllers\Promation\NoLocationController;
// use App\Http\Controllers\Promation\TopicAlreadyReadyController;
// use App\Http\Controllers\Promation\TopicFromInfluancerController;
// use App\Http\Controllers\Promation\ScriptController;
// use App\Http\Controllers\Promation\SocialMediaController;
use App\Http\Controllers\SeedAllController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PromationController;
// Middleware
//use App\Http\Middleware\CheckProfileCompletion;

// -----------------------------------------------------
// Auth routes
// -----------------------------------------------------


// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/auth/complete-profile', [CompleteProfileController::class, 'completeProfile']);

//     // User info endpoints
//     Route::get('/user-info', [UserInfoController::class, 'getUserInfo']);
//     Route::post('/user-info/phone', [UserInfoController::class, 'updatePhoneNumber']);
//     Route::post('/user-info/identity', [UserInfoController::class, 'updateIdentityNumber']);
//     Route::post('/user-info/profile-image', [UserInfoController::class, 'updateProfileImage']);

//     // -----------------------------------------------------
//     // Promotion routes (only for authenticated users)
//     // -----------------------------------------------------
//     Route::prefix('promations')->group(function () {

//         // Main promotion creation
//         Route::post('/create', [PromationController::class, 'createPromotion']);

//         // Sub-controller endpoints (optional direct access)
//         Route::post('/{promotion}/social-media', [SocialMediaController::class, 'handle']);
//         Route::post('/{promotion}/location', [LocationController::class, 'handle']);
//         Route::post('/{promotion}/no-location', [NoLocationController::class, 'handle']);
//         Route::post('/{promotion}/topic-ready', [TopicAlreadyReadyController::class, 'handle']);
//         Route::post('/{promotion}/topic-from-influencer', [TopicFromInfluancerController::class, 'handle']);
//         Route::post('/{promotion}/script', [ScriptController::class, 'handle']);
//     });
// });
Route::post('auth/login', [LoginController::class, 'login']);
Route::post('auth/register', [RegisterController::class, 'register']);

// protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('auth/complete-profile', [ProfileController::class, 'completeProfile']);
// Route::post('/add/promation', [PromationController::class, 'createPromotion']);

});
Route::post('debug', function () {
    return 'API WORKING';
});
//a test route
//Route::get('add/promation', [PromationController::class, 'testMethod']);
Route::post('add/promation', [PromationController::class, 'createPromotion']);


Route::prefix('seed')->group(function () {

    Route::get('/add/influencer-type', [SeedAllController::class, 'addInfluencerType']);
    Route::get('/get/influencer-type', [SeedAllController::class, 'allInfluencerTypes']);

    Route::get('/add/promation-status', [SeedAllController::class, 'addPromationStatus']);
    Route::get('/get/promation-status', [SeedAllController::class, 'allPromationStatus']);

    Route::get('/add/promation-type', [SeedAllController::class, 'addPromationType']);
    Route::get('/get/promation-type', [SeedAllController::class, 'allPromationType']);

    Route::get('/add/social-media', [SeedAllController::class, 'addSocialMedia']);
    Route::get('/get/social-media', [SeedAllController::class, 'allSocialMedia']);

    Route::get('/add/type-promation', [SeedAllController::class, 'addTypeOfPromation']);
    Route::get('/get/type-promation', [SeedAllController::class, 'allTypeOfPromation']);

    Route::get('/add/type-user', [SeedAllController::class, 'addTypeOfUser']);
    Route::get('/get/type-user', [SeedAllController::class, 'allTypeOfUser']);

    Route::get('/add/category', [SeedAllController::class, 'addCategory']);
    Route::get('/get/category', [SeedAllController::class, 'allCategory']);

    Route::get('/add/social-media-promation-type', [SeedAllController::class, 'addSocialMediaPromationType']);
    Route::get('/get/social-media-promation-type', [SeedAllController::class, 'allSocialMediaPromationType']);

    Route::get('/add/type-of-social-media-promation', [SeedAllController::class, 'addTypeOfSocialMediaPromation']);
    Route::get('/get/type-of-social-media-promation', [SeedAllController::class, 'allTypeOfSocialMediaPromation']);
});



