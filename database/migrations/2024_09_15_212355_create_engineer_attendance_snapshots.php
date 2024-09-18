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
        Schema::create('engineer_attendance_snapshots', function (Blueprint $table) {
            $table->id();
            $table->string('engineer_id');
            $table->enum('status', ['Hadir', 'Keluar', 'Absen']);
            $table->string('check_in_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engineer_attendance_snapshots');
    }
};
