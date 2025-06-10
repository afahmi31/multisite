<?php

require get_template_directory() . '/app/api/custom-api.php'; 
require get_template_directory() . '/app/api/server-api.php'; 
require get_template_directory() . '/app/api/register-api.php'; 
require get_template_directory() . '/app/api/create-site.php'; 
require get_template_directory() . '/app/multisite.php'; 

add_action('rest_api_init', function() {
    register_rest_route('custom/v1', '/login', [
        'methods' => 'POST',
        'callback' => 'mcd_user_login',
        'permission_callback' => '__return_true',
    ]);
    register_rest_route('custom/v1', '/register', [
        'methods' => 'POST',
        'callback' => 'mcd_register_user',
        'permission_callback' => '__return_true',
    ]);
    register_rest_route('custom/v1', '/create-site', [
        'methods' => 'POST',
        'callback' => 'mcd_create_site',
        'permission_callback' => function() {
            return is_user_logged_in();
        },
    ]);
    register_rest_route('custom/v1', '/user-sites', [
        'methods' => 'GET',
        'callback' => 'mcd_get_user_sites',
        'permission_callback' => function() {
            return is_user_logged_in();
        }
    ]);
    register_rest_route('custom/v1', '/logout', [
        'methods'  => 'POST',
        'callback' => 'custom_api_logout',
        'permission_callback' => function () {
            return '__return_true';
        },
    ]);

    register_rest_route('mcd/v1', '/create-subdomain', [
        'methods'             => 'GET',
        'callback'            => 'mcd_rest_create_subdomain',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('mcd/v1', '/list-accounts', [
        'methods' => 'GET',
        'callback' => 'mcd_rest_list_accounts',
        'permission_callback' => '__return_true',
    ]);
    register_rest_route( 'mcd/v1', '/available-themes', [
        'methods'  => 'GET',
        'callback' => 'mst_get_demo_sites',
        'permission_callback' => '__return_true',
    ] );

     register_rest_route('mcd/v1', '/verify-email', [
        'methods'  => 'POST',
        'callback' => 'mcd_verify_email_token',
        'permission_callback' => '__return_true',
    ]);
});