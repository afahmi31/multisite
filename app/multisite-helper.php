<?php

use function Laravel\Prompts\error;

/**
 * Clone demo site (e.g., compro1.indietech.io) into a new subsite
 * Assumes this function is triggered after a user chooses a theme
 * and submits a subdomain (e.g., client1)
 */

 function mst_get_demo_sites(WP_REST_Request $request) {
    if (!is_multisite()) {
        return new WP_Error('not_multisite', 'This is not a multisite network.', ['status' => 400]);
    }

    $nonce = $request->get_header('X-WP-Nonce');
    if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
        mst_rest_activity(
            [
                'method'   => 'POST',
                'endpoint' => '/custom/v1/available-themes',
                'message'  => "nonce invalid"
            ]
        );
        wp_send_json_error(['message' => 'Nonce is invalid or has expired.'], 403);
    }
    if (!is_user_logged_in()) {
        mst_rest_activity(
            [
                'method'   => 'POST',
                'endpoint' => '/custom/v1/available-themes',
                'message'  => 'Error: login required',
            ]
        );
        wp_send_json_error(['message' => 'You must be logged in'], 401);
    }

    $sites = get_sites([
        'public' => 1,
        'deleted' => 0,
        'archived' => 0,
    ]);

    foreach ($sites as $site) {
        $blog_id = $site->blog_id;

        switch_to_blog($blog_id);
        $admins = get_users([
            'role'    => 'administrator',
            'fields'  => ['user_login', 'user_email'],
            'number'  => 1,
        ]);
        restore_current_blog();

        if (!empty($admins)) {
            if ($site->blog_id == 1) {
                        continue; 
                    }
             $details = get_blog_details($site->blog_id);

                switch_to_blog($site->blog_id);
                $theme = wp_get_theme();

                $output[] = [
                    'blog_id'        => $site->blog_id,
                    'domain'         => $details->domain,
                    'path'           => $details->path,
                    'siteurl'        => untrailingslashit($details->siteurl),
                    'blogname'       => get_option('blogname'),
                    'name'           => $theme->get('Name'),
                    'theme_version'  => $theme->get('Version'),
                    'stylesheet_uri' => $theme->get_stylesheet_directory_uri(),
                ];
                restore_current_blog();
        }
    }

    return rest_ensure_response($output);
}

function clone_site_content($source_blog_id, $target_blog_id) {
    switch_to_blog($source_blog_id);
    $posts = get_posts([
        'post_type' => ['post', 'page'],
        'numberposts' => -1,
    ]);
    restore_current_blog();
    switch_to_blog($target_blog_id);
    foreach ($posts as $post) {
        $new_post_id = wp_insert_post([
            'post_title'   => $post->post_title,
            'post_content' => $post->post_content,
            'post_status'  => $post->post_status,
            'post_type'    => $post->post_type,
        ]);

        if ($post->post_title === 'Homepage') {
            update_option('page_on_front', $new_post_id);
        }

        if (function_exists('get_fields')) {
            clone_site_acf_post_type($source_blog_id, $target_blog_id, $post->ID, $new_post_id);
        }
    }
    restore_current_blog();
    clone_site_media($source_blog_id, $target_blog_id);
    if (function_exists('get_fields')) {
        clone_site_acf_options($source_blog_id, $target_blog_id);
    }
}

function clone_site_media($source_blog_id, $target_blog_id) {
    switch_to_blog($source_blog_id);
    $media_items = get_posts([
        'post_type' => 'attachment',
        'numberposts' => -1,
    ]);
    restore_current_blog();

    foreach ($media_items as $media) {
        switch_to_blog($target_blog_id);
        $new_media = [
            'post_title'     => $media->post_title,
            'post_mime_type' => $media->post_mime_type,
            'post_type'      => 'attachment',
            'post_status'    => 'inherit',
        ];
        wp_insert_post($new_media);
        restore_current_blog();
    }
}

function clone_site_acf_options($source_blog_id, $target_blog_id) {
    if (!function_exists('get_fields')) {
        return ;
    }
    switch_to_blog($source_blog_id);
    $acf_fields = get_fields('options');
    restore_current_blog();

    if ($acf_fields) {
        switch_to_blog($target_blog_id);
        foreach ($acf_fields as $field_key => $field_value) {
            update_field($field_key, $field_value, 'options');
        }
        restore_current_blog();
    }
}

function clone_site_acf_post_type($source_blog_id, $target_blog_id, $post_id, $new_post_id) {
    if (!function_exists('get_fields')) {
        return ;
    }
    switch_to_blog($source_blog_id);
    $acf_fields = get_fields($post_id);
    restore_current_blog();

    if ($acf_fields) {
        switch_to_blog($target_blog_id);
        foreach ($acf_fields as $field_key => $field_value) {
            update_field($field_key, $field_value, $new_post_id);
        }
        restore_current_blog();
    }
}

function clone_site_settings($source_blog_id, $target_blog_id) {
    switch_to_blog($source_blog_id);
    $theme = wp_get_theme();
    $settings_to_copy = [
        'permalink_structure',
        'show_on_front',
        'timezone_string',
        'users_can_register',
        'start_of_week',
        'use_balanceTags',
        'use_smilies',
        'require_name_email',
        'comments_notify',
        'posts_per_rss',
        'rss_use_excerpt',
        'posts_per_page',
        'date_format',
        'time_format',
        'links_updated_date_format',
        'comment_moderation',
        'moderation_notify',
        'gmt_offset',
        'default_email_category',
        'comment_registration',
        'html_type',
        'use_trackback',
        'default_role',
        'tag_base',
        'show_avatars',
        'avatar_rating',
        'upload_url_path',
        'thumbnail_size_w',
        'thumbnail_size_h',
        'thumbnail_crop',
        'medium_size_w',
        'medium_size_h',
        'avatar_default',
        'large_size_w',
        'large_size_h',
        'image_default_link_type',
        'image_default_size',
        'image_default_align',
        'close_comments_for_old_posts',
        'close_comments_days_old',
        'thread_comments',
        'thread_comments_depth',
        'page_comments',
        'comments_per_page',
        'default_comments_page',
        'comment_order',
        'default_post_format',
        'link_manager_enabled',
        'finished_splitting_shared_terms',
        'site_icon',
        'medium_large_size_w',
        'medium_large_size_h',
        'wp_page_for_privacy_policy',
        'show_comments_cookies_opt_in',
        'wp_attachment_pages_enabled',
        'widget_block',
        'sidebars_widgets',
        'widget_pages',
        'widget_calendar',
        'widget_archives',
        'widget_media_audio',
        'widget_media_image',
        'widget_media_gallery',
        'widget_media_video',
        'widget_meta',
        'widget_search',
        'widget_recent',
        'widget_recent',
        'widget_tag_cloud',
        'widget_nav_menu',
        'widget_custom_html',
        'theme_mods_'.$theme->get_template()
    ];

    $settings = [];
    foreach ($settings_to_copy as $setting) {
        $settings[$setting] = get_option($setting);
    }

    restore_current_blog();

    switch_to_blog($target_blog_id);

    foreach ($settings as $key => $value) {
        update_option($key, $value);
    }

    restore_current_blog();
}

function clone_site_widgets($source_blog_id, $target_blog_id) {
    switch_to_blog($source_blog_id);
    $sidebars_widgets = get_option('sidebars_widgets');
    $all_widgets = [];
    foreach ($sidebars_widgets as $sidebar => $widgets) {
        if (is_array($widgets)) {
            foreach ($widgets as $widget) {
                $type = preg_replace('/-\d+$/', '', $widget);
                $widget_option = get_option("widget_{$type}");
                if ($widget_option) {
                    $all_widgets["widget_{$type}"] = $widget_option;
                }
            }
        }
    }
    restore_current_blog();

    switch_to_blog($target_blog_id);
    update_option('sidebars_widgets', $sidebars_widgets);
    foreach ($all_widgets as $option => $value) {
        update_option($option, $value);
    }
    restore_current_blog();
}

function clone_site_menus($source_blog_id, $target_blog_id) {
    switch_to_blog($source_blog_id);
    $menus = wp_get_nav_menus();
    $menu_data = [];
    $demo_theme_mod = get_theme_mods();
    foreach ($menus as $menu) {
        $items = wp_get_nav_menu_items($menu->term_id);
        $menu_data[] = [
            'name'  => $menu->name,
            'slug'  => $menu->slug,
            'items' => $items,
        ];
    }
    switch_to_blog($target_blog_id);
    $menu_mapping = [];
    $expected_location_to_menu_name = [
        'primary_navigation'   => 'Primary Menu',
        'secondary_navigation' => 'Secondary Menu',
    ];
    foreach ($menu_data as $menu) {
        $new_menu_id = wp_create_nav_menu($menu['name']);
        $menu_mapping[$menu['slug']] = $new_menu_id;

        foreach ($menu['items'] as $item) {
            if ($item->object === 'page') {
                $source_ID = mst_get_post_id_by_title($item->title, 'page');
                wp_update_nav_menu_item($new_menu_id, 0, [
                    'menu-item-title'     => $item->title,
                    'menu-item-url'       => get_permalink($source_ID),
                    'menu-item-status'    => 'publish',
                    'menu-item-type'      => $item->type,
                    'menu-item-object'    => $item->object,
                    'menu-item-object-id' => $source_ID,
                ]);
            } else {
                wp_update_nav_menu_item($new_menu_id, 0, [
                    'menu-item-title'     => $item->title,
                    'menu-item-url'       => $item->url,
                    'menu-item-status'    => 'publish',
                    'menu-item-type'      => $item->type,
                    'menu-item-object'    => $item->object,
                    'menu-item-object-id' => $item->object_id,
                ]);
            }
        }
    }
    $location_mapping = [];
    foreach ($expected_location_to_menu_name as $location => $menu_name) {
        foreach ($menu_mapping as $slug => $menu_id) {
            $menu_obj = wp_get_nav_menu_object($menu_id);
            if ($menu_obj && $menu_obj->name === str_replace('-','_',$menu_name)) {
                $location_mapping[$location] = $menu_id;
                break;
            }
        }
    }
    if (!empty($location_mapping)) {
        set_theme_mod('nav_menu_locations', $location_mapping);
    }
    restore_current_blog();
}