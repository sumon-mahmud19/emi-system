<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PDF Driver
    |--------------------------------------------------------------------------
    |
    | Supported: "dompdf"
    |
    */

    'driver' => 'dompdf',

    /*
    |--------------------------------------------------------------------------
    | DomPDF Settings
    |--------------------------------------------------------------------------
    |
    | These settings are passed directly to DomPDF.
    |
    */

    'dompdf' => [
        'show_warnings' => false,
        'orientation' => 'portrait',
        'defines' => [
            // Load Bangla font support
            'DOMPDF_ENABLE_REMOTE' => true,
            'DOMPDF_UNICODE_ENABLED' => true,
            'DOMPDF_FONT_DIR' => public_path('fonts/'),
            'DOMPDF_FONT_CACHE' => storage_path('fonts/'),
            'DOMPDF_DEFAULT_MEDIA_TYPE' => 'screen',
            'DOMPDF_DEFAULT_PAPER_SIZE' => 'a4',
            'DOMPDF_DEFAULT_FONT' => 'notosansbengali',
            'DOMPDF_DPI' => 96,
            'DOMPDF_ENABLE_HTML5PARSER' => true,
            'DOMPDF_ENABLE_FONTSUBSETTING' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | PDF Generation Options
    |--------------------------------------------------------------------------
    |
    | These options are used when generating the PDF.
    |
    */

    'default_font' => 'notosansbengali',

    'paper' => 'a4',
];
