<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = "configuracion";

    protected $guarded = [];

    public static function actual(): self
    {
        return static::firstOrCreate(["id" => 1]);
    }
}
