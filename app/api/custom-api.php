<?php

function mcd_user_login(WP_REST_Request $request) {
    $nonce = $request->get_header('X-WP-Nonce');
    if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
        mst_rest_activity(
            [
                'method'   => 'POST',
                'endpoint' => '/custom/v1/login',
                'message'  => "nonce invalid"
            ]
        );
        wp_send_json_error(['message' => 'Nonce is invalid or has expired.'], 403);
    }

    $creds = [
        'user_login'    => sanitize_text_field($request['email']),
        'user_password' => $request['password'],
        'remember'      => true,
    ];

    // $user = wp_signon($creds, is_ssl());
    
    $user = wp_signon($creds, false);

    if ( is_wp_error($user) ) {
        wp_send_json_error(['message' => $user->get_error_message()], 401);
        mst_rest_activity(
            [
                'method'   => 'POST',
                'endpoint' => '/custom/v1/login',
                'message'  => "Error : " . $user->get_error_message()
            ]
        );
        return ;
    }    
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID, true);
        do_action('mcd_act_login_success', $user->ID);
        mst_rest_activity(
            [
                'method'   => 'POST',
                'endpoint' => '/custom/v1/login',
                'message'  => "login success"
            ]
        );
        wp_send_json_success([
            'message' => 'Login berhasil.',
            'user_id' => $user->ID
        ]);
    
}

function mcd_create_site(WP_REST_Request $request) {
    $nonce = $request->get_header('X-WP-Nonce');
    if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
        mst_rest_activity(
            [
                'method'   => 'POST',
                'endpoint' => '/custom/v1/create-site',
                'message'  => "nonce invalid"
            ]
        );
        wp_send_json_error(['message' => 'Nonce is invalid or has expired.'], 403);
    }
    if (!is_user_logged_in()) {
        mst_rest_activity(
            [
                'method'   => 'POST',
                'endpoint' => '/custom/v1/create-site',
                'message'  => 'Error: login required',
            ]
        );
        wp_send_json_error(['message' => 'You must be logged in to create a site'], 401);
    }

    $user = wp_get_current_user();
    $domain = sanitize_title($request['site_name']);
    $title = sanitize_text_field($request['site_title']);
    $site_Template = sanitize_text_field ($request['site_template']);

    if (empty($domain)) {
        wp_send_json_error(['message' => 'Domain cannot be empty.'], 400);
    }

    if (empty($title)) {
        wp_send_json_error(['message' => 'Title cannot be empty.'], 400);
    }

    if (empty($site_Template)) {
        wp_send_json_error(['message' => 'Template cannot be empty.'], 400);
    }

    $demo_site = get_blog_details([
        'domain' => $site_Template,
    ]);

    $network = get_network();
    $site_url = $domain . '.' . preg_replace('|^www\.|', '', $network->domain);
    $site_id = wpmu_create_blog(
        $site_url,
        '/',
        $title,
        $user->ID
    );
    
    if (!$demo_site) {
        return new WP_Error('no_demo_site', 'Demo site not found');
    }

    if (is_wp_error($site_id)) {
        mst_rest_activity(
            [
                'method'   => 'POST',
                'endpoint' => '/custom/v1/create-site',
                'message'  => "Error: " .  $site_id->get_error_message()
            ]
        );
        wp_send_json_error(['message' => $site_id->get_error_message()], 400);
    }

    switch_to_blog($demo_site->blog_id);
    $theme = wp_get_theme();
    $stylesheet = $theme->get_stylesheet();
    $template = $theme->get_template();
    
    restore_current_blog();
    
    if (empty($domain) || empty($title)) {
        mst_rest_activity(
            [
                'method'   => 'POST',
                'endpoint' => '/custom/v1/create-site',
                'message'  => "domain or title exists."
            ]
        );
        wp_send_json_error(['message' => 'Site name dan site title wajib diisi.'], 400);
    }
    
    // Set default theme
    update_blog_option($site_id, 'stylesheet', $stylesheet);
    update_blog_option($site_id, 'template', $template);

    // Paksa URL HTTPS
    $https_url = 'https://' . $site_url;
    update_blog_option($site_id, 'siteurl', $https_url);
    update_blog_option($site_id, 'home', $https_url);

    restore_current_blog();

        // 'show_on_front',
        // 'page_on_front',

        clone_site_content($demo_site->blog_id, $site_id);
        clone_site_menus($demo_site->blog_id, $site_id);
        // clone_site_widgets($demo_site->blog_id, $site_id);
        clone_site_settings($demo_site->blog_id, $site_id);
    
    mst_rest_activity(
            [
                'method'   => 'POST',
                'endpoint' => '/custom/v1/create-site',
                'message'  => "Success created new site from " . $title . " - " .  $site_url
            ]
        );
    wp_send_json_success([
        'message' => 'Success created new site.',
        'site_id' => $site_id,
        'site_url' => $site_url
    ]);
}

function mcd_get_user_sites(WP_REST_Request $request) {
    $nonce = $request->get_header('X-WP-Nonce');
    if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
        mst_rest_activity(
            [
                'method'   => 'POST',
                'endpoint' => '/custom/v1/user-sites',
                'message'  => "Nonce invalid."
            ]
        );
        wp_send_json_error(['message' => 'Nonce is invalid or has expired.'], 403);
    }
    if (!is_user_logged_in()) {
        mst_rest_activity(
            [
                'method'   => 'POST',
                'endpoint' => '/custom/v1/user-sites',
                'message'  => "Error: login required."
            ]
        );
        return new WP_REST_Response([
            'success' => false,
            'message' => 'Anda harus login untuk melihat daftar website.'
        ], 401);
    }
    

    $user_id = get_current_user_id();
    $blogs = get_blogs_of_user($user_id);
    $sites = [];

     $main_site_url = get_home_url(get_main_site_id());
    foreach ($blogs as $blog_id => $blog) {
        // if($blog->siteurl !== $main_site_url){
        if($blog->domain !== 'indietech.test'){
            $sites[] = [
                'site_id'    => $blog->userblog_id ?? $blog_id,
                'site_url'   => $blog->siteurl,
                'site_name'  => $blog->blogname,
                'is_primary' => false
            ];
        }
    }

    mst_rest_activity(
            [
                'method'   => 'POST',
                'endpoint' => '/custom/v1/user-sites',
                'message'  => "Success: get user get site"
            ]
        );
    return new WP_REST_Response([
        'success' => true,
        'data' => $sites
    ], 200);
}

function custom_api_logout() {
    wp_logout();
    return new WP_REST_Response(['message' => 'Logged out successfully.'], 200);
}
