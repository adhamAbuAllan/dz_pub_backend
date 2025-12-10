<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileOfRecommendation extends Model
{
    // The table associated with the model.
    protected $table = 'files_of_recommendations';
    protected $fillable = [
        'recommendation_id',
        'file_path',
    ];
    // Relationships


    public function recommendation()
    {
        return $this->belongsTo(Recommendation::class);
    }



}
