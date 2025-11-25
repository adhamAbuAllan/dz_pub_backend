<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Promation\SocialMediaPromotionRequest;
use App\Models\SocialMediaOfPromation;
use App\Models\TypeOfPromation;
use App\Models\TypeOfSocialMediaPromation;

class SocialMediaController extends Controller
{
    /**
     * Attach social media channels and type pivots for the given promation.
     * Expects validated request data:
     * - social_media => array of social_media ids
     * - promation_type_id => single id or array
     * - social_media_types => array of social_media_promation_type ids
     */
    public function handle($promotion, SocialMediaPromotionRequest $request)
    {
        // Use validated data
        $data = $request->validated();

        // 1) attach promation types (type_of_promations)
        $promationTypeIds = (array) ($data['promation_type_id'] ?? []);
        foreach ($promationTypeIds as $typeId) {
            TypeOfPromation::create([
                'promation_id' => $promotion->id,
                'type_id' => $typeId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2) attach social media channels (social_media_of_promations)
        $socialMediaIds = (array) $data['social_media'];
        foreach ($socialMediaIds as $smId) {
            SocialMediaOfPromation::create([
                'promation_id' => $promotion->id,
                'social_media_id' => $smId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3) attach social media types (type_of_social_media_promations)
        $socialMediaTypeIds = (array) $data['social_media_types'];
        foreach ($socialMediaTypeIds as $smtId) {
            TypeOfSocialMediaPromation::create([
                'promation_id' => $promotion->id,
                'type_id' => $smtId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Social media & types attached',
        ]);
    }
}
