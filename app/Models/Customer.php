<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'image','status', 'email_verified_at','added_by',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function getStatusAttribute($value)
    {
        return ucfirst($value);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id')->withDefault();
    }


    public function plan()
    {

        return $this->hasOne(CustomerPlan::class)->orderByDesc('created_at')->where('is_current', 'yes');
    }
    public function customer_plans (){
            return $this->hasMany(CustomerPlan::class);
    }

    public function settings()
    {
        return $this->hasMany(CustomerSettings::class);
    }
    
    public function tickets(){
        return $this->hasMany(Ticket::class);
    }

    public function subscribes()
    {
        return $this->hasMany(Subscribe::class);
    }

    public function plans()
    {
        return $this->hasMany(Plan::class, 'admin_id', 'id');
    }

    public function plan_requests(){
        return $this->hasMany(BillingRequest::class,'admin_id', 'id');
    }

    public function customers(){
        return $this->hasMany(Customer::class,'admin_id', 'id');
    }
}
