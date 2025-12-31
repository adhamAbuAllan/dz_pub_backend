<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('reporter_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('reported_id')
                ->constrained('users')
                ->cascadeOnDelete();

                $table->text('content',2000);
                 $table->foreignId('report_status_id')
                    ->constrained('report_statuses')
                    ->default(1); // by default 'pending'

            $table->timestamps();
        });
    }

     public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['report_status_id']);
            $table->dropColumn('report_status_id');
        });
    }
};
