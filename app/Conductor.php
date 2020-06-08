<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conductor extends Model
{
    public $timestamps = false;

    protected $table = "conductor";

    protected $primaryKey = "id";

    protected $fillable = [
        "apodo",
        "correo",
        "fechaNac",
        "password",
        "passwordChat",
        "auth_token",
        "latitud",
        "longitud",
        "nombreUsuario",
    ];

    protected $attributes = [
        "latitud" => "",
        "longitud" => "",
    ];
}
