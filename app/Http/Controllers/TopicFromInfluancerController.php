<?php

// namespace App\Http\Controllers;

// use App\Http\Controllers\Controller;
// use App\Http\Requests\Promation\TopicFromInfluancerRequest;
// use App\Models\TopicFromInfluancer;

// class TopicFromInfluancerController extends Controller
// {


//     /**
//      * Save a topic_from_influancers row (influencer will provide topic details)
//      */
//     public function handle($promotion, TopicFromInfluancerRequest $request)
//     {
//         TopicFromInfluancer::create([
//             'have_smaple' => $request->input('have_smaple', 'no'),
//             'detials' => $request->input('detials', ''),
//             'promation_id' => $promotion->id,
//             'created_at' => now(),
//             'updated_at' => now(),
//         ]);

//         return $this->success(['message' => 'topic_from_influancer saved']);
//     }
// }
