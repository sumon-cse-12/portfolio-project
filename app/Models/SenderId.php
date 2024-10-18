<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SenderId extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'sender_id', 'status','expire_date','dynamic_gateway_id','is_paid'];
    protected $dates=['expire_date'];
    public function customer(){
        return $this->belongsTo(Customer::class)->withDefault();
    }

    public function detail(){
        return $this->hasOne(SenderIdDetail::class, 'sender_id', 'id')->first();
    }

    public function gateway(){
        return $this->belongsTo(DynamicGateway::class,'dynamic_gateway_id', 'id')->withDefault();
    }
}
