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
        Schema::create('clients_without_cr', function (Blueprint $table) {
            //cleint_id
            $table->foreignId('client_id')
                  ->constrained('clients')
                  ->cascadeOnDelete();
                  //name
            //nickname
            $table->string('nickname',30);
            //identity_image
            $table->text('identity_image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_without_c_r_s');
    }
};
