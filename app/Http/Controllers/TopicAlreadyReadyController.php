<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Promation\TopicAlreadyReadyRequest;
use App\Models\TopicAlreadyReady;
use App\Models\FileOfTopic;


class TopicAlreadyReadyController extends Controller
{


    /**
     * Save topic_already_readies and optional files
     *
     * Expect TopicAlreadyReadyRequest with:
     * - file_path or files[] (file paths or uploaded files)
     */
    public function handle($promotion, TopicAlreadyReadyRequest $request)
    {
        // Save base record
        $tar = TopicAlreadyReady::create([
            'promation_id' => $promotion->id,
            'file_path'    => $request->input('file_path', ''), // primary path if provided
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Optional: extra files array
        $files = (array)$request->input('files', []);
        foreach ($files as $fp) {
            FileOfTopic::create([
                'file_path' => $fp,
                'promation_id' => $promotion->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $this->success(['message' => 'topic ready saved']);
    }
}
