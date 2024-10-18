<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSenderIdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sender_ids', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id');
            $table->string('sender_id')->unique();
            $table->string('from');
            $table->enum('status', ['pending', 'approved','rejected'])->default('pending');
            $table->timestamp('expire_date')->nullable();
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
        Schema::dropIfExists('sender_ids');
    }
}
