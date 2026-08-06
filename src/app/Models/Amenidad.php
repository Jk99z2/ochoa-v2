<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Amenidad extends Model
{
    protected $table = 'amenidades';
    protected $guarded = [];
    public $timestamps = false;

    public function propiedades()
    {
        return $this->belongsToMany(Propiedad::class, 'amenidad_propiedad');
    }
}
