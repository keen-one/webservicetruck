<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apoderado extends Model
{
    public $timestamps = false;

    protected $table = "apoderado";

    protected $primaryKey = "id";

    protected $fillable = [
        "apodo",
        "correo",
        "password",
        "passwordChat",
        "fechaNac",
        "auth_token",
        "nombreUsuario",
        "latitudInicial",
        "longitudInicial",
        "latitudFinal",
        "longitudFinal",
        "lugar",
    ];

    protected $attributes = [

        "latitudInicial" => "",
        "longitudInicial" => "",
        "latitudFinal"=>"",
        "longitudFinal"=>"",
        "lugar"=>"",
    ];
}
