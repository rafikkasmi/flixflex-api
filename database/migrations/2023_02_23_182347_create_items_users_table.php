<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items_users', function (Blueprint $table) {
            $table->unsignedBigInteger("user_id"); 
            $table->unsignedBigInteger("item_id");
            //BY CONVENTION FOR THE TYPE WE WILL PUT, 0=>MOVIES, 1=>TV SERIES
            $table->unsignedBigInteger("item_type");
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items_users');
    }
};
