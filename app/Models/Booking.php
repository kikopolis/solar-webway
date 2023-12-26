<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model {
    protected $fillable = [
        'uuid',
        'first_name',
        'last_name',
        'total_price',
        'total_distance',
        'duration_in_minutes',
        'departure',
        'arrival',
        'legs',
        'company_id',
        'price_list_id',
    ];
    protected $casts = [
        'legs'      => 'array',
        'departure' => 'datetime',
        'arrival'   => 'datetime',
    ];
    
    public function company(): BelongsTo {
        return $this->belongsTo(Company::class);
    }
    
    public function priceList(): BelongsTo {
        return $this->belongsTo(PriceList::class);
    }
}
