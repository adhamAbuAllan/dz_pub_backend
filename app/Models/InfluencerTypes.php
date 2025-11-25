<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfluencerTypes extends Model
{
    protected $table = 'influencer_types';
    protected $fillable = [
        'id',
        'name',
    ];
    public function influencers()
    {
        return $this->hasMany(Influencer::class, 'type_id');
    }



}
