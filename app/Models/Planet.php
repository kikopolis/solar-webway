<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planet extends Model {
    use HasFactory;
    
    public const MERCURY = 'mercury';
    public const VENUS   = 'venus';
    public const EARTH   = 'earth';
    public const MARS    = 'mars';
    public const JUPITER = 'jupiter';
    public const SATURN  = 'saturn';
    public const URANUS  = 'uranus';
    public const NEPTUNE = 'neptune';
    protected $fillable = [
        'uuid',
        'name',
    ];
}
