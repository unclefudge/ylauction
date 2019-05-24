<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuctionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Auction items
        Schema::create('auction_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->nullable();
            $table->unsignedInteger('parent')->nullable();
            $table->tinyInteger('status')->default(1);

            // Modify info
            $table->timestamps();
        });

        // Auction items
        Schema::create('auction_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cid')->nullable();
            $table->string('name', 255)->nullable();
            $table->text('description')->nullable();
            $table->integer('price')->default(0);
            $table->integer('reserve')->nullable();
            $table->unsignedInteger('highest_bid')->nullable();
            $table->integer('order')->nullable();
            $table->string('image1', 255)->nullable();
            $table->string('image2', 255)->nullable();
            $table->string('image3', 255)->nullable();
            $table->string('image4', 255)->nullable();
            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();
            $table->text('notes')->nullable();
            $table->tinyInteger('status')->default(1);

            // Foreign keys
            $table->foreign('cid')->references('id')->on('auction_categories')->onDelete('cascade');

            // Modify info
            $table->timestamps();
        });

        // Auction items
        Schema::create('auction_bids', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('aid')->nullable();
            $table->unsignedInteger('uid')->nullable();
            $table->integer('bid')->nullable();

            // Foreign keys
            $table->foreign('aid')->references('id')->on('auction_items')->onDelete('cascade');
            $table->foreign('uid')->references('id')->on('users')->onDelete('cascade');

            // Modify info
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
        Schema::dropIfExists('auction_bids');
        Schema::dropIfExists('auction_items');
        Schema::dropIfExists('auction_categories');
    }
}
