<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class   Influencer extends Model
{

        public $incrementing = false; // because id is foreign key to users and primary
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'rating',
        'bio',
        'gender',
        'date_of_birth',
        'type_id',
        'shake_number',
    ];
    // table
    protected $table = 'influencers';

    // Relationship



    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_of_influencers', 'influencer_id', 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
public function socialMediaLinks()
{
    return $this->hasMany(SocialMeidaOfInfluencer::class, 'influencer_id');
}

    public function addSocialMediaLink()
    {
        return $this->belongsToMany(SocialMedia::class, 'influencer_social_media')
                    ->withPivot('url')
                    ->withTimestamps();
    }
    public function typeOfInfluencer()
    {
        return $this->belongsTo(InfluencerTypes::class, 'type_id');
    }








}
