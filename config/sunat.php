<?php

return [
    'ambiente'     => env('SUNAT_AMBIENTE', 'beta'),
    'sol_ruc'      => env('SUNAT_SOL_RUC', '20000000001'),
    'sol_usuario'  => env('SUNAT_SOL_USUARIO', 'MODDATOS'),
    'sol_password' => env('SUNAT_SOL_PASSWORD', 'moddatos'),
    'cert_path'    => env('SUNAT_CERT_PATH', 'storage/app/sunat/certificate.pem'),
    'igv'          => env('SUNAT_IGV', 18),
];
