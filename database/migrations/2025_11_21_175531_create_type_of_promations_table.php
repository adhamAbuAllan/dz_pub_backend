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
        Schema::create('type_of_promations', function (Blueprint $table) {
            $table->id();
            //promation_id
            $table->foreignId('promation_id')
                  ->constrained('promations')
                  ->cascadeOnDelete();
                  //type_id from promation_types table
            $table->foreignId('type_id')
                  ->constrained('promation_types')
                  ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_of_promations');
    }


};
