<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialMediaPromationType extends Model
{
    // The table associated with the model.
    protected $table = 'social_media_promation_types';
    protected $fillable = ['name'];

    // Relationships
    public function promationTypes()
    {
        return $this->belongsToMany(PromationType::class, 'type_of_social_media_promations', 'type_id', 'proma  tion_id');
    }
}
