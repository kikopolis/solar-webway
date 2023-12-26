<?php

namespace App\Models;

use App\Http\Resources\PriceListResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PriceList extends Model {
    use HasFactory;
    
    protected $fillable = [
        'uuid',
        'valid_until',
    ];
    protected $casts = [
        'valid_until' => 'immutable_datetime',
    ];
    
    public function legs(): HasMany {
        return $this->hasMany(Leg::class);
    }
    
    public static function getLatestAsResource(): PriceListResource {
        $price_list = PriceList::latest('valid_until')->first();
        return new PriceListResource($price_list);
    }
    
    public function isExpired(): bool {
        return $this->valid_until->isPast();
    }
}
