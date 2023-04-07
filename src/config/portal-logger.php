<?php

return [
    'logger' => [
        'url' => env('PORTAL_LOGGER_URL', 'https://api.kejaksaanri.id/portal-logger'),
        'path' => env('PORTAL_LOGGER_PATH', '/api/v1/logs/create'),
        'app_id' => env('PORTAL_LOGGER_APP_ID', 'DEFAULT'),
        'app_key' => env('PORTAL_LOGGER_APP_KEY', ''),
        'default_status_code' => env('PORTAL_LOGGER_STATUS_CODE', '200'),
        'is_ajax_log' => env('PORTAL_LOGGER_IS_AJAX_LOG', false),
        'user_guard' => env('PORTAL_LOGGER_USER_GUARD', 'web'),
        'user_id' => env('PORTAL_LOGGER_USER_ID_VAR', 'id'),
        'user_satker_var' => env('PORTAL_LOGGER_USER_SATKER_VAR', 'satker_id'),
        'user_nip_var' => env('PORTAL_LOGGER_USER_NIP_VAR', 'nip'),
        'user_username_var' => env('PORTAL_LOGGER_USER_USERNAME_VAR', 'username'),
        'user_name_var' => env('PORTAL_LOGGER_USER_NAME_VAR', 'name'),
        'request_timeout' => env('PORTAL_LOGGER_REQUEST_TIMEOUT', 15),
        'request_async' => env('PORTAL_LOGGER_REQUEST_ASYNC', true),
        'cache_user' => env('PORTAL_LOGGER_CACHE_USER', 86400),
        'is_enable' => env('PORTAL_LOGGER_IS_ENABLE', true)
    ],
    'http_logger' => [
        'channel' => env('PORTAL_HTTP_LOGGER_CHANNEL', 'portal-logger'),
        'level' => env('PORTAL_HTTP_LOGGER_LEVEL', 'info'),
        'log_profile' => \KejaksaanDev\PortalLogger\Logs\PortalLogProfile::class,
        'log_writer' => \KejaksaanDev\PortalLogger\Logs\PortalLogWriter::class,
    ]
];