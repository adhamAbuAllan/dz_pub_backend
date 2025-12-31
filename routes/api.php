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
use App\Http\Controllers\InfluencerController;
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
        Route::post('auth/update-verification', [ProfileController::class, 'updateVerificationStatus']);
    Route::post('auth/assign-categories', [ProfileController::class, 'assignCategories']);
    Route::post('auth/add-social-media-links', [ProfileController::class, 'addSocialMediaLinks']);
// Route::post('/add/promation', [PromationController::class, 'createPromotion']);
//updateVerificationStatus

});
    Route::get('get-categories-by-influencer', [ProfileController::class, 'getCategoriesByInfluencer']);
    Route::get('get-social-media-links-by-influencer', [ProfileController::class, 'getSocialMediaLinksByInfluencer']);

Route::post('debug', function () {
    return 'API WORKING';
});
Route::get('get/influencers',[InfluencerController::class,'getInfluencers']);
Route::post('auth/complete-influencer-profile',[ProfileController::class,'completeInfluencer']);
Route::get('update/promation', [PromationController::class, 'updatePromotion']);
Route::post('add/promation', [PromationController::class, 'createPromotion']);
Route::post('get/promation', [PromationController::class, 'getPromotionsByClient']);
Route::post('get/promation-by-status', [PromationController::class, 'getPromotionsByStatus']);
Route::get('get/last-promotion-by-influencer', [PromationController::class, 'getLastPromotionByInfluencer']);


Route::prefix('seed')->group(function () {
    //Reports routes
    Route::post('/update/report-status', [SeedAllController::class, 'changeReportStatus']);
    Route::get('/get/reports-by-status', [SeedAllController::class, 'getReportsByStatus']);
    Route::post('/add/report', [SeedAllController::class, 'addReport']);
    Route::get('/get/reports', [SeedAllController::class, 'getReports']);

    Route::get('/get/users', [SeedAllController::class, 'getUsers']);
    Route::get('/get/inactive-users', [SeedAllController::class, 'getInactiveUsers']);
    Route::post('/update/user-status', [SeedAllController::class, 'changeInfluencerStatus']);
    Route::get('/get/unverified-users', [SeedAllController::class, 'getUnverifiedUsers']);
    Route::put('/update/verify', [SeedAllController::class, 'changeUserVerificationStatus']);
    Route::delete('/delete/user', [SeedAllController::class, 'deleteUser']);


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
    Route::get('/get/user-type', [SeedAllController::class, 'getUserType']);

    Route::get('/add/category', [SeedAllController::class, 'addCategory']);
    Route::get('/get/category', [SeedAllController::class, 'allCategory']);
    Route::get('/get/influencer-category', [SeedAllController::class, 'getInfluencersByCategory']);
    Route::get('/get/influencers', [SeedAllController::class, 'getAllInfluencers']);
    Route::get('/get/influencer', [SeedAllController::class, 'getInfluencerById']);

    Route::get('/add/social-media-promation-type', [SeedAllController::class, 'addSocialMediaPromationType']);
    Route::get('/get/social-media-promation-type', [SeedAllController::class, 'allSocialMediaPromationType']);

    Route::get('/add/type-of-social-media-promation', [SeedAllController::class, 'addTypeOfSocialMediaPromation']);
    Route::get('/get/type-of-social-media-promation', [SeedAllController::class, 'allTypeOfSocialMediaPromation']);
    Route::get('/add/custom-promotion', [SeedAllController::class, 'addCustomPromotion']);
    Route::get('/get/custom-promotion', [SeedAllController::class, 'getCustomPromotion']);
});



