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
use Illuminate\Http\Request;

class PromationController extends Controller
{


  public function createPromotion(PromotionBaseRequest $request)
{
    try {
        return DB::transaction(function () use ($request) {

            /*
            |--------------------------------------------------------------------------
            | 1. Create Main Promotion
            |--------------------------------------------------------------------------
            */
            $promotion = Promation::create($request->validated());


            /*
            |--------------------------------------------------------------------------
            | 2. Insert Social Media Platforms
            |--------------------------------------------------------------------------
            */
            foreach ($request->social_media as $smId) {
                SocialMediaOfPromation::create([
                    'promation_id' => $promotion->id,
                    'social_media_id' => $smId,
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | 3. Insert Social Media Types (Post | Story | Reel | etc.)
            |--------------------------------------------------------------------------
            */
            foreach ($request->social_media_types as $typeId) {
                TypeOfSocialMediaPromation::create([
                    'promation_id' => $promotion->id,
                    'type_id' => $typeId,
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | 4. Movement Case (Influencer Must Move)
            |--------------------------------------------------------------------------
            */
            if ($request->should_influencer_movment === 'yes') {

                InfluancherMovment::create([
                    'promation_id' => $promotion->id,
                    'location' => $request->location,
                ]);

                // Optional attachment
                if ($request->hasFile('file_of_topic')) {
                    FileOfTopic::create([
                        'promation_id' => $promotion->id,
                        'file_path' => $request->file('file_of_topic')->store('promation_topics', 'public'),
                    ]);
                }

                return $this->returnPromotionJson($promotion->id);
            }


            /*
            |--------------------------------------------------------------------------
            | 5. Registration (Movement = no)
            |--------------------------------------------------------------------------
            */
            RegstrationOfPromation::create([
                'promation_id' => $promotion->id,
                'have_a_form' => $request->have_a_form,
            ]);


            /*
            |--------------------------------------------------------------------------
            | 6. If Have a Form = Yes → Validate Promotion Type
            |--------------------------------------------------------------------------
            */
            if ($request->have_a_form === 'yes') {

                $typeId = (int) $request->promation_type_id;

                /*
                | Script Promotion
                */
                if ($typeId === 3) {

                    $rec = Recommendation::create([
                        'promation_id' => $promotion->id,
                        'text' => $request->text,
                    ]);

                    if ($request->hasFile('file_of_recommendation')) {
                        FileOfRecommendation::create([
                            'recommendation_id' => $rec->id,
                            'file_path' => $request->file('file_of_recommendation')
                                                ->store('files_of_recommendations', 'public'),
                        ]);
                    }

                }

                /*
                | Image / Video Promotion
                */
                else {

                    /*
                    |--------------------------------------------------------------------------
                    | Topic Ready (User Provides File)
                    |--------------------------------------------------------------------------
                    */
                    if ($request->topic_is_ready === 'yes') {

                        $readyPath = $request->file('file_path')->store('media_files', 'public');

                        TopicAlreadyReady::create([
                            'promation_id' => $promotion->id,
                            'detials' => $request->detials,
                            'have_smaple' => $request->have_smaple,
                            'file_path' => $readyPath,
                        ]);

                        // optional: topic description file
                        if ($request->hasFile('file_of_topic')) {
                            FileOfTopic::create([
                                'promation_id' => $promotion->id,
                                'file_path' => $request->file('file_of_topic')
                                                      ->store('promation_topics', 'public'),
                            ]);
                        }
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Topic From Influencer
                    |--------------------------------------------------------------------------
                    */
                    else {
                        TopicFromInfluancer::create([
                            'promation_id' => $promotion->id,
                            'detials' => $request->detials,
                            'have_smaple' => $request->have_smaple,
                        ]);
                    }
                }
            }


            /*
            |--------------------------------------------------------------------------
            | 7. Add Promotion Type
            |--------------------------------------------------------------------------
            */
            TypeOfPromation::create([
                'promation_id' => $promotion->id,
                'type_id' => $request->promation_type_id,
            ]);


            /*
            |--------------------------------------------------------------------------
            | 8. Return Final JSON Model With All Relations
            |--------------------------------------------------------------------------
            */
            return $this->returnPromotionJson($promotion->id);
        });

    } catch (\Throwable $e) {

        return response()->json([
            'status' => false,
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
}



    public function updatePromotion(PromotionBaseRequest $request, Promation $promotion)
{
    try {
        return DB::transaction(function () use ($request, $promotion) {

            // 1) Update main promotion
            $promotion->update($request->validated());

            // 2) Update social media relations if provided
            if ($request->has('social_media')) {
                SocialMediaOfPromation::where('promation_id', $promotion->id)->delete();
                foreach ($request->social_media as $smId) {
                    SocialMediaOfPromation::create([
                        'promation_id' => $promotion->id,
                        'social_media_id' => $smId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            if ($request->has('social_media_types')) {
                TypeOfSocialMediaPromation::where('promation_id', $promotion->id)->delete();
                foreach ($request->social_media_types as $smtId) {
                    TypeOfSocialMediaPromation::create([
                        'promation_id' => $promotion->id,
                        'type_id' => $smtId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // 3) Influencer movement
            if ($request->filled('should_influencer_movment')) {
                $movement = InfluancherMovment::firstOrNew(['promation_id' => $promotion->id]);
                if ($request->should_influencer_movment === 'yes') {
                    $movement->location = $request->location;
                    $movement->save();

                    if ($request->hasFile('file_of_topic')) {
                        FileOfTopic::updateOrCreate(
                            ['promation_id' => $promotion->id],
                            ['file_path' => $request->file('file_of_topic')->store('promation_topics', 'public')]
                        );
                    }
                } else {
                    $movement->delete(); // Remove movement if no
                }
            }

            // 4) Registration
            if ($request->has('have_a_form')) {
                $registration = RegstrationOfPromation::firstOrNew(['promation_id' => $promotion->id]);
                $registration->have_a_form = $request->have_a_form;
                $registration->save();
            }

       // Section 5: Recommendations or Topics
if ($request->filled('have_a_form') && $request->have_a_form === 'yes') {
    $typeId = (int) $request->promation_type_id;

    // Define tables/models to update based on type or condition
    $tablesToUpdate = [];

    if ($typeId === 3 && $request->filled('text')) {
        $tablesToUpdate['Recommendation'] = [
            'text' => $request->text
        ];

        if ($request->hasFile('file_of_recommendation')) {
            $tablesToUpdate['FileOfRecommendation'] = [
                'file_path' => $request->file('file_of_recommendation')->store('files_of_recommendations', 'public')
            ];
        }
    } else {
        if ($request->topic_is_ready === 'yes') {
            $tablesToUpdate['TopicAlreadyReady'] = [
                'detials' => $request->detials,
                'have_smaple' => $request->have_smaple,
                'file_path' => $request->file('file_path')->store('media_files', 'public')
            ];

            if ($request->hasFile('file_of_topic')) {
                $tablesToUpdate['FileOfTopic'] = [
                    'file_path' => $request->file('file_of_topic')->store('promation_topics', 'public')
                ];
            }
        } else {
            $tablesToUpdate['TopicFromInfluancer'] = [
                'detials' => $request->detials,
                'have_smaple' => $request->have_smaple
            ];
        }
    }

    // Dynamically updateOrCreate in all relevant tables
    foreach ($tablesToUpdate as $modelName => $data) {
        $fullModel = "App\\Models\\$modelName";
        if (class_exists($fullModel)) {
            $fullModel::updateOrCreate(
                ['promation_id' => $promotion->id],
                $data
            );
        }
    }

    // Update type of promotion
    TypeOfPromation::updateOrCreate(
        ['promation_id' => $promotion->id],
        ['type_id' => $typeId]
    );
}

            return response()->json([
                'status' => true,
                'promation' => $promotion->fresh(),
            ]);
        });
    } catch (\Throwable $e) {
        return response()->json([
            'status' => false,
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
}
private function returnPromotionJson($id)
{
    $promotion = Promation::with([

        // 'client',
        // 'client.clientWithCR',
        // 'client.clientWithoutCR',

        // 'influencer',
        // 'influencer.categories',
        // 'influencer.typeOfInfluencer',
        // 'influencer.socialMediaLinks',

       // 'status',

        'socialMedia',
      //  'socialMedia.socialMedia',

        'socialMediaTypes',
      //  'socialMediaTypes.socialMediaPromationType',

        'movement',

        'regstration',

        'typeOfPromations',
     //   'typeOfPromations.promationType',

        'recommendations',
        'filesOfRecommendations',

        'topicAlreadyReadies',
        'topicFromInfluancers',
        'filesOfTopic',

    ])->find($id);

    // Convert to array
    $data = $promotion->toArray();

    // 🔥 Remove any null value OR empty array
    $data = array_filter($data, function ($value) {
        return !is_null($value) && $value !== [];
    });

    return response()->json([
        'status' => true,
        'promotion' => $data
    ]);
}

public function getPromotionsByClient(Request $request)
{
    // Validate incoming request
    $validated = $request->validate([
        'client_id' => 'required|exists:clients,id',
    ]);

    $clientId = $validated['client_id'];

    // Load all promotions related to the client
    $promotions = Promation::with([
     // 'client',
        // 'client.clientWithCR',
        // 'client.clientWithoutCR',

         'influencer.user',
        // 'influencer.categories',
        // 'influencer.typeOfInfluencer',
        // 'influencer.socialMediaLinks',

       // 'status',

        'socialMedia',
      //  'socialMedia.socialMedia',

        'socialMediaTypes',
      //  'socialMediaTypes.socialMediaPromationType',

        'movement',

        'regstration',

        'typeOfPromations',
     //   'typeOfPromations.promationType',

        'recommendations',
        'filesOfRecommendations',

        'topicAlreadyReadies',
        'topicFromInfluancers',
        'filesOfTopic',

    ])
    ->where('client_id', $clientId)
    ->orderBy('created_at', 'desc')
    ->get();

    return response()->json([
        'status' => true,
        'client_id' => $clientId,
        'count' => $promotions->count(),
        'promotions' => $promotions,
    ]);
}
    public function getPromotionsByStatus(Request $request)
{
    $request->validate([
        'influencer_id' => 'required|integer|exists:users,id',
        'status_id'     => 'required|integer|exists:promation_statuses,id',
    ]);

    try {
        $promotions = Promation::where('influencer_id', $request->influencer_id)
            ->where('status_id', $request->status_id)
            ->get();

        return response()->json([
            'status' => true,
            'promotions' => $promotions,
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}
public function getLastPromotionByInfluencer(Request $request)
{
    $request->validate([
        'influencer_id' => 'required|integer|exists:users,id',
    ]);

    try {

        $promotion = Promation::where('influencer_id', $request->influencer_id)
            ->with('topicAlreadyReadies')
            ->orderBy('id', 'desc')
            ->first();

        return response()->json([
            'status' => true,
            'promotion' => $promotion,
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}



}


/*



        if ($request->hasFile('file_of_recommendation')) {
                            $file = $request->file('file_of_recommendation');

                            FileOfRecommendation::create([
                                'promation_id' => $promotion->id,
                                'file_path' => $file->store('files_of_recommendations', 'public'),
                            ]);
                        }


//////////////////////////////////////////////////////////////////////////////////////////////////

    if ($request->hasFile('file_of_recommendation')) {
            $tablesToUpdate['FileOfRecommendation'] = [
                'file_path' => $request->file('file_of_recommendation')->store('files_of_recommendations', 'public')
            ];
        }
*/
