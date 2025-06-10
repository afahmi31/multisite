<?php
add_action('wp_ajax_check_site_availability', 'mcd_check_site_availability');
add_action('wp_ajax_nopriv_check_site_availability', 'mcd_check_site_availability');

function mcd_check_site_availability($site_name) {
    if (empty($site_name)) {
        if (!isset($_GET['site_name']) || empty($_GET['site_name'])) {
            wp_send_json(['available' => false]);
        }
        $site_name = sanitize_user($_GET['site_name']);
    }

    $site = get_current_site();
    $parent_domain = $site->domain;

    $domain = $site_name . '.' . $parent_domain;
    $site_exists = domain_exists($domain, '/', 1);
    if ($site_exists) {
        wp_send_json(['available' => false]);
        return false;
    } else {
         wp_send_json(['available' => true]);
         return true;
    }
}

add_action('wp_ajax_check_site_title_availability', 'mcd_check_site_title_availability');
add_action('wp_ajax_nopriv_check_site_title_availability', 'mcd_check_site_title_availability');

function mcd_check_site_title_availability($site_title) {
    global $wpdb;
    if (empty($site_title)) {
        if (!isset($_GET['site_title']) || empty($_GET['site_title'])) {
            wp_send_json(['available' => false]);
        }
        $site_title = sanitize_text_field($_GET['site_title']);
    }
    $found = false;

    $sites = get_sites();

    foreach ($sites as $site) {
        $blog_id = $site->blog_id;

        switch_to_blog($blog_id);
        $current_title = get_option('blogname');
        restore_current_blog();

        if (strtolower($current_title) === strtolower($site_title)) {
            $found = true;
            break;
        }
    }

    if ($found) {
        wp_send_json(['available' => false]);
        return false;
    } else {
        wp_send_json(['available' => true]);
        return true;
    }
}