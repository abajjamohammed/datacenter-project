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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');        // Who booked
            $table->unsignedBigInteger('resource_id');    // Which resource
            $table->dateTime('start_time');               // Start of booking
            $table->dateTime('end_time');                 // End of booking
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Active', 'Completed'])
                  ->default('Pending');                  // Booking status
            $table->string('justification')->nullable(); // Reason for booking
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
