<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Municipio extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function propiedades(): HasMany
    {
        return $this->hasMany(Propiedad::class);
    }
}
