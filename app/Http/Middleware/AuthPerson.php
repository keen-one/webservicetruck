<?php

namespace App\Http\Middleware;

use App\Apoderado;
use App\Conductor;
use Closure;
use Illuminate\Http\Request;

class AuthPerson
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $id = $request->id;
        $authToken = $request->auth_token;

        $validator = \Validator::make(["id" => $id, "auth_token" => $authToken], [
            "id" => "required|integer|exists:apoderado,id",
            "auth_token" => "required|max:255|min:5",
        ]);
        $validator2 = \Validator::make(["id" => $id, "auth_token" => $authToken], [
            "id" => "required|integer|exists:conductor,id",
            "auth_token" => "required|max:255|min:5",
        ]);
        if ($validator->fails() && $validator2->fails()) {
            return response()->json([
                "state" => false,
                "msg" => "Combinacion token/id falló validación",
            ], 200, [
                'Content-Type' => 'application/json;charset=UTF-8',
                'Charset' => 'utf-8',
            ], JSON_UNESCAPED_UNICODE);
        } else {
            $conductor = Conductor::find($id);
            $apoderado = Apoderado::find($id);
            if ($conductor) {
                if (strcmp($conductor->auth_token, $authToken) == 0) {
                    return $next($request);
                } else {
                    if ($apoderado) {
                        if (strcmp($apoderado->auth_token, $authToken) == 0) {
                            return $next($request);
                        } else {
                            return response()->json([
                                "state" => false,
                                "msg" => "Combinacion token/id falló validación",
                            ], 200, [
                                'Content-Type' => 'application/json;charset=UTF-8',
                                'Charset' => 'utf-8',
                            ], JSON_UNESCAPED_UNICODE);
                        }
                    }
                }
            } else {
                if ($apoderado) {
                    if (strcmp($apoderado->auth_token, $authToken) == 0) {
                        return $next($request);
                    } else {
                        return response()->json([
                            "state" => false,
                            "msg" => "Combinacion token/id falló validación",
                        ], 200, [
                            'Content-Type' => 'application/json;charset=UTF-8',
                            'Charset' => 'utf-8',
                        ], JSON_UNESCAPED_UNICODE);
                    }
                } else {
                    return response()->json([
                        "state" => false,
                        "msg" => "Combinacion token/id falló validación",
                    ], 200, [
                        'Content-Type' => 'application/json;charset=UTF-8',
                        'Charset' => 'utf-8',
                    ], JSON_UNESCAPED_UNICODE);
                }
            }
        }

        return response()->json([
            "state" => false,
            "msg" => "Combinacion token/id falló validación",
        ], 200, [
            'Content-Type' => 'application/json;charset=UTF-8',
            'Charset' => 'utf-8',
        ], JSON_UNESCAPED_UNICODE);
    }
}
