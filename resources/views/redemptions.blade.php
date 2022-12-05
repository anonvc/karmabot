@extends('layouts.backend')

@section('content')

<div class="flex justify-between items-center">
  <div>
    <h1 class="text-gray-900 text-3xl font-bold">Redemptions</h1>
    <p class="mt-2 text-md text-gray-500">Track, manage & deliver redeemed rewards.</p>
  </div>
</div>
<div class="w-full mt-6">
  @if(count($project->redemptions) == 0)
  <div class="max-w-xl mt-10 mx-auto">
    <p class="text-center text-lg text-gray-900">No rewards have been redeemed yet.</p>
  </div>
  @endif
  <div class="grid grid-cols-2 gap-10">
  @foreach($project->redemptions as $redemption)
  <div class="col-span-2 md:col-span-1 bg-white shadow rounded-lg p-4 ">
    <div class="flex">
      <img class="w-10 h-10 rounded-full" src="/icons/{{ $redemption->reward->icon }}" />
      <div class="ml-3 w-full overflow-scroll">
        <h3 class="text-xl font-semibold text-gray-900">{{ $redemption->reward->name }}</h3>
        <p class="text-gray-900 mt-1"><span class="font-semibold">Address:</span> {{ $redemption->wallet->address }}</p>
        @if($redemption->reward->redemptionInfo != null)
        <p class="text-gray-900 mt-1"><span class="font-semibold">{{ $redemption->reward->redemptionInfo }}:</span> {{ $redemption->info }}</p>
        @else
        <p>-</p>
        @endif
        <div class="mt-4 flex items-center justify-between">
          <p class="text-gray-500">{{ $redemption->created_at->diffForHumans() }}</p>
          @if($redemption->delivered == 0)
          <a href="/redemptions/deliver/{{ $redemption->id }}" onclick="return confirm('Are you sure you want to mark this reward as delivered?');" type="button" class="ml-2 inline-flex items-center rounded-md border border-transparent bg-brand-purple-light px-3 py-2 text-sm font-medium leading-4 text-white shadow-sm hover:bg-brand-purple-dark focus:outline-none focus:ring-2 focus:ring-brand-purple-light focus:ring-offset-2">Mark as delivered</a>
          @else
          <div class="inline-flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-brand-pink-dark">
              <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
            </svg>
            <p class="ml-1 font-semibold text-brand-pink-dark">Delivered</p>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  @endforeach



</div>


@endsection