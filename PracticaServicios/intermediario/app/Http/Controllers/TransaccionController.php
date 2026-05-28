<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TransaccionController extends Controller
{
    public function store(Request $request)
    {
        // Controlador de transacciones: valida saldo antes de registrar
        // Comentarios breves en español solicitados por el usuario.
        $data = $request->all();

        // monto y origen
        // extraer monto y cuenta origen del cuerpo
        $monto = isset($data['monto']) ? floatval($data['monto']) : 0.0;
        $cuentaOrigen = $data['cuentaOrigen'] ?? $data['cuenta'] ?? $data['origen'] ?? null;

        if (!$cuentaOrigen) {
            // falta cuenta origen
            return response()->json(['mensaje' => 'cuenta origen requerida'], 400);
        }

        // SOAP 
        $wsdl = env('BANCO_SOAP_WSDL', 'https://localhost:44377/WebService1.asmx?WSDL');

        try {
            // intentar consultar saldo vía SOAP
            $client = new \SoapClient($wsdl, ['exceptions' => true]);
            $resp = $client->consultarSaldo($cuentaOrigen);

            // extraer
            if (is_object($resp)) {
                // respuesta como objeto
                $saldo = $resp->consultarSaldoResult ?? ($resp->return ?? (float) $resp);
            } elseif (is_array($resp)) {
                // respuesta como array
                $saldo = $resp['consultarSaldoResult'] ?? ($resp['return'] ?? (float) reset($resp));
            } else {
                // respuesta simple (float)
                $saldo = (float) $resp;
            }
        } catch (\Exception $e) {
            // SOAP failed — try BNB REST as fallback
            $bnUrlTemplate = env('BNB_REST_URL', 'http://127.0.0.1:8001/api/cuenta/{cuenta}');
            $bnUrl = str_replace('{cuenta}', urlencode($cuentaOrigen), $bnUrlTemplate);
            try {
                $opts = ['http' => ['method' => 'GET', 'timeout' => 5]];
                $context = stream_context_create($opts);
                $respJson = @file_get_contents($bnUrl, false, $context);
                if ($respJson === false) {
                    throw new \Exception('BNB REST no disponible');
                }
                $respData = json_decode($respJson, true);
                if (isset($respData['saldo'])) {
                    // extraer saldo desde BNB REST
                    $saldo = (float) $respData['saldo'];
                } elseif (isset($respData['data']['saldo'])) {
                    $saldo = (float) $respData['data']['saldo'];
                } else {
                    // assume whole body is the account object
                    $saldo = isset($respData['saldo']) ? (float) $respData['saldo'] : null;
                }
                if ($saldo === null) throw new \Exception('Saldo no encontrado en respuesta BNB');
            } catch (\Exception $e2) {
                // ambos intentos fallaron
                return response()->json(['mensaje' => 'Error contactando banco (SOAP y BNB REST fallaron): ' . $e->getMessage() . ' / ' . $e2->getMessage()], 502);
            }
        }

        // validar saldo
        if ($saldo < $monto) {
            return response()->json(['mensaje' => 'Saldo insuficiente', 'saldo' => $saldo], 400);
        }

        // persistencia
        $file = storage_path('app/transacciones.json');
        $list = [];
        if (file_exists($file)) {
            $list = json_decode(file_get_contents($file), true) ?: [];
        }

        $list[] = $data;
        file_put_contents($file, json_encode($list, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return response()->json($data, 201);
    }

    // Ruta de prueba: intenta consultar saldo vía SOAP y luego BNB REST
    public function testSaldo($cuenta)
    {
        $cuentaOrigen = $cuenta;
        $wsdl = env('BANCO_SOAP_WSDL', 'https://localhost:44377/WebService1.asmx?WSDL');
        try {
            $client = new \SoapClient($wsdl, ['exceptions' => true]);
            $resp = $client->consultarSaldo($cuentaOrigen);
            if (is_object($resp)) {
                $saldo = $resp->consultarSaldoResult ?? ($resp->return ?? (float) $resp);
            } elseif (is_array($resp)) {
                $saldo = $resp['consultarSaldoResult'] ?? ($resp['return'] ?? (float) reset($resp));
            } else {
                $saldo = (float) $resp;
            }
            return response()->json(['origen' => 'soap', 'saldo' => (float) $saldo], 200);
        } catch (\Exception $e) {
            // intentar BNB REST
            $bnUrlTemplate = env('BNB_REST_URL', 'http://127.0.0.1:8001/api/cuenta/{cuenta}');
            $bnUrl = str_replace('{cuenta}', urlencode($cuentaOrigen), $bnUrlTemplate);
            try {
                $respJson = @file_get_contents($bnUrl);
                if ($respJson === false) throw new \Exception('BNB REST no disponible');
                $respData = json_decode($respJson, true);
                if (isset($respData['saldo'])) $saldo = (float) $respData['saldo'];
                elseif (isset($respData['data']['saldo'])) $saldo = (float) $respData['data']['saldo'];
                else $saldo = isset($respData['saldo']) ? (float) $respData['saldo'] : null;
                if ($saldo === null) throw new \Exception('Saldo no encontrado en respuesta BNB');
                return response()->json(['origen' => 'bnb-rest', 'saldo' => (float) $saldo], 200);
            } catch (\Exception $e2) {
                return response()->json(['mensaje' => 'Error contactando banco (SOAP y BNB REST): ' . $e->getMessage() . ' / ' . $e2->getMessage()], 502);
            }
        }
    }
}
