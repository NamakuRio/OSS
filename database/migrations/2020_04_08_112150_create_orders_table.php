<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->enum('type', ['handphone', 'laptop', 'printer', 'komputer', 'powerbank']);
            $table->string('merk')->nullable();
            $table->string('color')->nullable();
            $table->text('complaint')->nullable();
            $table->string('completeness')->nullable();
            $table->string('cost')->nullable();
            $table->text('comment')->nullable();
            $table->foreignId('user_id');
            $table->enum('status', [1, 2, 3, 4])->default(1);
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
