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
        Schema::create('regstration_of_promations', function (Blueprint $table) {
            $table->id();
            //promatino_id

            $table->foreignId('promation_id')->constrained('promations')->cascadeOnDelete();
            

                  //have_a_form
            $table->string('have_a_form',3);//yes/no
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regstration_of_promations');
    }
};
