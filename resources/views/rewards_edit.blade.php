@extends('layouts.backend')

@section('content')

<div class="">
  <h1 class="text-gray-900 text-3xl font-bold">Edit a reward</h1>
  <p class="mt-2 text-gray-500">Fill the form below to edit a reward.</p>
</div>
<div class="w-full mt-6">

  <div class="py-6 px-8 bg-white rounded-lg shadow">
    <form method="post" class="space-y-6" action="{{ route('rewards_submit') }}" enctype="multipart/form-data">

      <div class="form-group">
        <label for="name" class="block text-sm font-bold text-gray-700">Reward Name</label>
        <div class="mt-2">
          <input type="text" name="name" id="name" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-purple-light focus:ring-brand-purple-light sm:text-sm" placeholder="Discord Role" value="{{ old('name',$reward->name) }}">
        </div>
        @error('name')
            <div class="mt-1 text-red-500">{{ $message }}</div>
        @enderror
      </div>
      <div class="form-group">
        <label for="description" class="block text-sm font-bold text-gray-700">Short Description</label>
        <div class="mt-2">
          <input type="text" name="description" id="description" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-purple-light focus:ring-brand-purple-light sm:text-sm" placeholder="Get the exclusive supporter role on Discord and unlock new channels!" value="{{ old('description',$reward->description) }}">
        </div>
        @error('description') 
            <div class="mt-1 text-red-500">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <label for="icon" class="block text-sm font-bold text-gray-700">Reward Icon</label>
        <div class="mt-2 flex items-center">
          <img class="w-8 h-8 rounded-full" src="/icons/{{ $reward->icon}}" />
          <input type="file" name="icon" id="icon" class="ml-2 block w-full border-gray-300 shadow-sm focus:border-brand-purple-light focus:ring-brand-purple-light sm:text-sm" />
        </div>
        @error('icon')
            <div class="mt-1 text-red-500">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <label for="price" class="block text-sm font-bold text-gray-700">Price (in Karma Points)</label>
        <div class="mt-2">
          <input type="text" name="price" id="price" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-purple-light focus:ring-brand-purple-light sm:text-sm" placeholder="300" value="{{ old('price', $reward->priceInPoints ) }}">
        </div>
        @error('price')
            <div class="mt-1 text-red-500">{{ $message }}</div>
        @enderror
      </div>
      <div class="form-group">
        <label for="inventory" class="block text-sm font-bold text-gray-700">Available Inventory</label>
        <div class="mt-2">
          <input type="text" name="inventory" id="inventory" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-purple-light focus:ring-brand-purple-light sm:text-sm" placeholder="10" value="{{ old('inventory',$reward->inventory ) }}">
        </div>
        @error('inventory')
            <div class="mt-1 text-red-500">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <label for="information" class="block text-sm font-bold text-gray-700">Request information for reward delivery (optional) </label>
        <div class="mt-2">
          <input type="text" name="information" id="information" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-purple-light focus:ring-brand-purple-light sm:text-sm" placeholder="Full Discord Name (i.e: JACK#2344)" value="{{ old('information', $reward->redemptionInfo) }}">
        </div>
        @error('information')
            <div class="mt-1 text-red-500">{{ $message }}</div>
        @enderror
      </div>


      <input type="hidden" name="rewardId" value="{{ $reward->id }}" />
      @csrf

      <div class="flex justify-center">
            <button type="submit" class="inline-flex items-center rounded-md border border-transparent bg-brand-purple-light px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-brand-purple-dark focus:outline-none focus:ring-2 focus:ring-brand-purple-light focus:ring-offset-2">Edit Reward</button>
      </div>
    </form>
  </div>
</div>


@endsection