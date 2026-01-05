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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->after('id'); //->nullable(); i removed nullable, bcs why its nullable! every user should have a role  :mohammed
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('restrict');  //('set null'); restrict is better, it doesnt allow to delete the role as long as there are users use this role   : mohammed   
              });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};
