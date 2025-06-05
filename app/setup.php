<?php
/**
 * Theme setup.
 */

namespace App;

use Illuminate\Support\Facades\Vite;

require get_template_directory() . '/app/custom-log.php'; 

/**
 * Inject styles into the block editor.
 *
 * @return array
 */
add_filter('block_editor_settings_all', function ($settings) {
    $style = Vite::asset('resources/css/editor.css');

    $settings['styles'][] = [
        'css' => "@import url('{$style}')",
    ];

    return $settings;
});

/**
 * Inject scripts into the block editor.
 *
 * @return void
 */
add_filter('admin_head', function () {
    if (! get_current_screen()?->is_block_editor()) {
        return;
    }

    $dependencies = json_decode(Vite::content('editor.deps.json'));

    foreach ($dependencies as $dependency) {
        if (! wp_script_is($dependency)) {
            wp_enqueue_script($dependency);
        }
    }

    echo Vite::withEntryPoints([
        'resources/js/editor.js',
    ])->toHtml();
});

/**
 * Use the generated theme.json file.
 *
 * @return string
 */
add_filter('theme_file_path', function ($path, $file) {
    return $file === 'theme.json'
        ? public_path('build/assets/theme.json')
        : $path;
}, 10, 2);

/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    /**
     * Disable full-site editing support.
     *
     * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
     */
    remove_theme_support('block-templates');

    /**
     * Register the navigation menus.
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage'),
    ]);

    /**
     * Disable the default block patterns.
     *
     * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
     */
    remove_theme_support('core-block-patterns');

    /**
     * Enable plugins to manage the document title.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Enable post thumbnail support.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable responsive embed support.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support('responsive-embeds');

    /**
     * Enable HTML5 markup support.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
    ]);

    /**
     * Enable selective refresh for widgets in customizer.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
     */
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('roots/acorn');
}, 20);

/**
 * Register the theme sidebars.
 *
 * @return void
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ];

    register_sidebar([
        'name' => __('Primary', 'sage'),
        'id' => 'sidebar-primary',
    ] + $config);

    register_sidebar([
        'name' => __('Footer', 'sage'),
        'id' => 'sidebar-footer',
    ] + $config);
});


/**
 * Register role member.
 * and setting capabilities
 *
 * @return void
 */
add_action('init', function() {
    if (!get_role('member')) {
        add_role('member', 'Member', [
            'read' => true,
            'create_sites' => true, // Multisite: izinkan buat site
        ]);
    }
});


add_filter('show_admin_bar', function ($show) {
    if (!current_user_can('manage_options')) {
        return false;
    }
    return $show;
});

add_action('admin_init', function() {
    if (!current_user_can('manage_options') && !wp_doing_ajax()) {
        wp_redirect(home_url('/dashboard/'));
        exit;
    }
});

add_filter('login_redirect', function($redirect_to, $request, $user){
    if (isset($user->roles) && in_array('member', $user->roles)) {
        return home_url('/dashboard/');
    }

    return $redirect_to;
}, 10, 3);

add_action('admin_menu', function() {
    if (current_user_can('member') && is_network_admin()) {
        wp_redirect(home_url('/dashboard/'));
        exit;
    }
});


add_filter('wp_insert_site_data', function ($data) {
    if (isset($data['domain'])) {
        $data['domain'] = preg_replace('|^https?://|', '', $data['domain']);
        $data['domain'] = strtolower($data['domain']); 
    }

    if (isset($data['home_url'])) {
        $data['home_url'] = preg_replace('|^http://|', 'https://', $data['home_url']);
    }

    if (isset($data['site_url'])) {
        $data['site_url'] = preg_replace('|^http://|', 'https://', $data['site_url']);
    }

    return $data;
});

add_action('wp_initialize_site', function ($site) {
    $blog_id = $site->blog_id;
    switch_to_blog($blog_id);

    $domain = get_option('siteurl');
    if (strpos($domain, 'http://') === 0) {
        $https_url = preg_replace('|^http://|', 'https://', $domain);
        update_option('siteurl', $https_url);
        update_option('home', $https_url);
    }

    restore_current_blog();
}, 100);

add_action('init', function () {
    if (strpos($_SERVER['REQUEST_URI'], 'wp-signup.php') !== false) {
        wp_redirect(home_url('/register'));
        exit;
    }
});