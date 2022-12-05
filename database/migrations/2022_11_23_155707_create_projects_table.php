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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->integer('userId');
            $table->integer('chainId');
            $table->string('name');
            $table->string('collection_uid')->unique(); // Contract Address on ETH, Magic Eden symbol on SOL
            $table->string('image')->nullable();
            $table->string('discord_guild_id')->unique();
            $table->string('discord_channel_id')->nullable();
            $table->float('karmaValue', 8, 2);
            $table->dateTime('lastRefreshed')->nullable();
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
        Schema::dropIfExists('projects');
    }
};
