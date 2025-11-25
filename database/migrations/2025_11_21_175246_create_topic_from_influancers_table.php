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
        Schema::create('topic_from_influancers', function (Blueprint $table) {
            $table->id();
            //have_smaple
            $table->string('have_smaple',3);//yes/no
            //detials
            $table->text('detials',512);
            //promation_id
            $table->foreignId('promation_id')
                  ->constrained('promations')
                  ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topic_from_influancers');
    }
};
