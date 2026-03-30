<?php

namespace App\Enums;

enum TipoMovimientoSaldo: string
{
    case RECARGA = 'recarga';
    case PAGO = 'pago';
    case DEVOLUCION = 'devolucion';
}