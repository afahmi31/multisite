<?php

use Roots\Acorn\Application;

function dump_wp_query() {
    global $wp_query;
    echo '<pre>';
    print_r($wp_query);
    echo '</pre>';
}

function get_post_slug($postid = ''){
    if (empty($postid)) {
        return ;
    }

    $post = get_post( $postid );
    if (empty($post)) {
        return ;
    }

    return $post->post_name;
}

function mst_validate_input($request, $rules) {
  $errors = [];

  foreach ($rules as $field => $ruleSet) {
    $value = isset($request[$field]) ? $request[$field] : null;
    $ruleParts = explode('|', $ruleSet);

    foreach ($ruleParts as $rule) {
      if ($rule === 'required' && empty($value)) {
        $errors[$field][] = 'Required.';
      }

      if ($rule === 'email' && !empty($value) && !is_email($value)) {
        $errors[$field][] = 'Email format invalidate.';
      }

      if (preg_match('/^min:(\d+)$/', $rule, $matches)) {
        if (strlen($value) < $matches[1]) {
          $errors[$field][] = 'Min ' . $matches[1] . ' character.';
        }
      }

      if ($rule === 'string' && !is_string($value)) {
        $errors[$field][] = 'Require string value.';
      }
    }
  }

  if (!empty($errors)) {
    return mst_response_error(
      'input_invalid', 
      'Error input invalid',
      [
        'status' => 422,
        'fields' => $errors
      ] 
    );
  }

  return true;
}

function mst_response_success($data = [], $status = 200, $header = []) {
  return new WP_REST_Response($data, $status, $header);
}

function mst_response_error($code = '', $message = '', $data = []){
  return new WP_Error($code, $message, $data);
}

function mst_get_post_id_by_title( string $title = '', string $post_type = 'post' ): int {
    $posts = get_posts(
        array(
            'post_type'              => $post_type,
            'title'                  => $title,
            'numberposts'            => 1,
            'update_post_term_cache' => false,
            'update_post_meta_cache' => false,
            'orderby'                => 'post_date ID',
            'order'                  => 'ASC',
            'fields'                 => 'ids'
        )
    );

    error_log('mst_get_post_id_by_title -> ' . $title . '|' . $post_type);
    return empty( $posts ) ? get_the_ID() : $posts[0];
}

function mst_is_user_verified($user_id = null){
  if (!is_user_logged_in(  )) {
    return ;
  }

  if (is_null($user_id)) {
    $user_id = get_current_user_id(  );
  }

  if (!function_exists('get_field')) {
    return ;
  }

  return get_field('mst_verified','user_'.$user_id);
}