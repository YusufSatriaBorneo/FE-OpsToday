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
        Schema::create('engineer_activities', function (Blueprint $table) {
            $table->id();
            $table->string('ticketNo'); // Nomor tiket
            $table->string('engineer_id'); // ID engineer sebagai varchar
            $table->string('status')->default('In Progress'); // Status
            $table->boolean('isOnProgress')->default(0); // Status on progress
            $table->timestamp('completion_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engineer_activities');
    }
};
