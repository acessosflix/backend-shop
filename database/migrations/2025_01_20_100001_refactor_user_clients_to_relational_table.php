<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_clients', function (Blueprint $table) {
            // Remove unique constraint de email primeiro
            $table->dropUnique(['email']);
        });
        
        Schema::table('user_clients', function (Blueprint $table) {
            // Adiciona foreign key para users primeiro
            $table->foreignId('user_id')->after('id')->constrained('users')->onDelete('cascade');
            
            // Adiciona campo avatar
            $table->string('avatar')->nullable()->after('zipcode');
        });
        
        Schema::table('user_clients', function (Blueprint $table) {
            // Remove campos básicos que agora estão em users
            $table->dropColumn(['name', 'email', 'password', 'remember_token']);
        });
    }

    public function down(): void
    {
        Schema::table('user_clients', function (Blueprint $table) {
            // Restaura campos básicos primeiro
            $table->string('name')->after('id');
            $table->string('email')->after('name');
            $table->string('password')->after('email');
            $table->rememberToken()->after('password');
        });
        
        Schema::table('user_clients', function (Blueprint $table) {
            // Adiciona unique constraint de email
            $table->unique('email');
        });
        
        Schema::table('user_clients', function (Blueprint $table) {
            // Remove foreign key e campos adicionados
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'avatar']);
        });
    }
};
