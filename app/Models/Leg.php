<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Leg extends Model {
    use HasFactory;
    
    protected $fillable = [
        'uuid',
        'price_list_id',
    ];
    
    public function routeInfo(): HasOne {
        return $this->hasOne(RouteInfo::class);
    }
    
    public function priceList(): BelongsTo {
        return $this->belongsTo(PriceList::class);
    }
    
    public function providers(): HasMany {
        return $this->hasMany(Provider::class);
    }
}
