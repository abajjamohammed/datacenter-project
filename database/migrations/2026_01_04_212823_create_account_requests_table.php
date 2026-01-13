<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    //created by mohammed 04/01
    public function up(): void
    {
        Schema::create('account_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique(); 
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('department')->nullable();
            $table->enum('profile', ['ingénieur', 'enseignant', 'doctorant']);  // Profil it can be (ingénieur, enseignant, doctorant)
            $table->text('justification'); // why u need the account
            $table->enum('status', ['en_attente', 'approuvée', 'refusée'])->default('en_attente');
            $table->foreignId('processed_by') // the user who traited the demande
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            $table->timestamp('processed_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_requests');
    }
};
