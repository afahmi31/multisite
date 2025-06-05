<?php

function mst_rest_activity($data) {
  $log_dir  = WP_CONTENT_DIR . '/logs';
  $log_file = $log_dir . '/rest-api.log';

  if (! file_exists($log_dir)) {
    mkdir($log_dir, 0755, true);
  }

  $log_entry = sprintf(
    "[%s] [%s] [%s] %s | IP: %s | USER: %s\n",
    date('Y-m-d H:i:s'),
    strtoupper($data['method']),
    $data['endpoint'],
    $data['message'],
    $_SERVER['REMOTE_ADDR'] ?? 'unknown',
    get_current_user_id() ?: 'guest'
  );

  file_put_contents($log_file, $log_entry, FILE_APPEND);
}