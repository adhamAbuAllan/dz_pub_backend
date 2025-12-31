<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\InfluencerTypes;
use App\Models\PromationStatus;
use App\Models\PromationType;
use App\Models\SocialMedia;
use App\Models\TypeOfPromation;
use App\Models\TypeOfUser;
use App\Models\Category;
use App\Models\TypeOfSocialMediaPromation;
use App\Models\SocialMediaPromationType;
use App\Models\Influencer;
use App\Models\User;
use App\Models\CustomPromotion;
use App\Helpers\ArrayHelper;
use Illuminate\Support\Facades\DB;
use App\Models\Report;


class SeedAllController extends Controller
{
    /* ============================================================
     * 1) INFLUENCER TYPES
     * ============================================================ */
    public function addInfluencerType(Request $request)
    {
        $fields = ['name' => 'required'];
        $valid = Validator::make($request->all(), $fields);

        if ($valid->fails()) {
            return $this->fail($valid->messages()->first());
        }

        $type = InfluencerTypes::create([
            'name' => $request->name
        ]);

        return $this->success($type);
    }

    public function allInfluencerTypes()
    {
        return $this->success(InfluencerTypes::all());
    }

    /* ============================================================
     * 2) PROMATION STATUS
     * ============================================================ */
    public function addPromationStatus(Request $request)
    {
        $fields = ['name' => 'required'];
        $valid = Validator::make($request->all(), $fields);

        if ($valid->fails()) {
            return $this->fail($valid->messages()->first());
        }

        $status = PromationStatus::create([
            'name' => $request->name
        ]);

        return $this->success($status);
    }

    public function allPromationStatus()
    {
        return $this->success(PromationStatus::all());
    }

    /* ============================================================
     * 3) PROMATION TYPE
     * ============================================================ */
    public function addPromationType(Request $request)
    {
        $fields = ['name' => 'required'];
        $valid = Validator::make($request->all(), $fields);

        if ($valid->fails()) {
            return $this->fail($valid->messages()->first());
        }

        $type = PromationType::create([
            'name' => $request->name
        ]);

        return $this->success($type);
    }

    public function allPromationType()
    {
        return $this->success(PromationType::all());
    }

    /* ============================================================
     * 4) SOCIAL MEDIA
     * ============================================================ */
    public function addSocialMedia(Request $request)
    {
        $fields = ['name' => 'required'];
        $valid = Validator::make($request->all(), $fields);

        if ($valid->fails()) {
            return $this->fail($valid->messages()->first());
        }

        $social = SocialMedia::create([
            'name' => $request->name
        ]);

        return $this->success($social);
    }

    public function allSocialMedia()
    {
        return $this->success(SocialMedia::all());
    }

    /* ============================================================
     * 5) TYPE OF PROMATION
     * ============================================================ */
    public function addTypeOfPromation(Request $request)
    {
        $fields = [
            'promation_id' => 'required|integer',
            'type_id' => 'required|integer',
        ];
        $valid = Validator::make($request->all(), $fields);

        if ($valid->fails()) {
            return $this->fail($valid->messages()->first());
        }

        $promationType = TypeOfPromation::create([
            'promation_id' => $request->promation_id,
            'type_id' => $request->type_id,
        ]);

        return $this->success($promationType);
    }

    public function allTypeOfPromation()
    {
        return $this->success(TypeOfPromation::all());
    }

    /* ============================================================
     * 6) TYPE OF USER
     * ============================================================ */
    public function getUserType(Request $request)
    {
        $id = $request->query('user_id');

        if (!$id) {
            return response()->json([
                'status' => false,
                'message' => 'User ID is required'
            ], 400);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'type_id' => $user->type_id,
                'type' => $user->type_id == 1 ? 'influencer' : 'client',
            ]
        ]);
    }
    public function addTypeOfUser(Request $request)
    {
        $fields = ['name' => 'required'];
        $valid = Validator::make($request->all(), $fields);

        if ($valid->fails()) {
            return $this->fail($valid->messages()->first());
        }

        $userType = TypeOfUser::create([
            'name' => $request->name
        ]);

        return $this->success($userType);
    }

    public function allTypeOfUser()
    {
        return $this->success(TypeOfUser::all());
    }

    /* ============================================================
     * 7) CATEGORY / INFLUENCER
     * ============================================================ */
    public function addCategory(Request $request)
    {
        $fields = ['name' => 'required'];
        $valid = Validator::make($request->all(), $fields);

        if ($valid->fails()) {
            return $this->fail($valid->messages()->first());
        }

        $category = Category::create([
            'name' => $request->name
        ]);
//categories
        return $this->success($category);
    }

   public function allCategory()
{
    return response()->json([
        'message' => 'Categories fetched successfully',
        'categories' => Category::all()
    ]);
}

public function getInfluencersByCategory(Request $request)
{
    $request->validate([
        'category_id' => 'required|exists:categories,id',
    ]);

    $categoryId = $request->category_id;

    // Get users who are influencers *and* have this category
    $users = User::with([
       'userInfo',
        'influencer.categories'   // influencer with categories
    ])
    ->whereHas('influencer.categories', function ($q) use ($categoryId) {
        $q->where('categories.id', $categoryId);
    })
    ->get();

    return response()->json([
        'status' => true,
        'message' => "Influencers of category ID: $categoryId",
        'data' => $users
    ]);
}
public function getAllInfluencers()
{
    $influencers = User::with([
        'userInfo',
        'influencer.categories',
        'influencer.socialMediaLinks',

    ])
    ->whereHas('influencer')
    ->where('is_active', '!=', -1)
    ->get();

    return response()->json([
        'status' => true,
        'message' => "All influencers",
        'data' => $influencers
    ]);
}

public function getInfluencerById(Request $request)
{
    // Validate request
    $request->validate([
        'id' => 'required|integer'
    ]);

    $influencer = User::with([
        'userInfo',
        'influencer.categories',
        'influencer.socialMediaLinks',
    ])
    ->whereHas('influencer')
    ->where('id', $request->id)
    ->first();

    if (!$influencer) {
        return response()->json([
            'status' => false,
            'message' => "Influencer not found"
        ], 404);
    }

    return response()->json([
        'status' => true,
        'message' => "Influencer data",
        'data' => $influencer
    ]);
}
public function changeInfluencerStatus(Request $request)
{
    // Validate input
    $request->validate([
        'id' => 'required|integer|exists:users,id',
        'is_active' => 'required|integer|in:-1,0,1',
    ]);

    // Get influencer user
    $user = User::whereHas('influencer')
        ->where('id', $request->id)
        ->first();

    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'Influencer not found'
        ], 404);
    }

    // Update status
    $user->is_active = $request->is_active;
    $user->save();

    return response()->json([
        'status' => true,
        'message' => 'Influencer status updated successfully',
        'data' => [
            'id' => $user->id,
            'is_active' => $user->is_active
        ]
    ]);
}



    /* ============================================================
     * Report methods
     * ============================================================ */
    

public function getReportsByStatus(Request $request)
{
    $request->validate([
        'status_id' => 'required|integer|exists:report_statuses,id',
    ]);

    $reports = Report::with([
            'status',
            'reporter.userInfo',
            'reported.userInfo',
        ])
        ->where('report_status_id', $request->status_id)
        ->latest()
        ->get();

    return response()->json([
        'status' => true,
        'message' => 'Reports list',
        'data' => ArrayHelper::clean($reports)
    ]);
}

public function changeReportStatus(Request $request)
{
    $request->validate([
        'report_id' => 'required|integer|exists:reports,id',
        'status_id' => 'required|integer|exists:report_statuses,id',
    ]);

    $report = Report::find($request->report_id);
    $report->report_status_id = $request->status_id;
    $report->save();

    return response()->json([
        'status' => true,
        'message' => 'Report status updated successfully'
    ]);
}

public function addReport(Request $request)
{
    $request->validate([
         'reporter_id' => 'required|integer|exists:users,id',
        'reported_id' => 'required|integer|exists:users,id',
        'content' => 'required|string|min:5',
    ]);

    // Prevent reporting yourself
    if ($request->reporter_id == $request->reported_id) {
        return response()->json([
            'status' => false,
            'message' => 'You cannot report yourself'
        ], 400);
    }

    $report = Report::create([
        'reporter_id' => $request->reporter_id,
        'reported_id' => $request->reported_id,
        'content' => $request->content,
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Report submitted successfully',
        'data' => $report
    ], 201);
}
public function getReports()
{
    $reports = Report::with([
            'reporter.userInfo',
            'reported.userInfo',
        ])
        ->latest()
        ->get();

    return response()->json([
        'status' => true,
        'message' => 'Reports list',
        'data' => ArrayHelper::clean($reports)
    ]);
}
    /* ============================================================
     * Users
     * ============================================================ */


    public function deleteUser(Request $request)
{
    $request->validate([
        'user_id' => 'required|integer|exists:users,id',
    ]);

    DB::transaction(function () use ($request) {
        $user = User::findOrFail($request->user_id);
        $user->delete();
    });

    return response()->json([
        'status' => true,
        'message' => 'User permanently deleted'
    ]);
}

    public function changeUserVerificationStatus(Request $request)
{
    // Validate input
    $request->validate([
        'user_id' => 'required|integer|exists:users,id',
        'is_verified' => 'required|in:yes,no',
    ]);

    // Get user with userInfo
    $user = User::with('userInfo')->find($request->user_id);

    if (!$user || !$user->userInfo) {
        return response()->json([
            'status' => false,
            'message' => 'User info not found'
        ], 404);
    }

    // Update verification status
    $user->userInfo->is_verified = $request->is_verified;
    $user->userInfo->save();

    return response()->json([
        'status' => true,
        'message' => 'User verification status updated successfully',
        'data' => [
            'user_id' => $user->id,
            'is_verified' => $user->userInfo->is_verified
        ]
    ]);
}

 public function getUnverifiedUsers(Request $request)
{
    $users = User::whereHas('userInfo', function ($query) {
            $query->where('is_verified', 'no');
        })
        ->with('userInfo')
        ->when($request->filled('type_id'), function ($query) use ($request) {
            $query->where('type_id', $request->type_id);
        })
        ->get();

    return response()->json([
        'status' => true,
        'message' => 'Unverified users',
        'data' => $users
    ]);
}


public function getUsers()
{
    $users = User::with([
        'userInfo',

        // Influencer side
        'influencer',
        'influencer.categories',
        'influencer.typeOfInfluencer',


        // Client side
        'client',


        // Add ANY other relations here
    ])
    ->where('type_id', '!=', 3)
    ->get();

    return response()->json([
        'status' => true,
        'message' => 'Users data',
        'data' => ArrayHelper::clean($users)
    ]);
}
public function getInactiveUsers()
{
    $users = User::where('is_active', -1)
        ->get();

    return response()->json([
        'status' => true,
        'message' => 'Inactive users',
        'data' => $users
    ]);
}


    /* ============================================================
     * 8) TYPE OF SOCIAL MEDIA PROMATION
     * ============================================================ */
    public function addTypeOfSocialMediaPromation(Request $request)
    {
        $fields = [
            'promation_id' => 'required|integer',
            'type_id' => 'required|integer',
        ];
        $valid = Validator::make($request->all(), $fields);

        if ($valid->fails()) {
            return $this->fail($valid->messages()->first());
        }

        $type = TypeOfSocialMediaPromation::create([
      'promation_id' => $request->promation_id,
        'type_id' => $request->type_id,
        ]);

        return $this->success($type);
    }

    public function allTypeOfSocialMediaPromation()
    {
        return $this->success(TypeOfSocialMediaPromation::all());
    }

       /* ============================================================
     * 9) SOCIAL MEDIA PROMATION TYPE
     * ============================================================ */

 public function addSocialMediaPromationType(Request $request)
    {
        $fields = ['name' => 'required'];
        $valid = Validator::make($request->all(), $fields);

        if ($valid->fails()) {
            return $this->fail($valid->messages()->first());
        }

        $type = SocialMediaPromationType::create([
            'name' => $request->name
        ]);

        return $this->success($type);
    }

    public function allSocialMediaPromationType()
    {
        return $this->success(SocialMediaPromationType::all());
    }

public function addCustomPromotion(Request $request)
{
    // Validation rules
    $fields = [
        'client_id' => 'required|integer|exists:clients,id',
        'text' => 'required|string',
    ];

    $valid = Validator::make($request->all(), $fields);

    if ($valid->fails()) {
        return $this->fail($valid->messages()->first());
    }

    // Insert the data
    $customPromotion = CustomPromotion::create([
        'client_id' => $request->client_id,
        'text' => $request->text,
    ]);

    return $this->success($customPromotion);
}
public function getCustomPromotion(Request $request)
{
    // Validate request
    $fields = [
        'client_id' => 'required|integer|exists:clients,id',
    ];

    $valid = Validator::make($request->all(), $fields);

    if ($valid->fails()) {
        return $this->fail($valid->messages()->first());
    }

    // Get ALL promotions for this client
    $customPromotions = CustomPromotion::where('client_id', $request->client_id)->get();

    // If empty
    if ($customPromotions->isEmpty()) {
        return $this->fail("No custom promotions found for this client.");
    }

    return $this->success($customPromotions);
}


}
