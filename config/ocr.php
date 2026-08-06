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
    | Pipeline V2 Feature Flags
    |--------------------------------------------------------------------------
    |
    | Tahap 1: flag untuk mengaktifkan pipeline baru secara aman tanpa
    | mengubah route/controller entrypoint lama.
    |
    */
    'enable_pipeline_v2'            => env('OCR_ENABLE_PIPELINE_V2', true),
    'enable_openrouter_primary'     => env('OCR_ENABLE_OPENROUTER_PRIMARY', true),
    'enable_document_classification'=> env('OCR_ENABLE_DOCUMENT_CLASSIFICATION', true),
    'enable_image_quality'          => env('OCR_ENABLE_IMAGE_QUALITY', true),
    'enable_decision_engine'        => env('OCR_ENABLE_DECISION_ENGINE', true),
    'enable_mock_vision_provider'   => env('OCR_ENABLE_MOCK_VISION_PROVIDER', false),
    'enable_template_matcher'       => env('OCR_ENABLE_TEMPLATE_MATCHER', true),
    'enable_alignment_analysis'     => env('OCR_ENABLE_ALIGNMENT_ANALYSIS', true),
    'enable_anchor_detection'       => env('OCR_ENABLE_ANCHOR_DETECTION', true),
    'enable_table_detector'         => env('OCR_ENABLE_TABLE_DETECTOR', true),
    'enable_grid_detector'          => env('OCR_ENABLE_GRID_DETECTOR', true),
    'enable_cell_mapper'            => env('OCR_ENABLE_CELL_MAPPER', true),
    'enable_coordinate_mapper'      => env('OCR_ENABLE_COORDINATE_MAPPER', true),
    'enable_cell_ocr'               => env('OCR_ENABLE_CELL_OCR', true),
    'enable_student_row_matcher'    => env('OCR_ENABLE_STUDENT_ROW_MATCHER', true),
    'enable_roi_detection'          => env('OCR_ENABLE_ROI_DETECTION', false),

    /*
    |--------------------------------------------------------------------------
    | Provider Retry / Timeout Configuration
    |--------------------------------------------------------------------------
    |
    | Seluruh timeout dan retry dibuat configurable agar tidak hardcode.
    |
    */
    'pipeline_timeout'        => env('OCR_PIPELINE_TIMEOUT', 120),
    'provider_priority'       => array_values(array_filter(array_map('trim', explode(',', env('OCR_PROVIDER_PRIORITY', 'openrouter,gemini,local_ocr'))))),
    'openrouter_timeout'      => env('OCR_OPENROUTER_TIMEOUT', 60),
    'gemini_timeout'          => env('OCR_GEMINI_TIMEOUT', 60),
    'local_ocr_timeout'       => env('OCR_LOCAL_TIMEOUT', 120),
    'openrouter_max_retries'  => env('OCR_OPENROUTER_MAX_RETRIES', 2),
    'gemini_max_retries'      => env('OCR_GEMINI_MAX_RETRIES', 1),
    'local_ocr_max_retries'   => env('OCR_LOCAL_MAX_RETRIES', 1),

    /*
    |--------------------------------------------------------------------------
    | Stage 2 Gatekeeper Rules
    |--------------------------------------------------------------------------
    */
    'document_rules' => [
        'attendance_keywords' => ['ABSENSI', 'SISWA', 'KELAS', 'BULAN', 'TAHUN', 'MI', 'NURUL', 'ULUM'],
        'reject_keywords'     => ['RAPORT', 'RAPOR', 'KARTU TANDA PENDUDUK', 'KTP', 'IJAZAH', 'SURAT', 'NILAI'],
        'min_attendance_hits' => env('OCR_CLASSIFICATION_MIN_ATTENDANCE_HITS', 2),
        'reject_hits'         => env('OCR_CLASSIFICATION_REJECT_HITS', 2),
    ],

    'template_matching' => [
        'enabled' => env('OCR_TEMPLATE_MATCHING_ENABLED', true),
        'minimum_confidence' => env('OCR_TEMPLATE_MATCHING_MINIMUM_CONFIDENCE', 0.70),
        'minimum_anchor_score' => env('OCR_TEMPLATE_MATCHING_MINIMUM_ANCHOR_SCORE', 0.65),
        'minimum_alignment_score' => env('OCR_TEMPLATE_MATCHING_MINIMUM_ALIGNMENT_SCORE', 0.65),
        'minimum_layout_confidence' => env('OCR_TEMPLATE_MATCHING_MINIMUM_LAYOUT_CONFIDENCE', 0.60),
        'template_tolerance' => env('OCR_TEMPLATE_MATCHING_TOLERANCE', 0.35),
        'matching_mode' => env('OCR_TEMPLATE_MATCHING_MODE', 'heuristic'),
        'allow_fallback' => env('OCR_TEMPLATE_MATCHING_ALLOW_FALLBACK', true),
        'expected_orientation' => env('OCR_TEMPLATE_MATCHING_EXPECTED_ORIENTATION', 'landscape'),
        'target_aspect_ratio' => env('OCR_TEMPLATE_MATCHING_TARGET_ASPECT_RATIO', 1.414),
        'expected_table_top_ratio' => env('OCR_TEMPLATE_MATCHING_TABLE_TOP_RATIO', 0.22),
        'expected_table_left_ratio' => env('OCR_TEMPLATE_MATCHING_TABLE_LEFT_RATIO', 0.08),
        'expected_table_width_ratio' => env('OCR_TEMPLATE_MATCHING_TABLE_WIDTH_RATIO', 0.84),
        'expected_table_height_ratio' => env('OCR_TEMPLATE_MATCHING_TABLE_HEIGHT_RATIO', 0.60),
        'border_density_target' => env('OCR_TEMPLATE_MATCHING_BORDER_DENSITY_TARGET', 0.12),
        'line_density_target' => env('OCR_TEMPLATE_MATCHING_LINE_DENSITY_TARGET', 0.10),
        'whitespace_profile_target' => env('OCR_TEMPLATE_MATCHING_WHITESPACE_PROFILE_TARGET', 0.32),
        'grid_likelihood_target' => env('OCR_TEMPLATE_MATCHING_GRID_LIKELIHOOD_TARGET', 0.75),
    ],

    'table_detection' => [
        'enabled' => env('OCR_TABLE_DETECTION_ENABLED', true),
        'minimum_confidence' => env('OCR_TABLE_DETECTION_MINIMUM_CONFIDENCE', 0.70),
        'minimum_table_area' => env('OCR_TABLE_DETECTION_MINIMUM_TABLE_AREA', 0.30),
        'minimum_border_density' => env('OCR_TABLE_DETECTION_MINIMUM_BORDER_DENSITY', 0.05),
        'minimum_line_density' => env('OCR_TABLE_DETECTION_MINIMUM_LINE_DENSITY', 0.03),
        'table_detection_mode' => env('OCR_TABLE_DETECTION_MODE', 'heuristic'),
        'allow_fallback' => env('OCR_TABLE_DETECTION_ALLOW_FALLBACK', true),
        'save_debug_overlay' => env('OCR_TABLE_DETECTION_SAVE_DEBUG_OVERLAY', false),
        'debug_root' => env('OCR_TABLE_DETECTION_DEBUG_ROOT', 'storage/app/ocr/debug'),
        'expected_table_top_ratio' => env('OCR_TABLE_DETECTION_EXPECTED_TOP_RATIO', 0.22),
        'expected_table_left_ratio' => env('OCR_TABLE_DETECTION_EXPECTED_LEFT_RATIO', 0.08),
        'expected_table_width_ratio' => env('OCR_TABLE_DETECTION_EXPECTED_WIDTH_RATIO', 0.84),
        'expected_table_height_ratio' => env('OCR_TABLE_DETECTION_EXPECTED_HEIGHT_RATIO', 0.60),
    ],

    'grid_detection' => [
        'enabled' => env('OCR_GRID_DETECTION_ENABLED', true),
        'save_debug_overlay' => env('OCR_GRID_DETECTION_SAVE_DEBUG_OVERLAY', false),
        'minimum_confidence' => env('OCR_GRID_DETECTION_MINIMUM_CONFIDENCE', 0.45),
        'expected_rows' => env('OCR_GRID_DETECTION_EXPECTED_ROWS', 35),
        'expected_columns' => env('OCR_GRID_DETECTION_EXPECTED_COLUMNS', 36),
    ],

    'cell_mapping' => [
        'enabled' => env('OCR_CELL_MAPPING_ENABLED', true),
        'minimum_confidence' => env('OCR_CELL_MAPPING_MINIMUM_CONFIDENCE', 0.50),
        'expected_student_rows' => env('OCR_CELL_MAPPING_EXPECTED_STUDENT_ROWS', 40),
        'expected_date_columns' => env('OCR_CELL_MAPPING_EXPECTED_DATE_COLUMNS', 31),
        'expected_recap_columns' => env('OCR_CELL_MAPPING_EXPECTED_RECAP_COLUMNS', 3),
        'allow_partial_grid' => env('OCR_CELL_MAPPING_ALLOW_PARTIAL_GRID', true),
    ],

    'coordinate_mapping' => [
        'enabled' => env('OCR_COORDINATE_MAPPING_ENABLED', true),
        'overlap_threshold' => env('OCR_COORDINATE_MAPPING_OVERLAP_THRESHOLD', 0.60),
        'containment_threshold' => env('OCR_COORDINATE_MAPPING_CONTAINMENT_THRESHOLD', 0.70),
        'mapping_confidence_threshold' => env('OCR_COORDINATE_MAPPING_CONFIDENCE_THRESHOLD', 0.75),
    ],

    'cell_ocr' => [
        'enabled' => env('OCR_CELL_OCR_ENABLED', true),
        'provider' => env('OCR_CELL_OCR_PROVIDER', 'local'),
        'minimum_confidence' => env('OCR_CELL_OCR_MINIMUM_CONFIDENCE', 80),
        'enable_bbox' => env('OCR_CELL_OCR_ENABLE_BBOX', true),
        'enable_confidence' => env('OCR_CELL_OCR_ENABLE_CONFIDENCE', true),
        'enable_debug' => env('OCR_CELL_OCR_ENABLE_DEBUG', false),
        'max_parallel_cells' => env('OCR_CELL_OCR_MAX_PARALLEL_CELLS', 64),
        'temp_directory' => env('OCR_CELL_OCR_TEMP_DIRECTORY', storage_path('app/temp')),
        'keep_payload_when_debug' => env('OCR_CELL_OCR_KEEP_PAYLOAD_WHEN_DEBUG', false),
        'normalization' => [
            'H' => 'H',
            'I' => 'I',
            'S' => 'S',
            'A' => 'A',
            '.' => 'H',
            ',' => 'H',
            '/' => 'H',
            '-' => 'H',
        ],
    ],

    'student_row_matcher' => [
        'enabled' => env('OCR_STUDENT_ROW_MATCHER_ENABLED', true),
        'minimum_confidence' => env('OCR_STUDENT_ROW_MATCHER_MINIMUM_CONFIDENCE', 70),
        'enable_quality_score' => env('OCR_STUDENT_ROW_MATCHER_ENABLE_QUALITY_SCORE', true),
        'enable_debug' => env('OCR_STUDENT_ROW_MATCHER_ENABLE_DEBUG', false),
    ],

    'quality' => [
        'mode'                      => env('OCR_QUALITY_MODE', 'adaptive'),
        'allow_vision_on_warning'   => env('OCR_ALLOW_VISION_ON_WARNING', true),
        'hard_fail_only'            => env('OCR_HARD_FAIL_ONLY', true),
        'min_score'                 => env('OCR_QUALITY_MIN_SCORE', 45),
        'warning_score'             => env('OCR_QUALITY_WARNING_SCORE', 70),
        'classification_threshold'  => env('OCR_QUALITY_CLASSIFICATION_THRESHOLD', 60),
        'max_rotation'              => env('OCR_QUALITY_MAX_ROTATION', 8),
        'min_resolution'            => env('OCR_QUALITY_MIN_RESOLUTION', 900),
        'brightness' => [
            'warning' => env('OCR_QUALITY_BRIGHTNESS_WARNING', 35),
            'failed'  => env('OCR_QUALITY_BRIGHTNESS_FAIL', 10),
        ],
        'contrast' => [
            'warning' => env('OCR_QUALITY_CONTRAST_WARNING', 25),
            'failed'  => env('OCR_QUALITY_CONTRAST_FAIL', 5),
        ],
        'blur' => [
            'warning' => env('OCR_QUALITY_BLUR_WARNING', 55),
            'failed'  => env('OCR_QUALITY_BLUR_FAIL', 20),
        ],
        'noise' => [
            'warning' => env('OCR_QUALITY_NOISE_WARNING', 60),
            'failed'  => env('OCR_QUALITY_NOISE_FAIL', 20),
        ],
    ],

    'decision_engine' => [
        'mode' => env('OCR_DECISION_MODE', 'adaptive'),
        'allow_vision_on_warning' => env('OCR_DECISION_ALLOW_VISION_ON_WARNING', true),
        'enable_auto_preprocessing' => env('OCR_DECISION_ENABLE_AUTO_PREPROCESSING', true),
        'enable_recoverable_warning' => env('OCR_DECISION_ENABLE_RECOVERABLE_WARNING', true),
        'block_only_hard_fail' => env('OCR_DECISION_BLOCK_ONLY_HARD_FAIL', true),
    ],

    'decision_rules' => [
        'stop_on_document_failed' => env('OCR_DECISION_STOP_ON_DOCUMENT_FAILED', true),
        'stop_on_quality_failed'  => env('OCR_DECISION_STOP_ON_QUALITY_FAILED', false),
    ],

    'benchmark' => [
        'enabled' => env('OCR_BENCHMARK_ENABLED', true),
        'schema_version' => env('OCR_BENCHMARK_SCHEMA_VERSION', '1.0'),
        'save_json' => env('OCR_BENCHMARK_SAVE_JSON', true),
        'save_csv' => env('OCR_BENCHMARK_SAVE_CSV', true),
        'save_summary_log' => env('OCR_BENCHMARK_SAVE_SUMMARY_LOG', true),
        'save_artifacts' => env('OCR_BENCHMARK_SAVE_ARTIFACTS', false),
        'cleanup_days' => env('OCR_BENCHMARK_CLEANUP_DAYS', 30),
        'dataset_root' => env('OCR_BENCHMARK_DATASET_ROOT', 'tests/attendance-ai/dataset'),
        'report_root' => env('OCR_BENCHMARK_REPORT_ROOT', 'tests/attendance-ai/reports'),
    ],

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
