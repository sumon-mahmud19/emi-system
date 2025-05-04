<?php

return [

    'font_dir' => public_path('fonts'),
    'font_cache' => storage_path('fonts'),
    'default_font' => 'NotoSansBengali',


    'font_data' => [
        'notosansbengali' => [
            'R' => 'NotoSansBengali-Regular.ttf',
            'useOTL' => 0xFF, // Required for complex scripts like Bangla
            'useKashida' => 75,
        ],
    ],





];
