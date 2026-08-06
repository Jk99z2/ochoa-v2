<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipo extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function propiedades()
    {
        return $this->hasMany(Propiedad::class);
    }
}
