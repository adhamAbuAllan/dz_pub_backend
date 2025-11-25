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
        Schema::create('type_of_social_media_promations', function (Blueprint $table) {
            $table->id();
            //promation_id , from promations table
            $table->foreignId('promation_id')
                  ->constrained('promations')
                  ->cascadeOnDelete();
                  //type_id , from  social_media_promation_type table
            $table->foreignId('type_id')
                  ->constrained('social_media_promation_types')
                  ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_of_social_media_promations');
    }
};
