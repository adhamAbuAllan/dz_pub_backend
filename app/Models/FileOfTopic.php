<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileOfTopic extends Model
{
    // The table associated with the model.
    protected $table = 'files_of_topics';
    protected $fillable = [
        'promation_id',
        'file_path',
    ];

    // Relationships


    public function promation()
    {
        return $this->belongsTo(Promation::class);
    }
    
    
}
