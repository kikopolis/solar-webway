<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PriceList extends Model {
    protected $fillable = ['uuid', 'valid_until'];
    protected $casts = [
        'valid_until' => 'immutable_datetime',
    ];
    
    public function legs(): HasMany {
        return $this->hasMany(Leg::class);
    }
}
