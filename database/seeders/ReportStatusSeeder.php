<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ReportStatus;

class ReportStatusSeeder extends Seeder
{
    public function run(): void
    {
        ReportStatus::insert([
            ['name' => 'pending'],
            ['name' => 'reviewed'],
            ['name' => 'resolved'],
            ['name' => 'rejected'],
        ]);
    }
}

    
