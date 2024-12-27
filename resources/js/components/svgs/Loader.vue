
<template>

<div  class="fixed inset-0 flex justify-center items-center z-50 bg-gray-800 bg-opacity-50">
        <svg class="animate-spin h-10 w-10 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor"
            d="M4 12a8 8 0 018-8V2.83a1 1 0 00-1.57-.82l-4.25 3.4a1 1 0 01-1.24 0l-4.25-3.4A1 1 0 004 2.83V4a8 8 0 018 8zm2 5.19a1 1 0 01-.45.85 7.97 7.97 0 01-4.31-2.3l4.25-3.4a1 1 0 011.24 0l4.25 3.4a7.97 7.97 0 01-4.31 2.3 1 1 0 01-.42-.1z">
          </path>
        </svg>
      </div>


</template>
    <script>
      export default {
        name: 'Loader',
        props: {

        }
      }
    </script>
