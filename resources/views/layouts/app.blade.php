<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <style>
        html.ltr {
  direction: ltr;
}

html.rtl {
  direction: rtl;
}
    </style>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            <div class="xs:flex  xs:justify-between md:block items-center md:p-0 xs:p-4">
                <!-- <div class="md:none xs:block">
                    <a href="{{ route('dashboard') }}">
                        <x-application-mark class="block h-16 w-auto" />
                    </a>
                </div> -->
                <div class="md:none xs:flex justify-end">
                    <button data-drawer-target="logo-sidebar" 
                        data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" 
                        type="button" class=" text-sm
                        text-gray-500 rounded-lg
                        lg:hidden hover:bg-gray-100 focus:outline-none
                        focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700
                        dark:focus:ring-gray-600">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                        </svg>
                    </button>
                </div>

                @livewire('navigation-menu')
                </div>

            <!-- Page Heading -->


            <!-- Page Content -->
             <!-- @if(app()->getLocale() === 'ar') md:ml-64 @else md:mr-64 @endif -->
            <div id="app" class="p-4 rtl:md:mr-64 ltr:md:ml-64">

                   <div id="main"></div>

                {{ $slot }}


            </div>
        </div>

        @stack('modals')

        @livewireScripts
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            window.translations = @json(__('app')); // Replace 'messages' with your lang file name
            window.appLocale = "{{ app()->getLocale() }}";

        </script>
    </body>
</html>
