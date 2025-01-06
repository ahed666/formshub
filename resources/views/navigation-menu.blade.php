

<div>
    <aside id="logo-sidebar" class="fixed top-0 md:rtl:right-0 md:ltr:left-0 xs:left-0 z-40 w-64 h-screen 
           transition-transform -translate-x-full  md:translate-x-0" aria-label="Sidebar">
        <div class="h-full px-3 py-4 overflow-y-auto bg-[#0f172a]">
           <!-- Logo -->
            <div class=" flex justify-center items-center ">
                <a href="{{ route('dashboard') }}">
                    <!-- <x-application-mark class="block h-16 w-auto" /> -->
                
                    <img src="{{asset('/images/logo/app/main-logo.png')}}" class="block h-16 w-auto" alt="" srcset="">
                    
                </a>
            </div>
        <ul class="space-y-2 mt-10 font-medium text-white">

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



        <div class="flex  items-center space-x-4 xs:relative md:absolute bottom-2 border-t-[1px] p-1">
            <!-- Authentication -->
            <form class="flex hover:bg-gray-50 rounded hover:p-1" method="POST" action="{{ route('logout') }}" >
                                @csrf

                <button title="{{__('navigation.logout')}}" type="submit">
                
                           
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 hover:cursor-pointer text-secondary_blue">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                            </svg>

                </button>
                                
            </form>
            <form action="{{ route('change.language') }}" method="get">
                <select class="border-0 rounded p-[1px] rtl:!pl-8 text-secondary_blue" name="locale" onchange="this.form.submit()">
                    <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>English</option>
                    <option value="ar" {{ app()->getLocale() === 'ar' ? 'selected' : '' }}>Arabic</option>
                </select>
            </form>
            
            
        </div>



        </ul>
        </div>
     </aside>
</div>
