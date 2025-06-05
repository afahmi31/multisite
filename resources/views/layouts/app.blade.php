<!doctype html>
<html @php(language_attributes())>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php(do_action('get_header'))
    @php(wp_head())
    <script>
      const wpApiSettings = @json([
        'root' => esc_url_raw(rest_url()),
        'nonce' => wp_create_nonce('wp_rest'),
        'user_id' => get_current_user_id()
      ]);
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>

  <body @php(body_class())>
    @php(wp_body_open())

    <div id="app">
      <a class="sr-only focus:not-sr-only" href="#main">
        {{ __('Skip to content', 'sage') }}
      </a>

      @if (get_post_slug(get_the_ID()) !== 'register' && get_post_slug(get_the_ID()) !== 'login' && get_post_slug(get_the_ID()) !== 'dashboard')
        @include('sections.header')
      @endif

        <main id="main" class="main <?php echo 'content-wrapper__' . get_post_slug(get_the_ID()) . ' flex'; ?>">
          @if (get_post_slug(get_the_ID()) == 'dashboard')
      <aside class="max-w-[296px] w-full bg-[#EFEFEF] p-[32px] h-screen flex flex-col justify-between" >
        <div class="flex flex-col gap-4">
          <div class="flex justify-between items-center">
            <h2 class="text-primary">
              <a class="brand text-[32px] font-semibold" href="{{ home_url('/') }}">
              {!! $siteName !!}
            </a></h2>
            <div>
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" >
                <path
                  d="M9 3V21M5 3H19C20.1046 3 21 3.89543 21 5V19C21 20.1046 20.1046 21 19 21H5C3.89543 21 3 20.1046 3 19V5C3 3.89543 3.89543 3 5 3Z"
                  stroke="#1E1E1E"
                  stroke-width="2.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
              </svg>
            </div>
          </div>
          <nav class="flex flex-col items-start gap-2">
            <a href="#" class="p-4 text-black flex items-center bg-white w-full gap-2 rounded-[8px]" >
              <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" >
                <path
                  d="M7.5 18.3333V9.99996H12.5V18.3333M2.5 7.49996L10 1.66663L17.5 7.49996V16.6666C17.5 17.1087 17.3244 17.5326 17.0118 17.8451C16.6993 18.1577 16.2754 18.3333 15.8333 18.3333H4.16667C3.72464 18.3333 3.30072 18.1577 2.98816 17.8451C2.67559 17.5326 2.5 17.1087 2.5 16.6666V7.49996Z"
                  stroke="#1E1E1E"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
              </svg>
              <span class="">Dashboard</span>
            </a>
            <a href="#" class="p-4 text-black flex items-center w-full gap-2 rounded-[8px]" >
              <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" >
                <path
                  d="M18.3333 10H13.3333L11.6666 12.5H8.33329L6.66663 10H1.66663M18.3333 10V15C18.3333 15.4421 18.1577 15.866 17.8451 16.1786C17.5326 16.4911 17.1087 16.6667 16.6666 16.6667H3.33329C2.89127 16.6667 2.46734 16.4911 2.15478 16.1786C1.84222 15.866 1.66663 15.4421 1.66663 15V10M18.3333 10L15.4583 4.25837C15.3203 3.9807 15.1076 3.74702 14.8441 3.58361C14.5806 3.4202 14.2767 3.33354 13.9666 3.33337H6.03329C5.72322 3.33354 5.41935 3.4202 5.15583 3.58361C4.89231 3.74702 4.67961 3.9807 4.54163 4.25837L1.66663 10"
                  stroke="#1E1E1E"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
              </svg>
              <span class="">Dashboard</span>
            </a>
            <a href="#" class="p-4 text-black flex items-center w-full gap-2 rounded-[8px]" >
              <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" >
                <path
                  d="M6.66663 17.5H13.3333M9.99996 14.1667V17.5M3.33329 2.5H16.6666C17.5871 2.5 18.3333 3.24619 18.3333 4.16667V12.5C18.3333 13.4205 17.5871 14.1667 16.6666 14.1667H3.33329C2.41282 14.1667 1.66663 13.4205 1.66663 12.5V4.16667C1.66663 3.24619 2.41282 2.5 3.33329 2.5Z"
                  stroke="#1E1E1E"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
              </svg>
  
              <span class="">Dashboard</span>
            </a>
            <a href="#" class="p-4 text-black flex items-center w-full gap-2 rounded-[8px]" >
              <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" >
                <g clip-path="url(#clip0_3_211)">
                  <path
                    d="M17.5 6.66667V17.5H2.50004V6.66667M8.33337 10H11.6667M0.833374 2.5H19.1667V6.66667H0.833374V2.5Z"
                    stroke="#1E1E1E"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                </g>
                <defs>
                  <clipPath id="clip0_3_211">
                    <rect width="20" height="20" fill="white" />
                  </clipPath>
                </defs>
              </svg>
  
              <span class="">Dashboard</span>
            </a>
            <a href="#" class="p-4 text-black flex items-center w-full gap-2 rounded-[8px]" >
              <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" >
                <path
                  d="M7.5 17.5H4.16667C3.72464 17.5 3.30072 17.3244 2.98816 17.0118C2.67559 16.6993 2.5 16.2754 2.5 15.8333V4.16667C2.5 3.72464 2.67559 3.30072 2.98816 2.98816C3.30072 2.67559 3.72464 2.5 4.16667 2.5H7.5M13.3333 14.1667L17.5 10M17.5 10L13.3333 5.83333M17.5 10H7.5"
                  stroke="#1E1E1E"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
              </svg>
  
              <span class="">Dashboard</span>
            </a>
          </nav>
        </div>
        <button class="flex gap-4 items-center rounded-[8px] border border-gray-300 logout-button" >
          <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" >
            <path
              d="M7.5 17.5H4.16667C3.72464 17.5 3.30072 17.3244 2.98816 17.0118C2.67559 16.6993 2.5 16.2754 2.5 15.8333V4.16667C2.5 3.72464 2.67559 3.30072 2.98816 2.98816C3.30072 2.67559 3.72464 2.5 4.16667 2.5H7.5M13.3333 14.1667L17.5 10M17.5 10L13.3333 5.83333M17.5 10H7.5"
              stroke="#1E1E1E"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
          </svg>
          <span> Logout </span>
        </button>
      </aside>    
      @endif
          @yield('content')
        </main>

      @hasSection('sidebar')
        @if (get_post_slug(get_the_ID()) !== 'dashboard')  
          <aside class="sidebar">
            @yield('sidebar')
          </aside>
        @endif
      @endif
      
      @if (get_post_slug(get_the_ID()) !== 'register' && get_post_slug(get_the_ID()) !== 'login')  
        @include('sections.footer')
      @endif
    </div>
    @php(do_action('get_footer'))
    @php(wp_footer())
  </body>
</html>
