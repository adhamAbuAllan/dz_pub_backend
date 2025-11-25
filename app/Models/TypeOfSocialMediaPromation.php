<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeOfSocialMediaPromation extends Model
{
    //  
    protected $table = 'type_of_social_media_promations';
    protected $fillable = [
        'promation_id',
        'type_id',
    ];
    // Relationships
    public function promation()
    {
        return $this->belongsTo(Promation::class, 'promation_id');
    }
    public function socialMediaPromationType()
    {
        return $this->belongsTo(SocialMediaPromationType::class, 'type_id');
    }

}
