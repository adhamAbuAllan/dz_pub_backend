<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //  fillable property to allow mass assignment
    protected $fillable = ['name'];
    //table 
    protected $table = 'categories';    

    // Define relationship to CategoryOfInfluencer model
  //belongsToMany
    public function influencers()
    {
        return $this->belongsToMany(Influencer::class, 'categories_of_influencers', 'category_id', 'influencer_id');
    }

    
}
