<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\Rule;

class LoginConductor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $email = $request->email;
        $contrasena = $request->contrasena;
        $validator = \Validator::make([
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

        return $next($request);
    }

    private function jsonResponse($data, $code = 200)
    {
        return response()->json($data, $code, [
            'Content-Type' => 'application/json;charset=UTF-8',
            'Charset' => 'utf-8',
        ], JSON_UNESCAPED_UNICODE);
    }
}
