<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    //created by mohammed 04/01

    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id') 
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null'); // we keep the history even if the user has been deleted
            $table->string('action');
            $table->text('description');
            $table->string('model_type')->nullable();// manuel Polymorphism (to link to any model : Reservation, Incident...) Ex: "App\Models\Reservation"
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();  // browser, devise infos
            $table->timestamp('created_at')->useCurrent();  // creatd at is enough we dont need updated at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
