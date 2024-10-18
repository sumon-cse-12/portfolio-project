<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Number extends Model
{
    protected $fillable = [
        'number', 'purch_price', 'sell_price','status','is_default','sms_capability','mms_capability','voice_capability','whatsapp_capability',
        'dynamic_gateway_id'
    ];

    public function admin(){
        return $this->belongsTo(User::class,'admin_id')->withDefault();
    }
    public function customer_numbers(){
        return $this->hasMany(CustomerNumber::class);
    }

    public function requests(){
        return $this->hasOne(NumberRequest::class);
    }
    public function accepted_request(){
        return $this->hasOne(NumberRequest::class)->where('status','accepted');
    }

    public function getStatusAttribute($value)
    {
        return ucfirst($value);
    }

    public function gateway(){
        return $this->belongsTo(DynamicGateway::class,'dynamic_gateway_id','id')->withDefault();
    }
}
