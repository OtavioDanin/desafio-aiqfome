<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('favorite_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('client_id')->nullable(false);
            $table->bigInteger('product_id')->nullable(false);
            $table->string('titulo', '255');
            $table->string('imagem', '255');
            $table->string('preco', '10');
            $table->string('review', '10');
            $table->timestamp('data_criacao')->useCurrent();

            $table->foreign('client_id')
                  ->references('id')
                  ->on('clients')
                  ->onDelete('cascade');
            
            $table->index('product_id', 'idx_favoritos_product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorite_products');
    }
};
