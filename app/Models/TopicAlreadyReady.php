<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopicAlreadyReady extends Model
{

    // The table associated with the model.
    protected $table = 'topic_already_readies';
    protected $fillable = ['file_path','promation_id'];

    // Define relationships
    public function promation()
    {
        return $this->belongsTo(Promation::class);
    }

 
}

