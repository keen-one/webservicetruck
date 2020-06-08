<?php

namespace App\Http\Controllers;

use App\Conductor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ConductorController extends Controller
{
    public function __construct()
    {
    }

    public function login(Request $request)
    {

        $email = $request->email;
        $contrasena = $request->contrasena;
        $validator = Validator::make([
            "email" => $email,
            "contrasena" => $contrasena,
        ], [
            "email" => "required|min:5|max:255",
            Rule::exists("conductor", "correo")->where(function ($query) use ($contrasena) {
                $query->where("password", $contrasena);
            }),
        ]);
        if ($validator->fails()) {
            return $this->jsonResponse(["msg" => $validator->errors()->first(), "state" => false]);
        }
        $conductor = Conductor::where("correo", $email)->where("password", $contrasena)->first();
        if ($conductor) {
            return $this->jsonResponse(["data" => $conductor->toArray(), "state" => true]);
        } else {
            return $this->jsonResponse(["msg" => "Conductor no encontrado", "state" => false]);
        }
    }

    public function androidPasser(Request $request)
    {
        $validacion = Validator::make([
            "paquete" => $request->paquete,
            "huella" => $request->huella,
        ], [
            "huella" => "required|max:59|min:59",
            "paquete" => "required|max:255|min:5",

        ]);
        if ($validacion->fails()) {
            return $this->jsonResponse(["msg" => $validacion->errors()->first(), "state" => false]);
        } else {
            $paquete = $request->paquete;
            $huella = $request->huella;

            if ($paquete == env("PACKAGE_ANDROID") && $huella == env("HUELLA_ANDROID")) {

                return true;
            } else {
                return false;
            }
        }
    }

    public function existeUsername(Request $request)
    {

        $username = $request->username;
        $validator = Validator::make(["username" => $username], ["username" => "required|exists:conductor,nombreUsuario"]);
        if ($validator->fails()) {
            return $this->jsonResponse(["msg" => $validator->errors()->first(), "state" => false]);
        }

        return $this->jsonResponse(["msg" => "El nombre de usuario ya existe. Intenta de nuevo.", "state" => true]);
    }

    public function existeCorreo(Request $request)
    {

        $correo = $request->correo;
        $validator = Validator::make(["correo" => $correo], ["correo" => "required|exists:conductor,correo"]);
        if ($validator->fails()) {
            return $this->jsonResponse(["msg" => $validator->errors()->first(), "state" => false]);
        }

        return $this->jsonResponse(["msg" => "El correo electrónico ya existe", "state" => true]);
    }

    public function getApodoByUsername(Request $request)
    {
        $username = $request->username;
        $validator = Validator::make(["nombreUsuario" => $username], ["nombreUsuario" => "required|min:5|max:255|exists:conductor,nombreUsuario"]);
        if ($validator->fails()) {
            return $this->jsonResponse(["msg" => $validator->errors()->first(), "state" => false]);
        }
        $conductor = Conductor::where("nombreUsuario", $username)->first(["nombreUsuario", "apodo"]);

        return $this->jsonResponse(["data" => $conductor->toArray(), "state" => true]);
    }

    public function showByUsername(Request $request)
    {

        $username = $request->username;
        $validator = Validator::make(["nombreUsuario" => $username], ["nombreUsuario" => "required|min:5|max:255|exists:conductor,nombreUsuario"]);
        if ($validator->fails()) {
            return $this->jsonResponse(["msg" => $validator->errors()->first(), "state" => false]);
        }
        $obj = Conductor::where("nombreUsuario", $username)->first();
        if (! $obj) {
            return $this->jsonResponse(["msg" => "Conductor no encontrado", "state" => false]);
        }

        return $this->jsonResponse(["data" => $obj->toArray(), "state" => true]);
    }

    public function showByCorreo(Request $request)
    {

        $correo = $request->correo;
        $validator = Validator::make(["correo" => $correo], ["correo" => "required|min:5|max:255|exists:conductor,correo"]);
        if ($validator->fails()) {
            return $this->jsonResponse(["msg" => $validator->errors()->first(), "state" => false]);
        }
        $obj = Conductor::where("correo", $correo)->first();
        if (! $obj) {
            return $this->jsonResponse(["msg" => "Conductor no encontrado", "state" => false]);
        }

        return $this->jsonResponse(["data" => $obj->toArray(), "state" => true]);
    }

    public function update(Request $request)
    {

        $email1 = $request->email1;
        $password = $request->password;

        $latitud = $request->latitud;
        $longitud = $request->longitud;
        $actualizacion = [];
        $inputs = [];
        $reglas = [];
        $inputs["email1"] = $email1;
        $reglas["email1"] = "required|min:5|max:255|exists:conductor,correo";

        if (! empty($password)) {
            $inputs["password"] = $password;
            $reglas["password"] = "min:5|max:255";
        }

        if (! empty($latitud)) {
            $inputs["latitud"] = $latitud;
            $reglas["latitud"] = "min:2|max:255";
        }
        if (! empty($longitud)) {
            $inputs["longitud"] = $longitud;
            $reglas["longitud"] = "min:2|max:255";
        }

        $validator = Validator::make($inputs, $reglas);

        if ($validator->fails()) {
            return $this->jsonResponse(["msg" => $validator->errors()->first(), "state" => false]);
        }

        $obj = Conductor::where("correo", $email1)->first();
        if (! $obj) {
            return $this->jsonResponse(["msg" => "Conductor no encontrado", "state" => false]);
        }

        if (! empty($latitud)) {
            array_push($actualizacion, "latitud");
            $obj->latitud = $latitud;
        }
        if (! empty($longitud)) {
            array_push($actualizacion, "longitud");
            $obj->longitud = $longitud;
        }

        if (! empty($password)) {
            array_push($actualizacion, "password");
            $obj->password = $password;
        }
        $actualizacion = array_unique($actualizacion);

        if ($obj->update($actualizacion)) {
            return $this->jsonResponse(["data" => $obj->toArray(), "state" => true]);
        } else {
            return $this->jsonResponse(["msg" => "No pudo actualizar conductor", "state" => false]);
        }
    }

    public function destroy(Request $request)
    {

        $correo = $request->correo;
        $validator = Validator::make(["correo" => $correo], ["correo" => "required|max:255:min:5|exists:conductor,correo"]);
        if ($validator->fails()) {
            return $this->jsonResponse(["msg" => $validator->errors()->first(), "state" => false]);
        }
        $obj = Conductor::where("correo", $correo)->first();
        if (! $obj) {
            return $this->jsonResponse(["msg" => "Conductor no encontrado", "state" => false]);
        }
        try {
            if ($obj->delete()) {
                return $this->jsonResponse(["data" => $obj->toArray(), "state" => true]);
            } else {
                return $this->jsonResponse(["msg" => "no puede borrar conductor", "state" => false]);
            }
        } catch (\Exception $e) {

            return $this->jsonResponse(["msg" => "no puede borrar conductor", "state" => false]);
        }
    }

    public function guardar(Request $request)
    {
        $apodo = $request->apodo;
        $correo = $request->correo;
        $password = $request->password;
        $passwordChat = $password;
        $nombreUsuario = $request->nombreUsuario;
        $fechaNac = $request->fechaNac;
        $auth_token = $this->generarAuthToken($correo);
        $validator = Validator::make([
            "apodo" => $apodo,
            "correo" => $correo,
            "password" => $password,
            "nombreUsuario" => $nombreUsuario,
            "fechaNac" => $fechaNac,
            "auth_token" => $auth_token,
        ], [
            "apodo" => "required|min:5|max:255",
            "correo" => "required|min:5|max:255|unique:conductor,correo|unique:apoderado,correo",
            "password" => "required|min:5|max:255",
            "nombreUsuario" => "required|min:5|max:255|unique:conductor,nombreUsuario|unique:apoderado,nombreUsuario",
            "fechaNac" => "required|min:5|max:255",
            "auth_token" => "required|max:255|min:5",
        ]);
        if ($validator->fails()) {
            return $this->jsonResponse(["msg" => $validator->errors()->first(), "state" => false]);
        }

        $conductor = new Conductor();
        $conductor->passwordChat = $passwordChat;
        $conductor->apodo = $apodo;
        $conductor->correo = $correo;
        $conductor->password = $password;
        $conductor->nombreUsuario = $nombreUsuario;
        $conductor->fechaNac = $fechaNac;
        $conductor->auth_token = $auth_token;

        if ($conductor->save()) {
            return $this->jsonResponse(["data" => $conductor->toArray(), "state" => true]);
        } else {
            return $this->jsonResponse(["msg" => "No podria ingresar apoderado", "state" => false]);
        }
    }

    private function generarAuthToken($email)
    {
        if ($email == "") {
            return "";
        }
        $auth_token = md5(uniqid($email, true));

        return $auth_token;
    }

    public function getLocalizacion(Request $request)
    {
        $nombreUsuario = $request->username;
        $validator = Validator::make(["nombreUsuario" => $nombreUsuario], ["nombreUsuario" => "required|min:5|max:255|exists:conductor,nombreUsuario|unique:apoderado,nombreUsuario"]);
        if ($validator->fails()) {
            return $this->jsonResponse(["msg" => $validator->errors()->first(), "state" => false]);
        }
        $conductor = Conductor::where("nombreUsuario", $nombreUsuario)->first(["nombreUsuario", "latitud", "longitud"]);

        return $this->jsonResponse(["data" => $conductor->toArray(), "state" => true]);
    }
}
