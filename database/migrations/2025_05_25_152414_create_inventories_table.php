<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('base_inventory_id')->default(0);
            $table->string('item_name');
            $table->string('hsn_code');
            $table->enum('stock_category', ['electronics', 'furniture', 'machinery']);
            $table->enum('unit', ['piece', 'set', 'kg', 'liter']);
            $table->decimal('worth');
            $table->enum('vendor', ['vendor_a', 'vendor_b', 'vendor_c']);
            $table->text('description')->nullable();
            $table->string('model_no')->nullable();
            $table->string('gm_code')->nullable();
            $table->string('brand_name')->nullable();
            $table->decimal('purchase_price')->nullable();
            $table->decimal('length')->nullable();
            $table->decimal('height')->nullable();
            $table->decimal('width')->nullable();
            $table->decimal('weight')->nullable();
            $table->decimal('volume')->nullable();
            $table->decimal('current')->nullable();
            $table->decimal('power')->nullable();
            $table->decimal('rental_information')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventories');
    }
}
