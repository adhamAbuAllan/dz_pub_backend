<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopicFromInfluancer extends Model
{
    protected $table = 'topic_from_influancers';

    

    protected $fillable = [
        'have_smaple',
        'detials',
        'promation_id'
    ];

    // Define relationships
    public function promation()
    {
        return $this->belongsTo(Promation::class);
    }



}
