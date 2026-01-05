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
            $table->foreignId('category_id')
            ->constrained('resource_categories')
            ->onDelete('restrict');      // i changed type with category id  :mohammed  // Server, VM, Storage, Network
            $table->text('description');        
            $table->json('specifications')->nullable();  // CPU, RAM, Storage type, OS | i changes the type from string to json, it gonna be better when we gonna need it : mohammed
            $table->enum('resource_status', ['disponible', 'réservée', 'maintenance', 'hors_service'])->default('disponible');  //
            $table->string('location')->nullable(); // the physical location where the resource exist :mohammed
            $table->foreignId('responsable_id')
                 ->nullable()
                 ->constrained('users')
                 ->onDelete('set null');
            $table->boolean('is_active')->default(true);
          //  $table->integer('capacity')->nullable(); // Storage size, VM slots, etc.     i deleted it bcs the capacity exist in the specifications :mohammed
          //  $table->string('status')->default('available'); // available, maintenance, booked
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
