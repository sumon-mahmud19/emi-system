<?php

return [

    'font_dir' => storage_path('fonts/'),
    'font_cache' => storage_path('fonts/'),

    'font_data' => [
        'notosansbengali' => [
            'R' => 'NotoSansBengali-Regular.ttf',
            'useOTL' => 0xFF, // Required for complex scripts like Bangla
            'useKashida' => 75,
        ],
    ],

    'default_font' => 'notosansbengali',



];
