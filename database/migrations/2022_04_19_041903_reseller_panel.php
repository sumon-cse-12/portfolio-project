<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ResellerPanel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // For Reseller Customer
        Schema::table('customers', function (Blueprint $table) {
            $table->enum('type',['normal','master_reseller','master_reseller_customer','reseller', 'reseller_customer'])->default('normal');
            $table->enum('added_by', ['admin','master_reseller','reseller']);
        });
        // For Reseller Plan
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('sms_limit');
            $table->enum('plan_type',['reseller','normal', 'master_reseller', 'reseller_customer', 'master_reseller_customer'])->default('normal');
            $table->enum('masking',['yes','no'])->default('no');
            $table->enum('non_masking',['yes','no'])->default('yes');
            $table->string('masking_rate')->default('0');
            $table->string('non_masking_rate')->default('0');
            $table->string('custom_date')->nullable();
            $table->enum('recurring_type',['weekly','monthly','yearly', 'custom'])->default('weekly');
            $table->enum('added_by',['admin','master_reseller','reseller'])->default('admin');
            $table->longText('module')->nullable();
            $table->integer('non_masking_credit')->default(0);
            $table->integer('masking_credit')->default(0);
            $table->string('credit_amount')->default(0);
        });

        Schema::table('sender_ids', function (Blueprint $table) {
            $table->string('from')->nullable()->change();
            $table->enum('is_paid', ['yes','no',])->default('no');
        });

        Schema::table('sms_queues', function (Blueprint $table) {
            $table->enum('from_type', ['number','sender_id','whatsapp'])->nullable();
        });

        Schema::table('customer_settings', function (Blueprint $table) {
            $table->longText('value')->change();
        });
        Schema::table('customer_plans', function (Blueprint $table) {
            $table->enum('is_current', ['yes','no',])->default('no');
            $table->string('masking_rate')->default('0');
            $table->string('non_masking_rate')->default('0');
            $table->longText('module')->nullable();
            $table->date('expire_date')->nullable();
//            Drop
            $table->dropColumn('sms_limit');
            $table->dropColumn('available_sms');
        });
        Schema::table('billing_requests', function (Blueprint $table) {
            $table->enum('payment_status', ['paid','unpaid',])->default('unpaid');
        });
        DB::statement("ALTER TABLE sender_ids CHANGE COLUMN status status ENUM('approved', 'rejected', 'pending', 'review', 'review_pending') NOT NULL DEFAULT 'pending'");

        Schema::table('numbers', function (Blueprint $table) {
            $table->enum('is_default', ['yes','no',])->default('no');
        });
        Schema::table('customer_numbers', function (Blueprint $table) {
            $table->enum('is_default', ['yes','no',])->default('no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('added_by');
        });
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('plan_type');
            $table->dropColumn('masking');
            $table->dropColumn('non_masking');
            $table->dropColumn('masking_rate');
            $table->dropColumn('non_masking_rate');
            $table->dropColumn('recurring_type');
            $table->dropColumn('custom_date');
            $table->dropColumn('added_by');
            $table->dropColumn('module');
            $table->integer('sms_limit');
            $table->dropColumn('credit_amount');
        });
        Schema::table('sender_ids', function (Blueprint $table) {
            $table->string('from')->change();
            $table->dropColumn('is_paid');

        });
        Schema::table('customer_settings', function (Blueprint $table) {
            $table->string('value')->change();
        });
        Schema::table('customer_plans', function (Blueprint $table) {
            $table->dropColumn('is_current');
            $table->dropColumn('masking_rate');
            $table->dropColumn('non_masking_rate');
            $table->dropColumn('expire_date');
//            Restore Column
            $table->integer('sms_limit');
            $table->integer('available_sms');
        });
        Schema::table('billing_requests', function (Blueprint $table) {
            $table->dropColumn('payment_status');
        });
        Schema::table('sms_queues', function (Blueprint $table) {
            $table->dropColumn('from_type');
        });
        DB::statement("ALTER TABLE sender_ids CHANGE COLUMN status status ENUM('approved', 'rejected', 'pending') NOT NULL DEFAULT 'pending'");

        Schema::table('numbers', function (Blueprint $table) {
            $table->dropColumn('is_default');
        });
        Schema::table('customer_numbers', function (Blueprint $table) {
            $table->dropColumn('is_default');
        });
    }
}
