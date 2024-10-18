<?php

namespace App\Models;

use Mavinoo\Batch\Traits\HasBatch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsQueue extends Model
{
    use HasFactory,HasBatch;
    protected $dates=['schedule_datetime','delivered_at'];

    protected $fillable=['body','message_id','from','schedule_datetime','to','schedule_completed','campaign_id','message_files',
        'delivered_at','response_code','response_id','status','from_type','dynamic_gateway_id'];

    public function user(){
        return $this->belongsTo(Customer::class,'customer_id')->withDefault();
    }

    public function message(){
        return $this->belongsTo(Message::class, 'message_id', 'id')->withDefault();
    }


}
