<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RouteInfo extends Model {
    protected $fillable = ['uuid', 'from_id', 'to_id', 'leg_id', 'distance'];
    
    public function from(): BelongsTo {
        return $this->belongsTo(Planet::class);
    }
    
    public function to(): BelongsTo {
        return $this->belongsTo(Planet::class);
    }
    
    public function leg(): BelongsTo {
        return $this->belongsTo(Leg::class);
    }
}
