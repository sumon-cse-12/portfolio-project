<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instrument extends Model
{
    use HasFactory;

    public function instrumentDetails()
    {
        return $this->hasMany(InstrumentDetail::class, 'instrument_id', 'id');
    }
}
