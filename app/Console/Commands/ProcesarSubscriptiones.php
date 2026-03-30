<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Suscripcion;
use App\Models\Pedido;
use App\Models\PedidoProducto;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcesarSubscriptiones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscripciones:procesar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Procesa las suscripciones activas y genera pedidos automáticos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

        // Obtener subscripciones que deben procesarse hoy
        $suscripciones = Suscripcion::with('productos')
          ->where(function ($query) use ($today) {
            // Suscripciones semanales: cada 7 dias desde la fecha de registro
            $query->where('tipo', 'semanal')
                  ->whereRaw('DATEDIFF(?, fecha_inicio) % 7 = 0', [$today]);
          })
          ->orWhere(function ($query) use ($today) {
            // Suscripciones mensuales: mismo dia del mes que la fecha de registro
            $query->where('tipo', 'mensual')
              ->whereDay('fecha_inicio', $today->day);
          })
          ->get();

        if ($suscripciones->isEmpty()) {
            $this->info('No hay suscripciones para procesar hoy.');
            return Command::SUCCESS;
        }

        $procesadas = 0;
        $errores = 0;

        foreach ($suscripciones as $suscripcion) {
          try {
          DB::transaction(function () use ($suscripcion) {
            // Generar un código de pedido único
            $timestamp = (int)now()->diffInSeconds(now()->startOfDay(), true);
            $codigoPedido = $timestamp . random_int(1000, 9999);

            // Calcular precio total del pedido
            $precioTotal = $suscripcion->productos->sum(function ($producto) {
              $precioConDescuento = $producto->precio * (1 - (($producto->pivot->descuento ?? 0) / 100));
              return $precioConDescuento * $producto->pivot->cantidad;
            });

            // Crear el pedido
            $pedido = Pedido::create([
              'code' => $codigoPedido,
              'estado' => 'pendiente',
              'suscripcion' => true,
              'id_usuario' => $suscripcion->id_usuario,
              'precio_total' => $precioTotal,
            ]);

            // Agregar productos al pedido
            $pedidoProductos = $suscripcion->productos->map(function ($producto) use ($pedido) {
              return [
                'id_pedido' => $pedido->id,
                'id_producto' => $producto->id,
                'cantidad' => $producto->pivot->cantidad,
                'created_at' => now(),
                'updated_at' => now()
              ];
          })->toArray();

            PedidoProducto::insert($pedidoProductos);
          });

          $procesadas++;
          } catch (\Exception $e) {
            $errores++;
            Log::error("Error procesando suscripcion ID {$suscripcion->id}: " . $e->getMessage());
            $this->error("Error en suscripcion ID {$suscripcion->id}: " . $e->getMessage());
          }
        }

        $this->info("Proceso completado: {$procesadas} pedidos creados, {$errores} errores.");
        return Command::SUCCESS;
    }
}