<?php

return [

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

    /*
    |--------------------------------------------------------------------------
    | AI Provider
    |--------------------------------------------------------------------------
    |
    | Which AI Vision provider to use: 'openrouter' or 'gemini'.
    | OpenRouter is recommended (cheaper, more reliable).
    |
    */
    'ai_provider' => env('AI_PROVIDER', 'openrouter'),

    /*
    |--------------------------------------------------------------------------
    | AI Parser Configuration (Gemini — kept as fallback)
    |--------------------------------------------------------------------------
    */
    'ai_api_key'   => env('AI_API_KEY', ''),
    'ai_api_url'   => env('AI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent'),
    'ai_model'     => env('AI_MODEL', 'gemini-2.0-flash'),
    'ai_timeout'   => env('AI_TIMEOUT', 60),

    /*
    |--------------------------------------------------------------------------
    | OpenRouter Vision Configuration
    |--------------------------------------------------------------------------
    |
    | OpenAI-compatible endpoint. Supports vision models like
    | google/gemini-2.0-flash-001, anthropic/claude-3-haiku, etc.
    |
    */
    'openrouter_api_key' => env('OPENROUTER_API_KEY', ''),
    'openrouter_model'   => env('OPENROUTER_MODEL', 'google/gemini-2.5-flash'),
    'openrouter_base_url' => env('OPENROUTER_BASE_URL', 'https://openrouter.ai/api/v1/chat/completions'),

    /*
    |--------------------------------------------------------------------------
    | Local OCR (Tesseract + Python)
    |--------------------------------------------------------------------------
    |
    | Path to Python and Tesseract executables for local OCR fallback.
    | Used when AI Vision API is unavailable or quota is exceeded.
    |
    */
    'python_path'    => env('PYTHON_PATH', 'C:/Users/Lenovo/AppData/Local/Programs/Python/Python312/python.exe'),
    'tesseract_path' => env('TESSERACT_PATH', 'C:/Program Files/Tesseract-OCR/tesseract.exe'),
    'ocr_script'     => env('OCR_SCRIPT_PATH', 'scripts/ocr_attendance.py'),
];
