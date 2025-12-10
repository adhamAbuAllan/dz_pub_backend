<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    // The table associated with the model.
    protected $table = 'social_media';
    protected $fillable = ['name',];

    // Relationships
   //belongsToMany
    public function influencers()
    {
        return $this->belongsToMany(
            Influencer::class,
            'social_meida_of_influencers',
             'social_media_id',
             'influencer_id');
    }


}
