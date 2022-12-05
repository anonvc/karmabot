@extends('layouts.backend')

@section('content')

<div class="md:flex md:justify-between md:items-center">
  <div>
    <h1 class="text-gray-900 text-3xl font-bold">Rewards</h1>
    <p class="mt-2 text-md text-gray-500">Create & customize rewards your community members can claim in exchange for karma</p>
  </div>

  <a href="/rewards/create" type="button" class="mt-2 md:ml-2 inline-flex items-center rounded-md border border-transparent bg-brand-purple-light px-3 py-2 text-sm font-medium leading-4 text-white shadow-sm hover:bg-brand-purple-dark focus:outline-none focus:ring-2 focus:ring-brand-purple-light focus:ring-offset-2">Create a reward</a>
</div>
<div class="w-full mt-6">
  @if(count($project->rewards) == 0)
  <div class="max-w-xl mt-10 mx-auto">
    <p class="text-center text-lg text-gray-900">You have not created any rewards yet.<br> Click <a href="/rewards/create" class="text-brand-purple-light hover:text-brand-purple-dark">here</a> to get started.</p>
  </div>
  @endif
  <div class="grid grid-cols-3 gap-10">
  @foreach($project->rewards as $reward)
  <div class="col-span-3 md:col-span-1 bg-white shadow rounded-lg p-4 ">
    <div class="flex">
      <img class="w-10 h-10 rounded-full" src="/icons/{{ $reward->icon }}" />
      <div class="ml-3 w-full">
        <h3 class="text-xl font-semibold text-gray-900">{{ $reward->name }}</h3>
        <p class="text-gray-500 mt-1">{{ $reward->description }}</p>
        <p class="text-gray-500 mt-1"><span class="font-bold">Inventory:</span> {{ $reward->inventory }}</p>
        <p class="text-gray-500 mt-1"><span class="font-bold">Redemptions:</span> {{ count($reward->redemptions)}}</p>
        <div class="mt-4 flex items-center justify-between">
          <div class="text-gray-900 font-semibold inline-flex items-center"><span>{{ $reward->priceInPoints }}</span> <img src="/logo.png" class="ml-1 w-4 h-4"/></div>
          <div class="inline-flex items-center space-x-2">
            <a href="/rewards/edit/{{ $reward->id }}" class="bg-brand-purple-light/30 border-2 border-brand-purple-dark text-brand-purple-dark hover:text-white hover:bg-brand-purple-dark rounded-lg p-1">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
              </svg>
            </a>
            <a href="/rewards/delete/{{ $reward->id }}" onclick="return confirm('Are you sure you want to delete this reward?');" class="bg-brand-pink-light/30 border-2 border-brand-pink-dark text-brand-pink-dark hover:text-white hover:bg-brand-pink-dark rounded-lg p-1">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
              </svg>

            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  @endforeach



</div>


@endsection