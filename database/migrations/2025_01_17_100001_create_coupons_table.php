<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('Código do cupom');
            $table->text('description')->nullable()->comment('Descrição do cupom');
            $table->decimal('discount_percentage', 5, 2)->comment('Desconto em porcentagem (máximo 50%)');
            $table->date('valid_until')->nullable()->comment('Data de validade');
            $table->boolean('is_active')->default(true)->comment('Status do cupom');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
