<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promation extends Model
{
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

    public function regstration()
    {
        return $this->hasOne(RegstrationOfPromation::class, 'promation_id');
    }

    public function movement()
    {
        return $this->hasOne(InfluancherMovment::class, 'promation_id');
    }

    public function topicFromInfluancers()
    {
        return $this->hasMany(TopicFromInfluancer::class, 'promation_id');
    }

    public function topicAlreadyReadies()
    {
        return $this->hasMany(TopicAlreadyReady::class, 'promation_id');
    }

    public function filesOfTopic()
    {
        return $this->hasMany(FileOfTopic::class, 'promation_id');
    }

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class, 'promation_id');
    }

    public function filesOfRecommendations()
    {
        return $this->hasManyThrough(
            FileOfRecommendation::class,
            Recommendation::class,
            'promation_id',
            'recommendation_id'
        );
    }

    public function socialMedia()
    {
        return $this->hasMany(SocialMediaOfPromation::class, 'promation_id');
    }

    public function socialMediaTypes()
    {
        return $this->hasMany(TypeOfSocialMediaPromation::class, 'promation_id');
    }

    public function typeOfPromations()
    {
        return $this->hasMany(TypeOfPromation::class, 'promation_id');
    }
}

