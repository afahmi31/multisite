@extends('layouts.app')
@section('content')
<section class="flex flex-col md:flex-row flex-1">
  <div class="hidden lg:block lg:w-1/2 bg-gray-200"></div>
  <div class="w-full lg:w-1/2 bg-white flex flex-col justify-end px-6 md:px-16 lg:px-28 pb-20 lg:pb-40">
    <h1 class="text-3xl font-semibold text-start pt-12 pb-24 lg:pb-52">Indietech</h1>
    <h2 class="text-3xl font-bold text-start pb-5">Register</h2>
    <div class="border rounded-lg px-6 py-6">
      <form id="registerForm" method="post" novalidate class="flex flex-col gap-6">
        <div class="flex flex-col gap-2">
          <label class="block text-sm font-medium">First Name</label>
          <input type="text" name="first_name" placeholder="Enter your first name" class="w-full border border-gray-300 rounded-md px-4 py-3 focus:outline-none focus:ring-2 focus:ring-stone-400" />
          <div class="invalid-feedback text-xs text-red-600 mt-1" data-error="first_name"></div>
        </div>
        <div class="flex flex-col gap-2">
          <label class="block text-sm font-medium">Last Name</label>
          <input type="text" name="last_name" placeholder="Enter your last name" class="w-full border border-gray-300 rounded-md px-4 py-3 focus:outline-none focus:ring-2 focus:ring-stone-400" />
          <div class="invalid-feedback text-xs text-red-600 mt-1" data-error="last_name"></div>
        </div>
        <div class="flex flex-col gap-2">
          <label class="block text-sm font-medium">Email</label>
          <input type="email" name="email" placeholder="Enter your email" class="w-full border border-gray-300 rounded-md px-4 py-3 focus:outline-none focus:ring-2 focus:ring-stone-400" />
          <div class="invalid-feedback text-xs text-red-600 mt-1" data-error="email"></div>
        </div>
        <div class="flex flex-col gap-2">
          <label class="block text-sm font-medium">Password</label>
          <input type="password" name="password" placeholder="Enter your password" class="w-full border border-gray-300 rounded-md px-4 py-3 focus:outline-none focus:ring-2 focus:ring-stone-400" />
          <div class="invalid-feedback text-xs text-red-600 mt-1" data-error="password"></div>
        </div>
        <div class="flex flex-col gap-3 pt-4">
          <div class="flex items-start gap-3">
            <input type="checkbox" id="terms" name="terms" class="w-4 h-4 border border-gray-400 rounded mt-1" />
            <label class="text-sm text-gray-400 font-normal leading-snug max-w-xs" for="terms">
              By checking this you agree with our terms & conditions about user privacy
            </label>
          </div>
          <div class="invalid-feedback text-xs text-red-600 mt-1" data-error="terms"></div>
        </div>
        <div class="flex flex-col gap-3 pt-6">
          <button type="submit" class="w-full bg-black text-white py-3 rounded-md hover:bg-gray-800 transition">
            Register
          </button>
          <button type="button" class="w-full bg-black text-white py-3 rounded-md hover:bg-gray-800 transition">
            Register Using Google Account
          </button>
          <div class="invalid-feedback text-xs text-red-600 mt-1" data-error="form"></div>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection