<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class apiController extends Controller
{
    public function checkDatabase()
    {
        try {
            $result = DB::select('SHOW TABLES');
            return response()->json([
                'status' => 'success',
                'message' => 'Conexión exitosa a la base de datos',
                'tables' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al conectarse a la base de datos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createOrder(Request $request)
    {
        $data = $request->json()->all();

        if (isset($data['orders']) && is_array($data['orders'])) {
            $conn = DB::connection()->getPdo();

            foreach ($data['orders'] as $order) {
                $id_usuario = $conn->quote($order['id_usuario']);
                $id_producto = $conn->quote($order['id_producto']);
                $cantidad = $conn->quote($order['cantidad']);
                $sql = "INSERT INTO productosxusuario (id_usuario, id_producto, cantidad) 
                        VALUES ($id_usuario, $id_producto, $cantidad)";

                try {
                    $conn->exec($sql);
                    return response()->json(["status" => "success", "message" => "Nueva orden registrada correctamente"]);
                } catch (\Exception $e) {
                    return response()->json(["status" => "error", "message" => "Error al registrar la orden: " . $e->getMessage()]);
                }
            }
        } else {
            return response()->json(["status" => "error", "message" => "Datos de orden no válidos"]);
        }
    }
}




