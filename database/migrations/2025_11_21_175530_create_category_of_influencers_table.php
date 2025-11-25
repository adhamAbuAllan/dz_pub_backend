<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('category_of_influencers', function (Blueprint $table) {
            $table->id();
            //influencer_id
            $table->foreignId('influencer_id')
                  ->constrained('influencers')
                  ->cascadeOnDelete();
                  //category_id from categories table
            $table->foreignId('category_id')
                  ->constrained('categories')
                    ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_of_influencers');
    }
};
