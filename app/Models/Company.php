<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model {
    protected $fillable = ['uuid', 'name'];
    
    public function providers(): HasMany {
        return $this->hasMany(Provider::class);
    }
}
