
@extends('layouts.app')

@section('content')

<section class="flex flex-col md:flex-row flex-1">
  <div class="hidden lg:block lg:w-[720px] bg-gray-200"></div>
  <div class="w-full lg:w-1/2 bg-white flex flex-col justify-end px-6 md:px-16 lg:px-28 pb-20 lg:pb-40">
    <h1 class="text-3xl font-semibold text-start pt-12 pb-24 lg:pb-52">Indietech</h1>
    <h2 class="text-3xl font-bold text-start pb-5">Sign In</h2>
   
    <div class="border rounded-lg px-6 py-6">
      <form id="loginForm" class="flex flex-col gap-6">
        <div class="flex flex-col gap-2">
          <label class="block text-sm font-medium">Email</label>
          <input
            type="text"
            name="email"
            placeholder="Enter your email"
            class="w-full border border-gray-300 rounded-md px-4 py-3 focus:outline-none focus:ring-2 focus:ring-stone-400"
          />
        </div>
      
        <div class="flex flex-col gap-2">
          <label class="block text-sm font-medium">Password</label>
          <input
            type="password"
            name="password"
            placeholder="Enter your password"
            class="w-full border border-gray-300 rounded-md px-4 py-3 focus:outline-none focus:ring-2 focus:ring-stone-400"
          />
        </div>
      
        <button
          type="submit"
          class="w-full bg-black text-white py-3 rounded-md hover:bg-gray-800 transition"
        >
          Sign In
        </button>

        <div class="text-left">
          <a href="#" class="text-sm text-gray-600 hover:text-black underline">
            Forgot password?
          </a>
        </div>
        <a rel="nofollow" href="https://indietech.io/wp-json/wslu-social-login/type/google" class="w-full bg-black text-white py-3 rounded-md hover:bg-gray-800 transition"> Sign In Using Google </a>
        {{-- <button
          type="button"
          class="w-full bg-black text-white py-3 rounded-md hover:bg-gray-800 transition"
        >
          Sign In Using Google
        </button> --}}

      </form>

      <div class="pt-6 text-center">
        <p class="text-sm text-gray-600">
          Don't have an account? 
          <a href="/register" class="text-black underline hover:text-gray-800">
            Register here
          </a>
        </p>
      </div>

    </div>
  </div>
</section>
  {{-- @while(have_posts()) @php(the_post())
    @include('partials.page-header')
    @include('partials.content-page')
  @endwhile --}}
@endsection
