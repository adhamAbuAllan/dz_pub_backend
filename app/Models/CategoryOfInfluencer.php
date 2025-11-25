<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryOfInfluencer extends Model
{
    //  fillable property to allow mass assignment
    protected $fillable = ['influencer_id', 'category_id'];
    //table 
    protected $table = 'categories_of_influencers';

    // Define relationship to Influencer model
    public function influencer()
    {
        return $this->belongsTo(Influencer::class);
    }
    // Define relationship to Category model
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}

