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
            //we dont need to call user id and resources id twice we just have to call it the first time as a FK  :mohammed
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('resource_id')->references('id')->on('resources')->onDelete('cascade');
         //   $table->unsignedBigInteger('user_id');        // Who booked
         //   $table->unsignedBigInteger('resource_id');    // Which resource
            $table->dateTime('start_date');               // Start of booking      changed time to date  :mohammed
            $table->dateTime('end_date');                 // End of booking    changed time to date  :mohammed
            $table->enum('reservation_status', ['en_attente', 'approuvée', 'refusée', 'active', 'terminée'])
                  ->default('en_attente');        // changed ['Pending', 'Approved', 'Rejected', 'Active', 'Completed']  :mohammed
            $table->text('justification'); //->nullable(); // changed the type from string and removed nullable :mohammed
            $table->foreignId('approved_by')->references('id')->on('users')->nullable();
            $table->text('approval_comment')->nullable(); // approval/rejection reason :mohammed
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            
           
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
