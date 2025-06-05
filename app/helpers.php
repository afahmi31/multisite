<?php
function mst_api_success($data = [], $message = '', $status = 200) {
    $response = [];
    if ($message) $response['message'] = $message;
    if ($data)    $response['data']    = $data;
    return new WP_REST_Response($response, $status);
}

/**
 * Error Response Helper
 * Only return 'error' (string) and/or 'errors' (per field), tanpa 'data' dan 'message'
 */
function mst_api_error($error = '', $status = 500, $errors = []) {
    $response = [];
    if ($error)         $response['error']  = $error;
    if (!empty($errors)) $response['errors'] = (object)$errors;
    return new WP_REST_Response($response, $status);
}

function mst_log_error($context, $exception) {
    error_log("[mytheme][API][$context] " . $exception->getMessage());
    if (defined('WP_DEBUG') && WP_DEBUG && $exception instanceof Throwable) {
        error_log(print_r($exception->getTraceAsString(), true));
    }
}