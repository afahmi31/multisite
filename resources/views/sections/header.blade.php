<header class="banner pt-12 pb-[63px] container">
  <a class="brand text-[32px] font-semibold" href="{{ home_url('/') }}">
    {!! $siteName !!}
  </a>

  @if (has_nav_menu('primary_navigation') && get_post_slug(get_the_ID()) !== 'dashboard')
    <nav class="nav-primary" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
      {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav', 'echo' => false]) !!}
    </nav>
  @endif

  @if (!is_user_logged_in())
  <div class="header-action-wrapper flex gap-1 items-center">
    <a href="/register" class="bg-white border border-solid border-black px-8 py-4 text-black text-[16px] leading-none rounded-full">Register</a>
    <a href="/login" class="bg-black border border-solid border-black px-8 py-4 text-white text-[16px] leading-none rounded-full">login</a>
  </div>
  @else
      <div>

      </div>
  @endif
  
</header>
