<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Promation\PromotionNoLocationRequest;

class NoLocationController extends Controller
{

    /**
     * This controller contains any logic that specifically belongs to the "no location" path.
     * In the master controller we already created registration_of_promations and social media.
     *
     * @param \App\Models\Promation $promotion
     * @param PromotionNoLocationRequest $request
     */
    public function handle($promotion, PromotionNoLocationRequest $request)
    {
        // Here we could do any other "no location" specific work (logging, notifications, etc.)
        return $this->success(['message' => 'no-location handled']);
    }
}
