<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AndroidPasser
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
        $headerAppId = $request->header("appid", "hola");
        $androidKey = $this->decrypt(env("KEY_PASSWORD"), $headerAppId);
        if ($androidKey == env("HUELLA_ANDROID")) {
            return $next($request);
        } else {
            $data = ["msg" => "Falló autenticación con android", "state" => false];

            return response()->json($data, 200, [
                'Content-Type' => 'application/json;charset=UTF-8',
                'Charset' => 'utf-8',
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    private function decrypt($key, $data)
    {
        try {
            $OPENSSL_CIPHER_NAME = "aes-128-cbc"; //Name of OpenSSL Cipher
            $CIPHER_KEY_LEN = 16; //128 bits
            //$data = str_replace("-", "+", $data);
            //$data = str_replace("#", "=", $data);
            if (strlen($key) < $CIPHER_KEY_LEN) {
                $key = str_pad("$key", $CIPHER_KEY_LEN, "0"); //0 pad to len 16
            } else {
                if (strlen($key) > $CIPHER_KEY_LEN) {
                    $key = substr($key, 0, $CIPHER_KEY_LEN); //truncate to 16 bytes
                }
            }

            $parts = explode(':', $data); //Separate Encrypted data from iv.
            $parte1 = "1234567890123456789012";
            $parte2 = "1234567890123456789012";
            try {
                $parte1 = $parts[0];
                $parte2 = $parts[1];
            } catch (\Exception $e) {
                $parte1 = "1234567890123456789012";
                $parte2 = "1234567890123456789012";
            }
            $decryptedData = openssl_decrypt(base64_decode($parte1), $OPENSSL_CIPHER_NAME, $key, OPENSSL_RAW_DATA, base64_decode($parte2));

            return $decryptedData;
        } catch (\Exception $a) {
        }

        return "hola";
    }
}
