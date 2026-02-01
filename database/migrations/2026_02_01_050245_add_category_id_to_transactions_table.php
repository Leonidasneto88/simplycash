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
        Schema::table('transactions', function (Blueprint $table) {
            // Verifica se a coluna já não existe para evitar erros
            if (!Schema::hasColumn('transactions', 'category_id')) {
                $table->foreignId('category_id')
                      ->after('user_id') // Organiza a coluna após o user_id
                      ->nullable()
                      ->constrained()
                      ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // É necessário remover a constraint da chave estrangeira antes de deletar a coluna
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};