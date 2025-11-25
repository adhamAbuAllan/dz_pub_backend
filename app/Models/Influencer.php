<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Influencer extends Model
{
    // fillable property to allow mass assignment
    /**
           $table->foreignId('id')
          ->constrained('users')
          ->cascadeOnDelete()
          ->primary();
          $table->double('rating')->default(0);
          $table->string('bio',512)->nullable();
          $table->string('gender',10)->nullable();
          $table->date('date_of_birth')->nullable();
          $table->integer('type_id')->default(1);//from type of influencers table
          $table->integer('shake_number')->nullable();


     */
        public $incrementing = false; // because id is foreign key to users and primary
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'rating',
        'bio',
        'gender',
        'date_of_birth',
        'type_id',
        'shake_number',
    ];
    // table
    protected $table = 'influencers';

    // Relationship



    public function categories()
    {
        return $this->belongsToMany(Category::class, 'categories_of_influencers', 'influencer_id', 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
    public function socialMedias()
    {
        return $this->belongsToMany(SocialMedia::class, 'social_media_of_influencers', 'influencer_id', 'social_media_id');
    }
    public function typeOfInfluencer()
    {
        return $this->belongsTo(InfluencerTypes::class, 'type_id');
    }








}
