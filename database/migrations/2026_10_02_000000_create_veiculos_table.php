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
        Schema::create('veiculos', function (Blueprint $table) {
            $table->id();
            $table->string('marca', 60);
            $table->string('modelo', 80);
            $table->string('versao', 120)->nullable();
            $table->string('categoria', 30);
            $table->unsignedSmallInteger('ano_fabricacao');
            $table->unsignedSmallInteger('ano_modelo');
            $table->string('cor', 40);
            $table->string('combustivel', 20);
            $table->string('cambio', 20);
            $table->unsignedInteger('quilometragem')->default(0);
            $table->string('placa', 8)->nullable()->unique();
            $table->decimal('preco', 12, 2);
            $table->string('status', 20)->default('disponivel')->index();
            $table->string('foto_url', 500)->nullable();
            $table->text('descricao')->nullable();
            $table->timestamps();

            $table->index(['marca', 'modelo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('veiculos');
    }
};
