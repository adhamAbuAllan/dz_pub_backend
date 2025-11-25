<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promation extends Model
{

    // The table associated with the model.
    protected $table = 'promations';
    protected $fillable = [
        'client_id',
        'influencer_id',
        'requirements',
        'status_id',
        'price',
        'time_line',
        'should_influencer_movment',
    ];
    // Define relationships


    public function topicFromInfluancers()
    {
        return $this->hasMany(TopicFromInfluancer::class, 'promation_id');
    }
    public function topicAlreadyReadies()
    {
        return $this->hasMany(TopicAlreadyReady::class, 'promation_id');
    }
    public function socialMediaOfPromations()
    {
        return $this->hasMany(SocialMediaOfPromation::class, 'promation_id');
    }
    //belongsToMany
    //typeOfPromations
    public function typeOfPromations()
    {
        return $this->belongsToMany(TypeOfPromation::class, 'type_of_promations', 'promation_id', 'type_id');
    }
    public function socialMediaPromationType()
    {
        return $this->belongsTo(SocialMediaPromationType::class, 'social_media_promation_type_id');
    }
    public function regstrationOfPromation()
    {
        return $this->hasOne(RegstrationOfPromation::class, 'promation_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function influencer()
    {
        return $this->belongsTo(Influencer::class);
    }
    public function status()
    {
        return $this->belongsTo(PromationStatus::class, 'status_id');
    }








}
