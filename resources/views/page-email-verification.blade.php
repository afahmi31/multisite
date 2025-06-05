@extends('layouts.app')

<?php 
    $success = isset($_GET['success']) ? $_GET['success'] : 'false';
    $message = isset($_GET['message']) ? $_GET['message'] : '';
?>

@section('content')
<div class="verification-info <?php echo $success === 'true' ? 'success' : 'failed'; ?> p-10 h-[80vh] flex flex-col justify-center items-center">
  <h3 class="font-bold text-2xl text-center mb-3"><?php echo get_the_title(  ); ?></h3>
  <h5 class="font-bold text-lg text-center mb-5"><?php echo esc_html__( $message ); ?></h5>
  @if ($success === 'true' )
    @if (is_user_logged_in(  ))
      <a href="/dashboard" class="bg-black border border-solid border-black px-8 py-4 text-white text-[16px] leading-none rounded-full w-fit">Dashboard</a>
    @else
      <a href="/login" class="bg-black border border-solid border-black px-8 py-4 text-white text-[16px] leading-none rounded-full w-fit">Login</a> 
    @endif
  @else
    @if (is_user_logged_in(  ))
      <a href="/email-verification/?action=resend_email_verification" class="bg-black border border-solid border-black px-8 py-4 text-white text-[16px] leading-none rounded-full w-fit">Resend Verification</a> 
      @else
      <a href="/login" class="bg-black border border-solid border-black px-8 py-4 text-white text-[16px] leading-none rounded-full w-fit">Login</a> 
    @endif
  @endif
</div>
@endsection
