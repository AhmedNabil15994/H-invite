<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->integer('party_id');
            $table->integer('contact_id');
            $table->integer('invitations')->default(1);
            $table->integer('related_invitation_id')->nullable();
            $table->integer('status')->default(2);
            $table->dateTime('attended_at')->nullable();
            $table->integer('invitation_number')->nullable();
            $table->dateTime('scanned_at')->nullable();
            $table->dateTime('sent_at')->nullable();
            $table->dateTime('reminded_at')->nullable();
            $table->longText('queue_data')->nullable();
            $table->integer('step')->default(1);
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
        Schema::dropIfExists('invitations');
    }
}
