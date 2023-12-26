<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Provider extends Model {
    use HasFactory;
    
    protected $fillable = [
        'uuid',
        'price',
        'flight_start',
        'flight_end',
        'company_id',
        'leg_id',
    ];
    protected $casts = [
        'flight_start' => 'immutable_datetime',
        'flight_end'   => 'immutable_datetime',
        'price'        => 'float',
    ];
    
    public function company(): BelongsTo {
        return $this->belongsTo(Company::class);
    }
    
    public function leg(): BelongsTo {
        return $this->belongsTo(Leg::class);
    }
}
