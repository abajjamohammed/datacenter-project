<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   //created by mohammed 04/01
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
            ->constrained('users')
            ->onDelete('cascade');   // fk for user
            $table->foreignId('resource_id')
            ->constrained('resources')
            ->onDelete('cascade');  // fk for resources
            $table->foreignId('reservation_id')
            ->nullable()
            ->constrained('reservations'); // fk for reservation
            $table->string('title');
            $table->text('description');
            $table->enum('incident_status', ['ouvert', 'en_cours', 'résolu'])->default('ouvert');
            $table->enum('priority', ['basse', 'moyenne', 'haute' ])->default('moyenne');
            $table->foreignId('resolved_by')
            ->nullable()
            ->constrained('users');   // fk for user
            $table->dateTime('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
