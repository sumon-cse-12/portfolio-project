<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsQueuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_queues', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('message_id');
            $table->text('body');
            $table->string('from');
            $table->string('to');
            $table->dateTime('schedule_datetime')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->enum('schedule_completed',['yes','no'])->default('no');
            $table->text('message_files')->nullable()->comment('MMS files in json encoded');
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
        Schema::dropIfExists('sms_queues');
    }
}
