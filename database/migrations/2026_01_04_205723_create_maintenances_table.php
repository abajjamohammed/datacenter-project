<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // created by mohammed 04/01
    public function up(): void
    {
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_id')
            ->constrained('resources')
            ->onDelete('cascade');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->text('description');
            $table->foreignId('created_by')
            ->constrained('users')
            ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
