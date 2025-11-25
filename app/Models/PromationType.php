<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromationType extends Model
{
    // The table associated with the model.
    protected $table = 'promation_types';
    protected $fillable = [
        'id',
        'name',
    ];
    // Relationships
    //belongsToMany
    public function socialMediaPromationTypes()
    {
        return $this->belongsToMany(SocialMediaPromationType::class, 'type_of_social_media_promations', 'type_of_promation_id', 'type_id');
    }


}
