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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // Resource name
            $table->string('type');           // Server, VM, Storage, Network
            $table->string('specs')->nullable();  // CPU, RAM, Storage type, OS
            $table->integer('capacity')->nullable(); // Storage size, VM slots, etc.
            $table->string('status')->default('available'); // available, maintenance, booked
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
