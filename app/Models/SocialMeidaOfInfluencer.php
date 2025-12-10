<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialMeidaOfInfluencer extends Model
{
    // The table associated with the model.
    protected $table = 'social_meida_of_influencers';
protected $fillable = ['influencer_id', 'social_media_id','url_of_soical'];

    // Relationships

    public function influencer()
    {
        return $this->belongsTo(Influencer::class, 'influencer_id');
    }
    public function socialMedia()
    {
        return $this->belongsTo(SocialMedia::class, 'social_media_id');
    }


}
