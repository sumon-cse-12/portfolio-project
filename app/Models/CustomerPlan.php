<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerPlan extends Model
{
    protected $fillable = [
        'price','expire_date','plan_id','is_current','sms_sending_limit','max_contact','contact_group_limit',
        'sms_unit_price','free_sms_credit','coverage_ids','receive_whatsapp_sms','enable_for','api_availability','sender_id_verification',
        'short_description','unlimited_sms_send','unlimited_contact','unlimited_contact_group','payment_status','status','renew_date'];

    public function plan(){
        return $this->belongsTo(Plan::class,'plan_id')->withDefault();
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id', 'id')->withDefault();
    }
}
