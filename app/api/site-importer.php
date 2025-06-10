<?php

function clone_site_from_demo($source_blog_id, $target_blog_id) {
    require_once ABSPATH . 'wp-admin/includes/export.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';

    switch_to_blog($source_blog_id);

    $export_args = [
        'content' => 'all'
    ];

    ob_start();
    export_wp($export_args);
    $export_data = ob_get_clean();

    $tmp_dir = wp_upload_dir();
    $export_file_path = trailingslashit($tmp_dir['basedir']) . "export_demo_site_{$source_blog_id}.xml";
    file_put_contents($export_file_path, $export_data);

    restore_current_blog();

    if (!class_exists('WP_Import')) {
        require_once ABSPATH . 'wp-content/plugins/wordpress-importer/wordpress-importer.php';
    }

    switch_to_blog($target_blog_id);

    $importer = new WP_Import();
    $importer->fetch_attachments = true; 

    ob_start();
    $importer->import($export_file_path);
    ob_end_clean();

    restore_current_blog();

    unlink($export_file_path);
}

