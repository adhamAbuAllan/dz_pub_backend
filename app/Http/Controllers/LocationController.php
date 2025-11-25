<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Promation\PromotionLocationRequest;
use App\Models\InfluancherMovment;

class LocationController extends Controller
{

    /**
     * @param \App\Models\Promation $promotion
     * @param PromotionLocationRequest $request
     */
    public function handle($promotion, PromotionLocationRequest $request)
    {
        // Create influancher_movments row(s). Request may provide array of locations or single.
        $location = $request->input('location', null);

         $loc = $request->input('location');
            if ($loc) {
                InfluancherMovment::create([
                    'location' => $loc,
                    'promation_id' => $promotion->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

        return $this->success(['message' => 'locations saved']);
    }
}
