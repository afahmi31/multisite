<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});


// add_filter('rest_authentication_errors', function ($result) {
//   if (! is_user_logged_in() && ! preg_match('#^/custom/v1/#', $_SERVER['REQUEST_URI']) ) {
//     return new WP_Error('rest_cannot_access', 'REST API dibatasi.', ['status' => rest_authorization_required_code()]);
//   }
//   return $result;
// });

// nonce life on 10 minutes
add_filter('nonce_life', function() {
  return 60 * 10; 
});