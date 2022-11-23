<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        '/validador/cfdi',
        '/producto/comprado/medioPago/referencia',
        'api/documento/emitir',
        'api/documento/buscar'
    ];
    
}
