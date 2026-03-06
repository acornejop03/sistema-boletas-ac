<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Modo de conexión: 'directo' (greenter) o 'nubefact' (API REST)
    |--------------------------------------------------------------------------
    */
    'driver' => env('SUNAT_DRIVER', 'directo'),  // 'directo' | 'nubefact'

    /*
    | Configuración conexión directa SUNAT (driver=directo)
    */
    'ambiente'     => env('SUNAT_AMBIENTE', 'beta'),
    'sol_ruc'      => env('SUNAT_SOL_RUC', '20000000001'),
    'sol_usuario'  => env('SUNAT_SOL_USUARIO', 'MODDATOS'),
    'sol_password' => env('SUNAT_SOL_PASSWORD', 'moddatos'),
    'cert_path'    => env('SUNAT_CERT_PATH', 'storage/app/sunat/certificate.pem'),

    /*
    | Configuración NubeFact (driver=nubefact)
    | Token se obtiene en: https://app.nubefact.com → Configuración → Token API
    */
    'nubefact_token'   => env('NUBEFACT_TOKEN', ''),
    'nubefact_ruc'     => env('NUBEFACT_RUC', ''),
    'nubefact_api_url' => env('NUBEFACT_API_URL', 'https://api.nubefact.com/api/v1'),

    /*
    | IGV
    */
    'igv' => env('SUNAT_IGV', 18),
];
