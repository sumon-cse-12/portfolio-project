<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'title', 'plan_type','price','status','added_by','custom_date','recurring_type','admin_id','sms_sending_limit','max_contact','contact_group_limit',
        'sms_unit_price','free_sms_credit','enable_for','api_availability','sender_id_verification','set_as_popular',
        'short_description','unlimited_sms_send','unlimited_contact','unlimited_contact_group','coverage_ids'];

    public function getStatusAttribute($value)
    {
        return ucfirst($value);
    }
    public function admin(){
        return $this->belongsTo(User::class,'admin_id')->withDefault();
    }

    public function customer_plans(){
        return $this->hasMany(CustomerPlan::class);
    }

    public function requests(){
        return $this->hasMany(BillingRequest::class);
    }


}
