<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialMediaOfPromation extends Model
{

    // The table associated with the model.
    protected $table = 'social_media_of_promations';
    protected $fillable = ['promation_id', 'social_media_id'];

    // Relationships

    public function promation()
    {
        return $this->belongsTo(Promation::class, 'promation_id');
    }
    public function socialMedia()
    {
        return $this->belongsTo(SocialMedia::class, 'social_media_id');
    }




}
