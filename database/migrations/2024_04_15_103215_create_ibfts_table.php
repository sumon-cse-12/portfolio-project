<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ibfts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('passport');
            $table->string('instruction');
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('amount');
            $table->string('percentage');
            $table->string('sms_code');
            $table->text('conditional_addproval')->nullable();
            $table->string('vjut_code');
            $table->unsignedInteger('agent');
            $table->enum('status', ['declined', 'approved'])->default('declined');
            $table->enum('initalizing', ['processing', 'finished'])->default('processing');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ibfts');
    }
};
