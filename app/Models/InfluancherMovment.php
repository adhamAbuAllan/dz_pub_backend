<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfluancherMovment extends Model
{
    // The table associated with the model.
    protected $table = 'influancher_movments';
    protected $fillable = [
        'promation_id',
        'location',
    ];
    // Relationships


    public function promation()
    {
        return $this->belongsTo(Promation::class);
    }
    
    
 
}
