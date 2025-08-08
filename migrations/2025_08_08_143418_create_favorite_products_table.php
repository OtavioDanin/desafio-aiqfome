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
            $table->bigInteger('cliente_id');
            $table->bigInteger('produto_id')->nullable(false);
            $table->timestamp('data_adicao')->useCurrent();

            $table->foreign('cliente_id')
                  ->references('id')
                  ->on('clients')
                  ->onDelete('cascade');
            
            $table->index('produto_id', 'idx_favoritos_produto_id');
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
