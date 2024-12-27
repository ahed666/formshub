

<div>
    <aside id="logo-sidebar" class="fixed top-0 ltr:left-0 rtl:right-0 z-40 w-64 h-screen transition-transform -translate-x-full md:translate-x-0" aria-label="Sidebar">
        <div class="h-full px-3 py-4 overflow-y-auto bg-gray-50 dark:bg-gray-800">
           <!-- Logo -->
            <div class=" flex justify-center items-center">
                <a href="{{ route('dashboard') }}">
                    <x-application-mark class="block h-16 w-auto" />
                </a>
            </div>
           <ul class="space-y-2 mt-10 font-medium">

          <!--  Dashboard route  -->
        <x-nav-link href="{{ route('dashboard') }}"  :active="request()->routeIs('dashboard')">
            <dashboard-svg :dim-class="'w-5 h-5'"></dashboard-svg>
           <span class="flex-1 ms-3 whitespace-nowrap">{{ __('navigation.dashboard') }}</span>
        </x-nav-link>

        <!--  devices route  -->
        <x-nav-link href="{{ route('devices') }}"  :active="request()->routeIs('devices')">
           <device-svg :dim-class="'w-5 h-5'" ></device-svg>
           <span class="flex-1 ms-3 whitespace-nowrap">{{ __('navigation.devices') }}</span>
        </x-nav-link>

        <!-- forms route -->
        <x-nav-link href="{{ route('forms') }}"  :active="request()->routeIs('forms')">
            <form-svg :dim-class="'w-5 h-5'"></form-svg>
           <span class="flex-1 ms-3 whitespace-nowrap">  {{ __('navigation.forms') }}</span>
        </x-nav-link>

         <!-- subscriptions -->
         
         <x-nav-link href="{{ route('subscriptions.index') }}"  :active="request()->routeIs('subscriptions.index')">
            <subscriptions-svg :dim-class="'w-5 h-5'" ></subscriptions-svg>
           <span class="flex-1 ms-3 whitespace-nowrap">  {{ __('navigation.subscriptions') }}</span>
        </x-nav-link>
        

       <!-- profile route  -->
        <x-nav-link href="{{ route('profile.show') }}"  :active="request()->routeIs('profile.show')">
            <profile-svg :dim-class="'w-5 h-5'"></profile-svg>
           <span class="flex-1 ms-3 whitespace-nowrap">  {{__('navigation.profile')}}</span>
        </x-nav-link>



        <div class="flex items-center space-x-1 absolute bottom-2 border-t-[1px] p-1">
            <!-- Authentication -->
            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                <x-nav-link title="{{__('navigation.logout')}}" href="{{ route('logout') }}"
                            @click.prevent="$root.submit();">
                            <svg   class="w-5 h-5 hover:cursor-pointer" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" class="icon">
                            <path d="M868 732h-70.3c-4.8 0-9.3 2.1-12.3 5.8-7 8.5-14.5 16.7-22.4 24.5a353.84 353.84 0 0 1-112.7 75.9A352.8 352.8 0 0 1 512.4 866c-47.9 0-94.3-9.4-137.9-27.8a353.84 353.84 0 0 1-112.7-75.9 353.28 353.28 0 0 1-76-112.5C167.3 606.2 158 559.9 158 512s9.4-94.2 27.8-137.8c17.8-42.1 43.4-80 76-112.5s70.5-58.1 112.7-75.9c43.6-18.4 90-27.8 137.9-27.8 47.9 0 94.3 9.3 137.9 27.8 42.2 17.8 80.1 43.4 112.7 75.9 7.9 7.9 15.3 16.1 22.4 24.5 3 3.7 7.6 5.8 12.3 5.8H868c6.3 0 10.2-7 6.7-12.3C798 160.5 663.8 81.6 511.3 82 271.7 82.6 79.6 277.1 82 516.4 84.4 751.9 276.2 942 512.4 942c152.1 0 285.7-78.8 362.3-197.7 3.4-5.3-.4-12.3-6.7-12.3zm88.9-226.3L815 393.7c-5.3-4.2-13-.4-13 6.3v76H488c-4.4 0-8 3.6-8 8v56c0 4.4 3.6 8 8 8h314v76c0 6.7 7.8 10.5 13 6.3l141.9-112a8 8 0 0 0 0-12.6z"/>
                            </svg>
                </x-nav-link>
                                
            </form>
            <form action="{{ route('change.language') }}" method="get">
                <select class="border-0 p-[1px] rtl:!pl-8" name="locale" onchange="this.form.submit()">
                    <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>English</option>
                    <option value="ar" {{ app()->getLocale() === 'ar' ? 'selected' : '' }}>Arabic</option>
                </select>
            </form>
            
            
        </div>



           </ul>
        </div>
     </aside>
</div>
