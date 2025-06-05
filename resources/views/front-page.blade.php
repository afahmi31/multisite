
@extends('layouts.app')



@section('content')
<?php
  if(isset($_GET['action']) && $_GET['action'] === 'verify_email') {
    $user  = isset($_GET['user']) ? $_GET['user'] : '';
    $token = isset($_GET['token']) ? $_GET['token'] : '';
    $email = isset($_GET['email']) ? $_GET['email'] : '';

    $result = mcd_verify_email_token($user, $token, $email);
    $redirect_url = add_query_arg([
        'action'  => 'verify_email',
        'success' => $result['success'] ? 'true' : 'false',
        'message' => urlencode($result['message']),
    ], site_url('/email-verification/'));
    wp_safe_redirect($redirect_url);
    exit;
  }
?>
  @while(have_posts()) @php(the_post())
    @include('partials.content-page')
  @endwhile
@endsection
