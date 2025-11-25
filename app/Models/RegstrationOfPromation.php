<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegstrationOfPromation extends Model
{

    // The table associated with the model.
    protected $table = 'regstration_of_promations';
    protected $fillable = [
        'promation_id',
        'have_a_form',
    ];
    // Define relationships
    public function client()
    {
        return $this->belongsTo(Client::class);
    }



}
