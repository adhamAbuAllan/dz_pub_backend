<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Promation\ScriptPromotionRequest;
use App\Models\Recommendation;
use App\Models\FileOfRecommendation;


class ScriptController extends Controller
{


    /**
     * Handles script-type promation (recommendations + optional files)
     *
     * Expect ScriptPromotionRequest to validate:
     * - recommendations: array of ['text' => '...']
     * - optionally files: array of uploaded files or file paths (key: recommendation_files[index])
     */
    public function handle($promotion, ScriptPromotionRequest $request)
    {
        $recs = (array)$request->input('recommendations', []);

        foreach ($recs as $recInput) {
            $rec = Recommendation::create([
                'text' => $recInput['text'] ?? '',
                'promation_id' => $promotion->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // optional files: if request supplies file paths or uploaded files keyed by recommendation index
            if ($request->has('recommendation_files')) {
                $files = (array)$request->input('recommendation_files', []);
                // simple approach: store any provided file_path strings
                foreach ($files as $filePath) {
                    FileOfRecommendation::create([
                        'file_path' => $filePath,
                        'recommendation_id' => $rec->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        return $this->success(['message' => 'script recommendations saved']);
    }
}
