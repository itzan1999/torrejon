<?php

namespace App\Enums;

enum EstadoPedido: string
{
    case CREADO = 'creado';
    case PROCESADO = 'procesado';
    case REPARTO = 'reparto';
    case ENTREGADO = 'entregado';
    case CANCELADO = 'cancelado';
    case EN_DEVOLUCION = 'en_devolucion';
    case DEVUELTO = 'devuelto';
    case PERDIDO = 'perdido';

    public static function values(): array
    {
        return array_map(fn($e) => $e->value, self::cases());
    }
}
