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
     * 7) CATEGORY
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

        return $this->success($category);
    }

    public function allCategory()
    {
        return $this->success(Category::all());
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



}
