
@extends('layouts.app')

@section('content')

<div class="flex-1">
  <div
    class="p-[32px] border-b-1 border-[#EFEFEF] flex justify-between items-center"
  >
    <h1 class="text-primary">Dashboard</h1>
    <div class="flex items-center gap-2">
      <div
        class="flex items-center gap-2 rounded-full border border-gray-300 px-4 py-2"
      >
        <input type="text" name="" id="" placeholder="Search..."
        class="border-none" />
        <svg
          width="16"
          height="16"
          viewBox="0 0 16 16"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M14 14L11.1 11.1M12.6667 7.33333C12.6667 10.2789 10.2789 12.6667 7.33333 12.6667C4.38781 12.6667 2 10.2789 2 7.33333C2 4.38781 4.38781 2 7.33333 2C10.2789 2 12.6667 4.38781 12.6667 7.33333Z"
            stroke="#1E1E1E"
            stroke-width="1.6"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
        </svg>
      </div>
      <button
        class="rounded-full w-[40px] h-[40px] bg-[#EFEFEF] flex items-center justify-center"
      >
        <svg
          width="20"
          height="20"
          viewBox="0 0 20 20"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M17.5 9.58336C17.5029 10.6832 17.2459 11.7683 16.75 12.75C16.162 13.9265 15.2581 14.916 14.1395 15.6078C13.021 16.2995 11.7319 16.6662 10.4167 16.6667C9.31678 16.6696 8.23176 16.4126 7.25 15.9167L2.5 17.5L4.08333 12.75C3.58744 11.7683 3.33047 10.6832 3.33333 9.58336C3.33384 8.26815 3.70051 6.97907 4.39227 5.86048C5.08402 4.7419 6.07355 3.838 7.25 3.25002C8.23176 2.75413 9.31678 2.49716 10.4167 2.50002H10.8333C12.5703 2.59585 14.2109 3.32899 15.441 4.55907C16.671 5.78915 17.4042 7.42973 17.5 9.16669V9.58336Z"
            stroke="#1E1E1E"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
        </svg>
      </button>
      <button
        class="rounded-full w-[40px] h-[40px] bg-[#EFEFEF] flex items-center justify-center"
      >
        <svg
          width="20"
          height="20"
          viewBox="0 0 20 20"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M11.4417 17.5C11.2952 17.7525 11.0849 17.9622 10.8319 18.1079C10.5788 18.2536 10.292 18.3303 10 18.3303C9.70802 18.3303 9.42116 18.2536 9.16814 18.1079C8.91513 17.9622 8.70484 17.7525 8.55833 17.5M15 6.66663C15 5.34054 14.4732 4.06877 13.5355 3.13109C12.5979 2.19341 11.3261 1.66663 10 1.66663C8.67392 1.66663 7.40215 2.19341 6.46447 3.13109C5.52678 4.06877 5 5.34054 5 6.66663C5 12.5 2.5 14.1666 2.5 14.1666H17.5C17.5 14.1666 15 12.5 15 6.66663Z"
            stroke="#1E1E1E"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
        </svg>
      </button>
      <div
        class="rounded-full w-[40px] h-[40px] bg-[#919191] flex items-center justify-center"
      ></div>
    </div>
  </div>
  <div class="flex items-center justify-between pt-[32px] px-[32px]">
    <h2 class="text-black font-semibold text-base">Recent Project</h2>
    <button
      class="bg-black rounded-lg text-white px-4 py-2 flex items-center gap-2 start-new-project"
    >
      <span> Start New Project </span>
      <svg
        width="16"
        height="16"
        viewBox="0 0 16 16"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M8.00001 3.33337V12.6667M3.33334 8.00004H12.6667"
          stroke="#F5F5F5"
          stroke-width="1.6"
          stroke-linecap="round"
          stroke-linejoin="round"
        />
      </svg>
    </button>
  </div>


  <div
  class="w-full flex justify-center items-center h-[calc(100vh-200px)] list-website"
>
  <div class="flex justify-center items-center flex-col gap-2">
    <p>You didn’t have any project</p>
    <button
      id="start-new-project"
      class="bg-black rounded-lg text-white px-4 py-2 flex items-center gap-2"
    >
      <span> Start New Project </span>
    </button>
  </div>
</div>

<!-- Container tabel, disiapkan tapi disembunyikan -->
<div id="website-table-container" class="w-full px-4 py-12 hidden">
  <div class="overflow-x-auto">
    <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
      <thead>
        <tr class="bg-gray-100 text-left text-sm font-semibold text-gray-700">
          <th class="py-3 px-4 border-b">#</th>
          <th class="py-3 px-4 border-b">Site Name</th>
          <th class="py-3 px-4 border-b">URL</th>
        </tr>
      </thead>
      <tbody id="website-table-body"></tbody>
    </table>
  </div>
</div>



</div>

<!-- MODAL -->
<div
id="project-modal"
class="fixed inset-0 flex items-center justify-center z-50 hidden"
>
<div
  class="absolute inset-0 bg-black opacity-50"
  id="modal-overlay"
></div>
<div class="bg-white rounded-lg max-w-[763px] w-full relative z-10">
  <div
    class="flex justify-between items-center border-b-1 border-[#EFEFEF] p-6"
  >
    <h2 class="text-primary">Your Website Info!</h2>
    <button id="close-modal" class="p-1">
      <svg
        width="24"
        height="24"
        viewBox="0 0 24 24"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M18 6L6 18M6 6L18 18"
          stroke="#1E1E1E"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        />
      </svg>
    </button>
  </div>
  <form class="flex flex-col gap-4 p-6 website-form">
    <div class="flex flex-col gap-2">
      <label for="organization-name" class="font-medium"
        >Organization Name</label
      >
      <input
        type="text"
        id="organization-name"
        name="organization_name"
        class="border border-gray-300 rounded-lg p-2"
        placeholder="Enter project name"
      />
    </div>
    {{-- <div class="flex flex-col gap-2">
      <label for="site-logo" class="font-medium">Logo</label>
      <div class="flex items-center gap-2">
        <input
          type="text"
          id="site-logo"
          name="site_logo"
          class="border border-gray-300 rounded-lg p-2 w-[70%]"
          placeholder="Value"
        />
        <button
          class="bg-black text-white rounded-lg py-2 w-[30%] flex justify-center items-center"
        >
          <span> Uploard from File </span>
        </button>
      </div>
    </div> --}}
    {{-- <div class="flex flex-col gap-2">
      <label for="email" class="font-medium">Email</label>
      <input
        type="email"
        id="email"
        name="email"
        class="border border-gray-300 rounded-lg p-2"
        placeholder="Email"
      />
    </div> --}}
    {{-- <div class="flex flex-col gap-2">
      <label for="password" class="font-medium">Password</label>
      <input
        type="password"
        id="password"
        name="password"
        class="border border-gray-300 rounded-lg p-2"
        placeholder="Enter your password"
      />
    </div> --}}
    <div class="flex flex-col gap-2">
      <label for="project-description" class="font-medium"
        >Description</label
      >
      <textarea
        id="project-description"
        class="border border-gray-300 rounded-lg p-3"
        rows="3"
        name="description"
        placeholder="Enter project description"
      ></textarea>
    </div>
    <div class="flex flex-col gap-2">
      <label for="site-name" class="font-medium">Site name</label>
      <input
        type="text"
        id="site-name"
        name="site_name"
        class="border border-gray-300 rounded-lg p-2"
        placeholder="Enter your sitename"
      />
    </div>
    <div class="flex flex-col gap-2">
      <label for="site-name" class="font-medium">Site Title</label>
      <input
        type="text"
        id="site-title"
        name="site_title"
        class="border border-gray-300 rounded-lg p-2"
        placeholder="Enter your site ttile"
      />
    </div>
    <div class="flex flex-col gap-2">
      <label for="site-template" class="font-medium">Choose Template</label>
      <select
        id="site-template"
        name="site_template"
        class="border border-gray-300 rounded-lg p-2"
      >
      <option value="">Choose Template</option>
      </select>
    </div>
    <div class="flex justify-between items-center">
      <button
        class="bg-[#E3E3E3] text-black py-1 px-2 rounded-lg mt-2 border border-black"
      >
        Back
      </button>
      <button
        type="submit"
        id="next"
        class="bg-black text-white py-1 px-2 rounded-lg mt-2"
      >
        Next
      </button>
    </div>
  </form>
</div>
</div>
  {{-- @while(have_posts()) @php(the_post())
    @include('partials.page-header')
    @include('partials.content-page')
  @endwhile --}}
@endsection
