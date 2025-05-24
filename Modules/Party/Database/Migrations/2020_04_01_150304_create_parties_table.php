<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->json('title')->nullable();
            $table->json('description')->nullable();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('expired_at')->nullable();
            $table->integer('state_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->text('address')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->text('address_link')->nullable();
            $table->integer('sort_start')->default(1);
            $table->json('whatsapp_msg')->nullable();
            $table->json('acceptance_reply')->nullable();
            $table->json('rejection_reply')->nullable();
            $table->json('reminder_msg')->nullable();
            $table->json('dimensions')->nullable();
            $table->boolean('status')->default(false);
            $table->integer('next_step')->default(1);
            $table->integer('package_id')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('parties');
    }
}
