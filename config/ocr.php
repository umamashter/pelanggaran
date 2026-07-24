<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Tesseract OCR Path
    |--------------------------------------------------------------------------
    |
    | Full path to tesseract executable. Leave empty to use system PATH.
    | Example (Windows): 'C:\Program Files\Tesseract-OCR\tesseract.exe'
    |
    */
    'tesseract_path' => env('TESSERACT_PATH', ''),

    /*
    |--------------------------------------------------------------------------
    | Python Path
    |--------------------------------------------------------------------------
    |
    | Path to python executable. Leave empty to use system PATH.
    | Example: 'python3' or 'C:\Python312\python.exe'
    |
    */
    'python_path' => env('PYTHON_PATH', 'python'),

    /*
    |--------------------------------------------------------------------------
    | OCR Script Path
    |--------------------------------------------------------------------------
    |
    | Path to the OCR Python script, relative to project root.
    |
    */
    'script_path' => env('OCR_SCRIPT_PATH', 'scripts/ocr_attendance.py'),

    /*
    |--------------------------------------------------------------------------
    | Upload Disk
    |--------------------------------------------------------------------------
    |
    | Filesystem disk for temporary attendance photo uploads.
    |
    */
    'upload_disk' => 'local',

    /*
    |--------------------------------------------------------------------------
    | Upload Directory
    |--------------------------------------------------------------------------
    |
    | Directory within the upload disk for attendance photos.
    |
    */
    'upload_dir' => 'absensi-import',

    /*
    |--------------------------------------------------------------------------
    | Max File Size (MB)
    |--------------------------------------------------------------------------
    |
    | Maximum upload file size in megabytes.
    |
    */
    'max_file_size' => 10,

    /*
    |--------------------------------------------------------------------------
    | Allowed MIME Types
    |--------------------------------------------------------------------------
    |
    | Allowed image MIME types for upload.
    |
    */
    'allowed_mime_types' => ['image/jpeg', 'image/png', 'image/webp'],
];
