<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{

    // The table associated with the model.
    protected $table = 'recommendations';
    protected $fillable = [
        'text',
        'promation_id',
    ];
    // Define relationships

    public function promation()
    {
        return $this->belongsTo(Promation::class);
    }





}
