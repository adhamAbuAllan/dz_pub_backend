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
        Schema::create('social_media_of_promations', function (Blueprint $table) {
            $table->id();
            //promation_id
            $table->foreignId('promation_id')
                  ->constrained('promations')
                  ->cascadeOnDelete();
            //social_media_id
            $table->foreignId('social_media_id')
                  ->constrained('social_media')
                  ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_media_of_promations');
    }
};
