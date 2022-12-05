@extends('layouts.backend')

@section('content')
  <div class="flex items-center justify-center">
    <img class="block h-12 w-auto" src="/logo.png" alt="Karmabot.app">
    <h1 class="text-3xl font-bold text-gray-900">Karmabot.app</h1>
  </div>
<div class="py-12">
  <div class="max-w-3xl mx-auto p-6 lg:p-8 bg-white rounded-lg shadow">
    <h1 class="text-3xl font-bold">Welcome {{ $user->username }}! 🎉</h1>
    <p class="mt-2 text-gray-500">Fill the form below to setup Karmabot on your server, it only takes a minute.</p>

    <form method="post" class="mt-10" action="{{ route('onboarding_submit') }}">
     @csrf

      <div x-data="@if(old('blockchain') == 1) { chain: 'Ethereum'} @else { chain: 'Solana'}  @endif" class="grid grid-cols-2 gap-10">
        <div class="col-span-2 md:col-span-2 space-y-8">
          <h2 class="text-xl font-bold">1. Setup your collection</h2>
          
          <div class="form-group">
            <label for="blockchain" class="block text-sm font-bold text-gray-700">Blockchain</label>

            <div class="mt-2 space-y-4 sm:flex sm:items-center sm:space-y-0 sm:space-x-10">
              <div class="flex items-center">
                <input id="solana" value="2" x-on:click="chain = 'Solana'" name="blockchain" type="radio" class="h-4 w-4 active border-gray-300 text-brand-purple-light focus:ring-brand-purple-light" @if(old('blockchain') == 2) checked @endif required>
                <label for="solana" class="ml-3 block text-sm font-medium text-gray-700">Solana</label>
              </div>
              <div class="flex items-center">
                <input disabled id="ethereum" value="1" x-on:click="chain = 'Ethereum'" name="blockchain" type="radio" class="h-4 w-4 border-gray-300 text-brand-purple-light focus:ring-brand-purple-light" @if(old('blockchain') == 1) checked @endif required>
                <label for="ethereum" class="ml-3 block text-sm font-medium text-gray-700">Ethereum (Coming soon)</label>
              </div>
              @error('blockchain')
                  <div class="mt-1 text-red-500">{{ $message }}</div>
              @enderror

            </div>
          </div>
          <div x-show="chain == 'Ethereum'" class="form-group">
            <label for="contract_address" class="block text-sm font-bold text-gray-700">Contract Address</label>
            <div class="mt-2">
              <input type="text" name="contract_address" id="contract_address" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-purple-light focus:ring-brand-purple-light sm:text-sm" placeholder="0x320493284fjdsf983408324832fjds93483208432" value="{{ old('contract_address','') }}">
            </div>
            @error('contract_address')
                <div class="mt-1 text-red-500">{{ $message }}</div>
            @enderror
          </div>
          <div x-show="chain == 'Solana'" class="form-group">
            <label for="collection_symbol" class="block text-sm font-bold text-gray-700">Magic Eden Symbol</label>
            <div class="mt-2">
              <input type="text" name="collection_symbol" id="collection_symbol" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-purple-light focus:ring-brand-purple-light sm:text-sm" placeholder="y00ts" value="{{ old('collection_symbol','') }}">
            </div>
            @error('collection_symbol')
                <div class="mt-1 text-red-500">{{ $message }}</div>
            @enderror
          </div>
          <div class="form-group">
            <label for="karma_value" class="block text-sm font-bold text-gray-700">Karma to Currency</label>
            <div class="mt-2 flex rounded-md">
              <span class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-50 px-3 text-gray-500 sm:text-sm">1 Karma =</span>
              <input type="number" step="0.001" name="karma_value" id="karma_value" class="w-auto rounded-none border-gray-300 px-3 py-2 focus:border-brand-purple-light focus:ring-brand-purple-light sm:text-sm" value="{{ old('karma_value','0.1') }}">
              <span x-show="chain == 'Ethereum'" class="inline-flex items-center rounded-r-md border border-l-0 border-gray-300 bg-gray-50 px-3 text-gray-500 sm:text-sm">ETH in paid royalties</span>
              <span x-show="chain == 'Solana'" class="inline-flex items-center rounded-r-md border border-l-0 border-gray-300 bg-gray-50 px-3 text-gray-500 sm:text-sm">SOL in paid royalties</span>
            </div>
            <p  class=" mt-1 text-sm leading-5 text-gray-500">Input how much royalties must be paid by a member to earn 1 karma.</p>
            @error('karma_value')
                <div class="mt-1 text-red-500">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="col-span-2 md:col-span-2 space-y-8">
          <h2 class="text-xl font-bold">2. Install KarmaBot</h2>
          <div>
            <a href="/auth/discord/install" type="button" class="hidden inline-flex items-center rounded-md border border-transparent bg-brand-purple-dark px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-brand-purple-dark focus:outline-none focus:ring-2 focus:ring-brand-purple-light focus:ring-offset-2">Install KarmaBot on your server</a>

            <a href="/auth/discord/install" target="_blank" type="button" class="hidden inline-flex items-center rounded-md border border-transparent bg-brand-pink-light px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-brand-pink-dark focus:outline-none focus:ring-2 focus:ring-brand-pink-light focus:ring-offset-2">Install KarmaBot on your server</a>
            <a href="/auth/discord/install" target="_blank" type="button" class="inline-flex items-center rounded-md border border-transparent bg-brand-purple-light px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-brand-purple-dark focus:outline-none focus:ring-2 focus:ring-brand-purple-light focus:ring-offset-2">Install KarmaBot on your server</a>

          </div>
          <br>
          <h2 class="text-xl font-bold">3. Select your discord sever</h2>
            @error('bot_error')
                <div class="mt-1 text-red-500">{{ $message }}</div>
            @enderror
            @error('guild')
                <div class="mt-1 text-red-500">{{ $message }}</div>
            @enderror
          <div>
            <fieldset>
              @if(count($guilds) > 0)
              <div class="mt-4 divide-y divide-gray-200 border-t border-b border-gray-200">
                @foreach($guilds as $guild)
                  @if($guild->owner)
                  <div class="py-4 flex justify-between">
                      <div class="inline-flex items-center">
                        @if($guild->icon != null)
                        <img class="w-8 h-8 rounded-full" src="https://cdn.discordapp.com/icons/{{ $guild->id }}/{{ $guild->icon }}.png" />
                        @else
                        <div class="w-8 h-8 rounded-full bg-gray-100"></div>
                        @endif
                        <label for="side-null" class="ml-3 select-none text-lg font-semibold text-gray-700">{{ $guild->name }}</label>
                      </div>
                      <div class=" h-5 items-center">
                        <input name="guild" value="{{ $guild->id }}" type="radio" class="h-4 w-4 border-gray-300 text-brand-purple-dark focus:ring-brand-purple-light" @if(old('guild') == $guild->id) checked @endif required>
                      </div>
                  </div>
                  @endif
                @endforeach
              </div>
              @else
              <div class="mt-4 ">
                <p class="text-lg text-gray-500 text-center block">No Discord servers with admin permissions found.</p>

                <a href="{{ route('logout') }}" class="block text-center mt-1 font-medium text-brand-purple-light hover:text-brand-purple-dark text-lg" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout?</a>

              </div>
              @endif
            </fieldset>

          
          </div>
        </div>
      </div>

      <br><br><br>
      <div class="flex justify-center">
            <button type="submit" class="inline-flex items-center rounded-md border border-transparent bg-brand-purple-light px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-brand-purple-dark focus:outline-none focus:ring-2 focus:ring-brand-purple-light focus:ring-offset-2">Finish Setup</button>
      </div>
    </form>

    <form method="POST" id="logout-form" action="{{ route('logout') }}">
      @csrf
    </form>
  </div>
</div>
@endsection
