<?php

return [
    'font_dir' => storage_path('fonts/'),
    'font_cache' => storage_path('fonts/'),
    'fonts' => [
        'solaiman_lipi' => [
            'R'  => public_path('fonts/SolaimanLipi.ttf'),  // Path to the SolaimanLipi font
            'B'  => public_path('fonts/SolaimanLipi.ttf'),  // Use the same file for bold if not available
            'I'  => public_path('fonts/SolaimanLipi.ttf'),  // Italic font (if available)
            'BI' => public_path('fonts/SolaimanLipi.ttf'),  // Bold Italic font (if available)
        ],
    ],

];
