<?php

namespace App\Http\Middleware;

use App\Conductor;
use Closure;
use Illuminate\Http\Request;

class AuthConductor
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
        $validator = \Validator::make($request->all(), [
            "id" => "required|numeric|exists:conductor,id",
            "auth_token" => "required|max:255|min:5",
        ]);
        if ($validator->fails()) {
            return response()->json([
                "state" => false,
                "msg" => $validator->errors()->first(),
            ], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
        }
        $conductor = Conductor::find($id);
        if ($conductor->auth_token == $authToken) {
            return $next($request);
        } else {
            return response()->json([
                "state" => false,
                "msg" => "Combinacion token/id falló validación",
            ], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
        }
    }
}
