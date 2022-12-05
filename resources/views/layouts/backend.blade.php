<!DOCTYPE html>
<html class="h-full" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Karmabot.app</title>
      <meta name="title" content="Karmabot.app">
      <meta name="description" content="The end-to-end platform to create & launch generative NFT collectibles without code.">

      <script src="//unpkg.com/alpinejs" defer></script>
      @vite(['resources/css/app.css', 'resources/js/app.js'])
      <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
      <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
      <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
      <link rel="manifest" href="/site.webmanifest">
      <meta name="msapplication-TileColor" content="#da532c">
      <meta name="theme-color" content="#ffffff">
  </head>
  <body class="h-full bg-gray-50 text-gray-900">
      @if(Auth::check())
        @if(!Route::is('onboarding') && !Route::is('member_*') )
          @include('includes.nav')
        @endif
      @endif

      <div class="max-w-7xl mx-auto py-10 px-6">
        @include('includes.errors')
        @yield('content')
      </div>




    
  </body>
</html>
