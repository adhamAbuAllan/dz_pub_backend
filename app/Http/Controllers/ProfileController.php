<?php

namespace App\Http\Controllers;
//namespace App\Http\Controllers\Promation;


use App\Http\Requests\Auth\ClientWithCRRequest;
use App\Http\Requests\Auth\ClientWithoutCRRequest;
use App\Http\Requests\Auth\InfluencerProfileRequest;
use App\Http\Requests\Auth\UserInfoRequest;
// use App\Models\ClientWithCR;      // model for clients_with_cr
// use App\Models\ClientWithoutCr;   // model for clients_without_cr
use App\Models\Influencer;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//use Illuminate\Support\Facades\Storage;
use App\Http\MyTrait\ResTrait;
use App\Helpers\StoreFileIfPresent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\SocialMeidaOfInfluencer;


class ProfileController extends Controller
{
    use ResTrait;


    /**
     * Combined endpoint to complete profile.
     * It detects current user type and dispatches to correct handler.
     *
     * Client flow:
     *   - if client's Client record has is_have_cr == 'yes' -> expects ClientWithCRRequest
     *   - else -> expects ClientWithoutCRRequest
     *
     * Influencer flow:
     *   - expects InfluencerProfileRequest and optional category_ids & social_media_ids arrays
     *
     * User info fields (phone, identity, profile_image, is_verified) handled by UserInfoRequest.
     */
    public function completeProfile(Request $request)
{
    $user = Auth::user();

    if (! $user) {
        return $this->fail('Unauthenticated');
    }

    // update is_have_cr from request
    if ($request->has('is_have_cr')) {
        $user->client->update([
            'is_have_cr' => $request->is_have_cr,
        ]);
    }

    if ($user->type_id === 1) {
        $clientMeta = $user->client;
        if (! $clientMeta) {
            return $this->fail('Client meta not found for user');
        }

        if ($clientMeta->is_have_cr === 'yes') {
            return $this->completeClientWithCr($request, $user);
        } else {
            return $this->completeClientWithoutCr($request, $user);
        }
    } else {
        return $this->completeInfluencer($request, $user);
    }
}


    protected function storeFileIfPresent($file, $folder = 'uploads')
    {
        if (! $file) return null;
        // store in storage/app/public/<folder>
        $path = $file->store($folder, 'public');
        return $path; // save path in DB (you can prepend storage url if needed)
    }

    protected function completeClientWithCr(Request $request, $user)
    {
        // Validate using your FormRequest class
        // Manually instantiate validation because we're in one controller that receives Request
        $validator = app(ClientWithCRRequest::class);
        $validated = $validator->replace($request->all())->validateResolved() ?? $request->all();
        // But more reliable: use validate() directly using rules() from the request:
        $validated = $request->validate((new ClientWithCRRequest)->rules());

        DB::beginTransaction();
        try {
            // handle image_of_license file
            $imagePath = null;
            if ($request->hasFile('image_of_license')) {
                $imagePath = StoreFileIfPresent::store
                ($request->file('image_of_license'), 'licenses');
            }

            // insert into clients_with_cr
            $data = [
                'client_id' => $user->id,
                'reg_owner_name' => $request->reg_owner_name,
                'institution_name' => $request->institution_name,
                'branch_address' => $request->branch_address,
                'institution_address' => $request->institution_address,
                'rc_number' => $request->rc_number,
                'nis_number' => $request->nis_number,
                'nif_number' => $request->nif_number,
                'iban' => $request->iban,
                'image_of_license' => $imagePath,
            ];

            // obey the rule you specified: when have_cr = yes => all required except nullable fields handled by request
            \App\Models\ClientWithCr::create($data);

            // user info (optional)
            if ($request->filled('phone_number') || $request->filled('identity_number') || $request->hasFile('profile_image')) {
                $this->upsertUserInfo($request, $user);
            }

            DB::commit();
            return $this->success(['message' => 'Client profile (with CR) completed',
        'client_with_cr' => $data
        ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->fail($e->getMessage());
        }
    }

    protected function completeClientWithoutCr(Request $request, $user)
    {
        $validated = $request->validate((new ClientWithoutCRRequest)->rules());

        DB::beginTransaction();
        try {
            // handle identity_image file
            $identityPath = null;
            if ($request->hasFile('identity_image')) {
                $identityPath = StoreFileIfPresent::store
                ($request->file('identity_image'), 'identities');
            }

            $data = [
                'client_id' => $user->id,
                'nickname' => $request->nickname,
                'identity_image' => $identityPath,
            ];

            \App\Models\ClientWithoutCr::create($data);

            if ($request->filled('phone_number') || $request->filled('identity_number') || $request->hasFile('profile_image')) {
                $this->upsertUserInfo($request, $user);
            }

            DB::commit();
            return $this->success(['message' => 'Client profile (without CR) completed',

        'client_without_cr' => $data]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->fail($e->getMessage());
        }
    }

    protected function completeInfluencer(Request $request, $user)
    {
        // merge influencer rules and user info rules as needed:
        $influencerRules = (new InfluencerProfileRequest)->rules();
        $userInfoRules = (new UserInfoRequest)->rules();

        $validated = $request->validate(array_merge($influencerRules, $userInfoRules, [
            // Accept arrays for pivot attachments



        ]));

        DB::beginTransaction();
        try {
            /** @var Influencer $influencer */
            $influencer = Influencer::find($user->id);
            if (! $influencer) {
                // create if missing (defensive)
                $influencer = Influencer::create([
                    'id' => $user->id,
                    'type_id' => $request->type_id ?? 1,
                ]);
            }

            // update influencer fields
            $influencer->rating = $request->rating ?? $influencer->rating;
            $influencer->bio = $request->bio ?? $influencer->bio;
            $influencer->gender = $request->gender ?? $influencer->gender;
            $influencer->date_of_birth = $request->date_of_birth ?? $influencer->date_of_birth;
            $influencer->shake_number = $request->shake_number ?? $influencer->shake_number;
            if ($request->filled('type_id')) {
                $influencer->type_id = $request->type_id;
            }
            $influencer->save();


            // user info
            $this->upsertUserInfo($request, $user);

            DB::commit();


            return $this->success(['message' => 'Influencer profile completed'
        ,'influencer' => $influencer]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->fail($e->getMessage());
        }
    }

    protected function upsertUserInfo(Request $request, $user)
    {
        // validate by UserInfoRequest before calling this method ideally
        $userInfo = UserInfo::where('user_id', $user->id)->first() ?? new UserInfo();

        // store profile image
        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $this->storeFileIfPresent($request->file('profile_image'), 'profiles');
        }

        $userInfo->user_id = $user->id;
        if ($request->filled('phone_number')) $userInfo->phone_number = $request->phone_number;
        if ($request->filled('identity_number')) $userInfo->identity_number = $request->identity_number;
        if ($profileImagePath) $userInfo->profile_image = $profileImagePath;
        if ($request->filled('is_verified')) $userInfo->is_verified = $request->is_verified;
        $userInfo->save();
    }
public function addSocialMediaLinks(Request $request)
{
    // 1️⃣ Validate input from the request
    $validated = $request->validate([
        'social_media_ids' => 'required|array',
        'social_media_ids.*' => 'exists:social_media,id',
        'social_media_urls' => 'required|array',
        'social_media_urls.*' => 'required|max:255',
    ]);

    // 2️⃣ Get the authenticated user
    $user = $request->user();

    // 3️⃣ Find the influencer
    $influencer = Influencer::findOrFail($user->id);

    // 4️⃣ Loop and attach/update links using SocialMeidaOfInfluencer
    foreach ($validated['social_media_ids'] as $index => $socialMediaId) {
        $url = $validated['social_media_urls'][$index] ?? null;
        if ($url) {
            SocialMeidaOfInfluencer::updateOrCreate(
                [
                    'influencer_id' => $influencer->id,
                    'social_media_id' => $socialMediaId,
                ],
                [
                    'url_of_soical' => $url,
                ]
            );
        }
    }

    // 5️⃣ Return the response
    return response()->json([
        'message' => 'Social media links added/updated successfully.',
        'social_media_links' => $influencer->socialMediaLinks()->get()
    ]);
}


public function assignCategories(Request $request)
{
    // 1️⃣ Validate input
    $validated = $request->validate([
        'category_ids' => 'required|array',
        'category_ids.*' => 'exists:categories,id',
    ]);

    // 2️⃣ Get the authenticated user
    $user = auth()->user();
    if (!$user) {
        return response()->json([
            'message' => 'Unauthenticated.'
        ], 401);
    }

    // 3️⃣ Find influencer by user id
    $influencer = Influencer::find($user->id);

    if (!$influencer) {
        return response()->json([
            'message' => 'User is not an influencer.',
        ], 404);
    }

    // 4️⃣ Sync categories to pivot table
    $influencer->categories()->sync($validated['category_ids']);

    // 5️⃣ Return updated categories
    return response()->json([
        'message' => 'Categories assigned successfully.',
        'categories' => $influencer->categories()->get(),
    ]);
}

public function getCategoriesByInfluencer(Request $request)
{
    // 1️⃣ Validate request parameter
    $validated = $request->validate([
        'influencer_id' => 'required|exists:influencers,id',
    ]);

    // 2️⃣ Get influencer by given ID
    $influencer = Influencer::find($validated['influencer_id']);

    // 3️⃣ Fetch categories
    $categories = $influencer->categories()->get();

    // 4️⃣ Return response
    return response()->json([
        'message' => 'Categories retrieved successfully.',
        'categories' => $categories,
    ]);
}
public function getSocialMediaLinksByInfluencer(Request $request)
{
    // 1️⃣ Validate the request parameter
    $validated = $request->validate([
        'influencer_id' => 'required|exists:influencers,id',
    ]);

    // 2️⃣ Get the influencer by the given ID
    $influencer = Influencer::find($validated['influencer_id']);

    // 3️⃣ Fetch social media links
    $socialMediaLinks = $influencer->socialMediaLinks()->get();

    // 4️⃣ Return response
    return response()->json([
        'message' => 'Social media links retrieved successfully.',
        'social_media_links' => $socialMediaLinks,
    ]);
}

    public function updateVerificationStatus(Request $request)
{



    $request->validate([
        'is_verified' => 'required', //no validate have
    ]);

    // Find or create UserInfo for the authenticated user
    $userInfo = UserInfo::updateOrCreate(
        ['user_id' => Auth::id()],
        ['is_verified' => $request->is_verified]
    );

    return $this->success($userInfo);
}

}
