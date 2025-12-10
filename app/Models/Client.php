<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model

{

    // fillable property to allow mass assignmentp
    protected $fillable = [
        'id',
        'is_have_cr',
    ];
    // table
    protected $table = 'clients';

    // Relationship



    public function clientWithCR()
    {
        return $this->hasOne(ClientWithCR::class);
    }
    public function clientWithoutCR()
    {
        return $this->hasOne(ClientWithoutCR::class);
    }
   public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

public function customPromotion()
{
    return $this->hasOne(CustomPromotion::class);
}


}
