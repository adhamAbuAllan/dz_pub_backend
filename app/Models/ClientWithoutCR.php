<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientWithoutCR extends Model
{
    // The table associated with the model.
    protected $table = 'clients_without_cr';
    protected $fillable = [
        'client_id',
        'nickname',
        'identity_image',
    ];
        protected $casts = [
        'identity_image' => 'encrypted'
    ];
    // Relationships


    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public $timestamps = true;

}
