<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerNumber extends Model
{
    protected $fillable=['number_id','customer_id','cost','number','forward_to','forward_to_dial_code','expire_date','is_default',
        'sms_capability','mms_capability','voice_capability','whatsapp_capability','dynamic_gateway_id'];
    protected $dates=['expire_date'];
    public function admin_number(){
        return $this->belongsTo(Number::class,'number_id', 'id')->withDefault();
    }

    public function customer(){
        return $this->belongsTo(Customer::class)->withDefault();
    }
}
