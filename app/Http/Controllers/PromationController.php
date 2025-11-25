<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\Promation\PromotionBaseRequest;
use App\Http\Requests\Promation\PromotionLocationRequest;
use App\Http\Requests\Promation\PromotionNoLocationRequest;
use App\Http\Requests\Promation\SocialMediaPromotionRequest;
use App\Http\Requests\Promation\TopicAlreadyReadyRequest;
use App\Http\Requests\Promation\TopicFromInfluancerRequest;
use App\Http\Requests\Promation\ScriptPromotionRequest;
use App\Models\TypeOfPromation;
use App\Models\RegstrationOfPromation;
use App\Models\Promation;
use App\Models\FileOfTopic;
use App\Models\SocialMediaOfPromation;
use App\Models\TypeOfSocialMediaPromation;
use App\Models\InfluancherMovment;
use App\Models\Recommendation;
use App\Models\FileOfRecommendation;
use App\Models\TopicAlreadyReady;
use App\Models\TopicFromInfluancer;

class PromationController extends Controller
{
    public function createPromotion(PromotionBaseRequest $request)
    {
        try {
            return DB::transaction(function () use ($request) {

                // 1) Create main promotion
                $promotion = Promation::create($request->validated());

                // 2) Social media relations (always required)

                // build a social media promaiton validate
                $socialMediaData = [
                    'social_media' => $request->social_media,

                ];
                $socialMediaType = [
                    'social_media_types' => $request->social_media_types,
                ];
                //input social media types

                foreach ($socialMediaData['social_media'] as $smId) {
                    SocialMediaOfPromation::create([
                        'promation_id' => $promotion->id,
                        'social_media_id' => $smId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                foreach ($socialMediaType['social_media_types'] as $smtId) {
                    TypeOfSocialMediaPromation::create([
                        'promation_id' => $promotion->id,
                        'type_id' => $smtId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }



                //input social media

                // ----------------------------
                // Branch: movement = yes
                // ----------------------------
                if ($request->should_influencer_movment === 'yes') {
                    $loc = $request->input('location');
                    InfluancherMovment::create([
                        'location' => $loc,
                        'promation_id' => $promotion->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    if ($request->hasFile('file_of_topic')) {
                        $file = $request->file('file_of_topic');

                        FileOfTopic::create([
                            'promation_id' => $promotion->id,
                            'file_path' => $file->store('promation_topics', 'public'),
                        ]);
                    }

                    return response()->json([
                        'status' => true,
                        'promation' => $promotion->fresh()
                    ]);
                }

                // ----------------------------
                // Branch: movement = no
                // ----------------------------


                RegstrationOfPromation::create([
                    'promation_id' => $promotion->id,
                    'have_a_form' => $request->have_a_form,
                ]);

                // ----------------------------
                // If have_a_form = yes
                // ----------------------------
                if ($request->have_a_form === 'yes') {
                    $typeId = (int) $request->promation_type_id;

                    if ($typeId === 3) {
                        // Script promotion
                        //Recommendation
                        Recommendation::create([
                            'text' => $request->text,
                            'promation_id' => $promotion->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        if ($request->hasFile('file_of_recommendation')) {
                            $file = $request->file('file_of_recommendation');

                            FileOfRecommendation::create([
                                'promation_id' => $promotion->id,
                                'file_path' => $file->store('files_of_recommendations', 'public'),
                            ]);
                        }
                    } else {
                        // Image or Video
                        // path like //http://127.0.0.1:8000/storage/media_files/meda.png


                        if ($request->topic_is_ready === 'yes') {
    $file_path = $request->file('file_path');
      TopicAlreadyReady::create([
                                  'detials' => $request->detials,
                                'have_smaple' => $request->have_smaple,
                                'file_path'=>$file_path->store('media_files','public'),
                                'promation_id' => $promotion->id,
                            ]);




                        if ($request->hasFile('file_of_topic')) {
                        $file = $request->file('file_of_topic');

                        FileOfTopic::create([
                            'promation_id' => $promotion->id,
                            'file_path' => $file->store('promation_topics', 'public'),
                        ]);
                    }

                        } else {

                            TopicFromInfluancer::create([
                                  'detials' => $request->detials,
                                'have_smaple' => $request->have_smaple,
                                'promation_id' => $promotion->id,
                            ]);
                        }
                    }
                }
                TypeOfPromation::create([
                    'type_id' => $typeId,
                    'promation_id' => $promotion->id,

                ]);

                return response()->json([
                    'status' => true,
                    'promation' => $promotion->fresh()
                ]);
            });
        } catch (\Throwable $e) {
            // catch inside the transaction
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}
