<?php

namespace App\Http\Controllers;

use App\Apoderado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ApoderadoController extends Controller
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
            Rule::exists("apoderado", "correo")->where(function ($query) use ($contrasena) {
                $query->where("password", $contrasena);
            }),
        ]);
        if ($validator->fails()) {
            return $this->jsonResponse(["msg" => $validator->errors()->first(), "state" => false]);
        }
        $apoderado = Apoderado::where("correo", $email)->where("password", $contrasena)->first();
        if ($apoderado) {
            return $this->jsonResponse(["data" => $apoderado->toArray(), "state" => true]);
        } else {
            return $this->jsonResponse(["msg" => "Apoderado no encontrado", "state" => false]);
        }
    }

    public function existeUsername(Request $request)
    {
        $username = $request->username;
        $validator = Validator::make(["username" => $username], ["username" => "required|exists:apoderado,nombreUsuario"]);
        if ($validator->fails()) {
            return $this->jsonResponse(["msg" => $validator->errors()->first(), "state" => false]);
        }

        return $this->jsonResponse(["msg" => "El nombre de usuario ya existe. Intenta de nuevo", "state" => true]);
    }

    public function existeCorreo(Request $request)
    {

        $correo = $request->correo;
        $validator = Validator::make(["correo" => $correo], ["correo" => "required|exists:apoderado,correo"]);
        if ($validator->fails()) {
            return $this->jsonResponse(["msg" => $validator->errors()->first(), "state" => false]);
        }

        return $this->jsonResponse(["msg" => "El correo electrónico ya existe", "state" => true]);
    }

    public function showByUsername(Request $request)
    {

        $username = $request->username;
        $validator = Validator::make(["nombreUsuario" => $username], ["nombreUsuario" => "required|min:5|max:255|exists:apoderado,nombreUsuario"]);
        if ($validator->fails()) {
            return $this->jsonResponse(["msg" => $validator->errors()->first(), "state" => false]);
        }
        $obj = Apoderado::where("nombreUsuario", $username)->first();
        if (! $obj) {
            return $this->jsonResponse(["msg" => "Apoderado no encontrado", "state" => false]);
        }

        return $this->jsonResponse(["data" => $obj->toArray(), "state" => true]);
    }

    public function getPuntoPartidaFinalByUsername(Request $request)
    {
        $username = $request->username;
        $validator = Validator::make(["nombreUsuario" => $username], ["nombreUsuario" => "required|min:5|max:255|exists:apoderado,nombreUsuario"]);
        if ($validator->fails()) {
            return $this->jsonResponse(["msg" => $validator->errors()->first(), "state" => false]);
        }
        $apoderado = Apoderado::where("nombreUsuario", $username)->first([
            "nombreUsuario",
            "apodo",
            "latitudInicial",
            "longitudInicial",
            "latitudFinal",
            "longitudFinal",
			"lugar"
        ]);

        return $this->jsonResponse(["data" => $apoderado->toArray(), "state" => true]);
    }

    public function getUsuarioByUsername(Request $request)
    {
        $username = $request->username;
        $validator = Validator::make(["nombreUsuario" => $username], ["nombreUsuario" => "required|min:5|max:255|exists:apoderado,nombreUsuario"]);
        if ($validator->fails()) {
            return $this->jsonResponse(["msg" => $validator->errors()->first(), "state" => false]);
        }
        $apoderado = Apoderado::where("nombreUsuario", $username)->first([
            "nombreUsuario",
            "apodo",
            "latitudInicial",
            "longitudInicial",
        ]);

        return $this->jsonResponse(["data" => $apoderado->toArray(), "state" => true]);
    }

    public function getApodoByUsername(Request $request)
    {
        $username = $request->username;
        $validator = Validator::make(["nombreUsuario" => $username], ["nombreUsuario" => "required|min:5|max:255|exists:apoderado,nombreUsuario"]);
        if ($validator->fails()) {
            return $this->jsonResponse(["msg" => $validator->errors()->first(), "state" => false]);
        }
        $apoderado = Apoderado::where("nombreUsuario", $username)->first(["nombreUsuario", "apodo"]);

        return $this->jsonResponse(["data" => $apoderado->toArray(), "state" => true]);
    }

    public function showByCorreo(Request $request)
    {

        $correo = $request->correo;
        $validator = Validator::make(["correo" => $correo], ["correo" => "required|min:5|max:255|exists:apoderado,correo"]);
        if ($validator->fails()) {
            return $this->jsonResponse(["msg" => $validator->errors()->first(), "state" => false]);
        }
        $obj = Apoderado::where("correo", $correo)->first();
        if (! $obj) {
            return $this->jsonResponse(["msg" => "Apoderado no encontrado", "state" => false]);
        }

        return $this->jsonResponse(["data" => $obj->toArray(), "state" => true]);
    }

    public function update(Request $request)
    {

        $email1 = $request->email1;
        $password = $request->password;
        $lugar = $request->lugar;
        $latitudInicial = $request->latitudInicial;
        $longitudInicial = $request->longitudInicial;
        $latitudFinal = $request->latitudFinal;
        $longitudFinal = $request->longitudFinal;

        $inputs = [];
        $reglas = [];
        $inputs["email1"] = $email1;
        $reglas["email1"] = "required|min:5|max:255|exists:apoderado,correo";

        if (! empty($password)) {
            $inputs["password"] = $password;
            $reglas["password"] = "min:5|max:255";
        }
        if (! empty($lugar)) {
            $inputs["lugar"] = $lugar;
            $reglas["lugar"] = "min:5|max:255";
        }

        if (! empty($latitudInicial)) {
            $inputs["latitudInicial"] = $latitudInicial;
            $reglas["latitudInicial"] = "min:2|max:255";
        }
        if (! empty($longitudInicial)) {
            $inputs["longitudInicial"] = $longitudInicial;
            $reglas["longitudInicial"] = "min:2|max:255";
        }
        if (! empty($latitudFinal)) {
            $inputs["latitudFinal"] = $latitudFinal;
            $reglas["latitudFinal"] = "min:2|max:255";
        }
        if (! empty($longitudFinal)) {
            $inputs["longitudFinal"] = $longitudFinal;
            $reglas["longitudFinal"] = "min:2|max:255";
        }
        $validator = Validator::make($inputs, $reglas);

        if ($validator->fails()) {
            return $this->jsonResponse(["msg" => $validator->errors()->first(), "state" => false]);
        }
        $actualizacion = [];
        $obj = Apoderado::where("correo", $email1)->first();
        if (! $obj) {
            return $this->jsonResponse(["msg" => "Apoderado no encontrado", "state" => false]);
        }

        if (! empty($password)) {
            array_push($actualizacion, "password");
            $obj->password = $password;
        }
        if (! empty($lugar)) {
            array_push($actualizacion, "lugar");
            $obj->lugar = $lugar;
        }

        if (! empty($latitudInicial)) {
            array_push($actualizacion, "latitudInicial");
            $obj->latitudInicial = $latitudInicial;
        }
        if (! empty($longitudInicial)) {
            array_push($actualizacion, "longitudInicial");
            $obj->longitudInicial = $longitudInicial;
        }
        if (! empty($latitudFinal)) {
            array_push($actualizacion, "latitudFinal");
            $obj->latitudFinal = $latitudFinal;
        }
        if (! empty($longitudFinal)) {
            array_push($actualizacion, "longitudFinal");
            $obj->longitudFinal = $longitudFinal;
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
        $validator = Validator::make(["correo" => $correo], ["correo" => "required|max:255:min:5|exists:apoderado,correo"]);
        if ($validator->fails()) {
            return $this->jsonResponse(["msg" => $validator->errors()->first(), "state" => false]);
        }
        $obj = Apoderado::where("correo", $correo)->first();
        if (! $obj) {
            return $this->jsonResponse(["msg" => "Apoderado no encontrado", "state" => false]);
        }
        try {
            if ($obj->delete()) {
                return $this->jsonResponse(["data" => $obj->toArray(), "state" => true]);
            } else {
                return $this->jsonResponse(["msg" => "no puede borrar apoderado", "state" => false]);
            }
        } catch (\Exception $e) {
            return $this->jsonResponse(["msg" => "no puede borrar apoderado", "state" => false]);
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
            "correo" => "required|min:5|max:255|unique:apoderado,correo|unique:conductor,correo",
            "password" => "required|min:5|max:255",
            "nombreUsuario" => "required|min:5|max:255|unique:apoderado,nombreUsuario|unique:conductor,nombreUsuario",
            "fechaNac" => "required|min:5|max:255",
            "auth_token" => "required|max:255|min:5",
        ]);
        if ($validator->fails()) {
            return $this->jsonResponse(["msg" => $validator->errors()->first(), "state" => false]);
        }

        $apoderado = new Apoderado();
        $apoderado->passwordChat = $passwordChat;
        $apoderado->apodo = $apodo;
        $apoderado->correo = $correo;
        $apoderado->password = $password;
        $apoderado->nombreUsuario = $nombreUsuario;
        $apoderado->fechaNac = $fechaNac;
        $apoderado->auth_token = $auth_token;
        if ($apoderado->save()) {
            return $this->jsonResponse(["data" => $apoderado->toArray(), "state" => true]);
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
}
