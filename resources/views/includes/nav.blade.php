
<!-- This example requires Tailwind CSS v2.0+ -->
<nav x-data="{ open: false }" class="bg-white shadow">
  <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
    <div class="relative flex items-center justify-between h-16">
      <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
        <!-- Mobile menu button-->
        <button type="button" @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-brand-purple-dark hover:text-white hover:bg-brand-purple-light focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">
          <span class="sr-only">Open main menu</span>
          <!--
            Icon when menu is closed.

            Heroicon name: outline/menu

            Menu open: "hidden", Menu closed: "block"
          -->
          <svg :class="{ 'hidden': open, 'block': !(open) }" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
          <!--
            Icon when menu is open.

            Heroicon name: outline/x

            Menu open: "block", Menu closed: "hidden"
          -->
          <svg :class="{ 'block': open, 'hidden': !(open) }" class=" h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="flex-1 flex items-center justify-center sm:items-stretch sm:justify-start">
        <a href="/" class="flex-shrink-0 flex items-center">
          <img class="h-8 w-auto" src="/logo.png" alt="Karmabot.app">
        </a>
        <div class="hidden sm:w-full sm:flex sm:justify-between sm:ml-6">
          <div class="flex space-x-4">
            <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
            @if(Route::is('dashboard'))
            <a href="/dashboard" class="bg-brand-purple-light text-white px-3 py-2 rounded-md text-sm font-medium" aria-current="page">Dashboard</a>
            @else
            <a href="/dashboard" class="text-gray-900 hover:bg-brand-purple-light hover:text-white px-3 py-2 rounded-md text-sm font-medium" aria-current="page">Dashboard</a>
            @endif
            @if(Route::is('rewards'))
            <a href="/rewards" class="bg-brand-purple-light text-white px-3 py-2 rounded-md text-sm font-medium">Rewards</a>
            @else
            <a href="/rewards" class="text-gray-900 hover:bg-brand-purple-light hover:text-white px-3 py-2 rounded-md text-sm font-medium">Rewards</a>
            @endif

            @if(Route::is('redemptions'))
            <a href="/redemptions" class="bg-brand-purple-light text-white px-3 py-2 rounded-md text-sm font-medium">Redemptions</a>
            @else
            <a href="/redemptions" class="text-gray-900 hover:bg-brand-purple-light hover:text-white px-3 py-2 rounded-md text-sm font-medium">Redemptions</a>
            @endif

          </div>

          <div class="hidden sm:flex sm:items-center sm:ml-6">
              <x-dropdown align="right" width="48">
                  <x-slot name="trigger">
                      <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                          @if(Auth::user()->avatar)
                              <img class="h-8 w-8 rounded-full object-cover mr-2" src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->username }}#{{ Auth::user()->discriminator }}" />
                          @endif


                          <div class="ml-1">
                              <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                              </svg>
                          </div>
                      </button>
                  </x-slot>

                  <x-slot name="content">
                      <!-- Authentication -->
                      <form method="POST" action="{{ route('logout') }}">
                          @csrf

                          <x-dropdown-link :href="route('logout')"
                                           onclick="event.preventDefault();
                                              this.closest('form').submit();">
                              {{ __('Log Out') }}
                          </x-dropdown-link>
                      </form>
                  </x-slot>
              </x-dropdown>
          </div>

        </div>
      </div>
      <div class="hidden absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
        <button type="button" class="bg-gray-800 p-1 rounded-full text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">
          <span class="sr-only">View notifications</span>
          <!-- Heroicon name: outline/bell -->
          <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
          </svg>
        </button>

      </div>


    </div>
  </div>

  <!-- Mobile menu, show/hide based on menu state. -->
  <div class="sm:hidden" id="mobile-menu" x-show="open">
    <div class="px-2 pt-2 pb-3 space-y-1">
      <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
      @if(Route::is('dashboard'))
      <a href="/dashboard" class="bg-brand-purple-light text-white block px-3 py-2 rounded-md text-base font-medium" aria-current="page">Dashboard</a>
      @else
      <a href="/dashboard" class="text-gray-900 hover:bg-brand-purple-light hover:text-white block px-3 py-2 rounded-md text-base font-medium" aria-current="page">Dashboard</a>
      @endif

      @if(Route::is('rewards'))
      <a href="/rewards" class="bg-brand-purple-light text-white block px-3 py-2 rounded-md text-base font-medium" aria-current="page">Rewards</a>
      @else
      <a href="/rewards" class="text-gray-900 hover:bg-brand-purple-light hover:text-white block px-3 py-2 rounded-md text-base font-medium" aria-current="page">Rewards</a>
      @endif

      @if(Route::is('redemptions'))
      <a href="/redemptions" class="bg-brand-purple-light text-white block px-3 py-2 rounded-md text-base font-medium" aria-current="page">Redemptions</a>
      @else
      <a href="/redemptions" class="text-gray-900 hover:bg-brand-purple-light hover:text-white block px-3 py-2 rounded-md text-base font-medium" aria-current="page">Redemptions</a>
      @endif

    </div>
  </div>
</nav>
