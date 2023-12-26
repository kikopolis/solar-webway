<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RouteInfo extends Model {
    use HasFactory;
    
    protected $fillable = [
        'uuid',
        'from_id',
        'to_id',
        'leg_id',
        'distance',
    ];
    protected $casts = [
        'distance' => 'integer',
    ];
    
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
