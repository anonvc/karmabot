@extends('layouts.backend')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>  
<style>
  .selectedTimeframe {
    border-width: 2px;
    border-color: rgb(101, 163, 13);
    border-radius: 0.5rem;
    padding: 0.25rem;
  }
</style>

<div class="">
  <h1 class="text-gray-900 text-3xl font-bold">Hello {{ ucfirst($user->username ) }}!</h1>
  <p class="mt-2 text-md text-gray-500">Welcome to {{ ucfirst($project->name) }}'s Dashboard</p>
</div>
<div class="w-full grid grid-cols-3 gap-10 mt-6">

  <div class="col-span-3 lg:col-span-2">
    <div class=" grid grid-cols-3 gap-6 md:gap-10">
      <div class="overflow-hidden rounded-lg bg-white h-auto shadow col-span-3 md:col-span-1 h-20">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="truncate text-sm font-medium text-gray-500">Daily Revenue</dt>
                <dd>
                  <p class="text-lg font-medium text-gray-900">{{ $allTransactions->where('blockTime','>',\Carbon\Carbon::yesterday()->endOfDay())->sum('royalty') }} {{ $project->chain->currency }}</p>
                </dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
      <div class="overflow-hidden rounded-lg bg-white h-auto shadow col-span-3 md:col-span-1 h-20">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185zM9.75 9h.008v.008H9.75V9zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm4.125 4.5h.008v.008h-.008V13.5zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
              </svg>


            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="truncate text-sm font-medium text-gray-500">Daily Fulfillement Rate</dt>
                <dd>
                  @if($allTransactions->where('blockTime','>',\Carbon\Carbon::yesterday()->endOfDay())->where('royalty',0)->count() > 0) 
                  <p class="text-lg font-medium text-gray-900"> {{ round(
                    ($allTransactions->where('blockTime','>',\Carbon\Carbon::yesterday()->endOfDay())->where('royalty','>',0)->count()/$allTransactions->where('blockTime','>',\Carbon\Carbon::yesterday()->endOfDay())->where('royalty',0)->count())
                  *100,2) }}%</p>
                  @else
                    @if($allTransactions->where('blockTime','>',\Carbon\Carbon::yesterday()->endOfDay())->where('royalty','>',0)->count() > 0)
                   <p class="text-lg font-medium text-gray-900"> 100%</p>
                   @else
                   <p class="text-lg font-medium text-gray-900"> 0%</p>
                   @endif
                  @endif
                </dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
      <div class="overflow-hidden rounded-lg bg-white h-auto shadow col-span-3 md:col-span-1 h-20">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
              </svg>

            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="truncate text-sm font-medium text-gray-500">Daily Volume</dt>
                <dd>
                  <p class="text-lg font-medium text-gray-900">{{ $allTransactions->where('blockTime','>',\Carbon\Carbon::yesterday()->endOfDay())->sum('price') }} {{ $project->chain->currency }}</p>
                </dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="overflow-hidden rounded-lg bg-white shadow p-6 mt-10">
      <div class="flex justify-between">
        <div>
          <h2 class="text-xl font-medium text-gray-900" id="announcements-title">Overview</h2>
         
          <p class="mt-1 text-sm text-gray-500">Last updated: @if($project->lastRefreshed != null){{ $project->lastRefreshed }}@else Processing...@endif  </p>
        </div>
        <div id="timeframesContainer" class="md:mt-4 inline-flex items-start md:items-center space-x-2">
          @if(count($daysArray) > 6)
          <button onclick="updateData(7,this)" class="timeframe text-gray-500 font-semibold text-lg selectedTimeframe">7D</button>
          @else
          <button class="cursor-default timeframe text-gray-300 font-semibold text-lg">7D</button>
          @endif
          @if(count($daysArray) > 13)
          <button onclick="updateData(14,this)" class="timeframe text-gray-500 font-semibold text-lg">14D</button>
          @else
          <button class="cursor-default timeframe text-gray-300 font-semibold text-lg">14D</button>
          @endif
          @if(count($daysArray) > 29)
          <button onclick="updateData(30,this)" class="timeframe text-gray-500 font-semibold text-lg">30D</button>
          @else
          <button class="cursor-default timeframe text-gray-300 font-semibold text-lg">30D</button>
          @endif
          @if(count($daysArray) > 59)
          <button onclick="updateData(60,this)" class="timeframe text-gray-500 font-semibold text-lg">60D</button>
          @else
          <button class="cursor-default timeframe text-gray-300 font-semibold text-lg">60D</button>
          @endif
        </div>
      </div>
      <div id="chartContainer" class="container mt-6 overflow-x-scroll">
        <div class="containerBody">
          <canvas id="chartBar"></canvas>
        </div>
      </div>
    </div>

  </div>

  @if(count($project->redemptions) == 0)
    <div class="col-span-3 lg:col-span-1">
      <div class="overflow-hidden rounded-lg bg-white shadow p-6">
        <h2 class="text-xl font-medium text-gray-900" id="announcements-title">Steps for Success </h2>
        <div class="mt-6 flow-root">
          <ul role="list" class="-my-5 divide-y divide-gray-200">
            
              <li class="py-5">
                <div class="inline-flex items-center">
                  <h4 class="text-lg font-semibold">1. Karmabot Channel</h4>
                </div>
                <p class="mt-1 text-gray-500">Choose a dedicated channel for Karmabot notifications</p>
                <div class="inline-flex items-center w-full mt-2">
                @if($project->discord_channel_id == null)
                  <form method="post" class="space-y-6" action="{{ route('onboarding_set_channel') }}">
                      @csrf
                      <select id="channel_id" name="channel_id" class="w-auto truncate rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                        @foreach($channels as $channel)
                          @if($channel->type == 0)
                            <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                          @endif
                        @endforeach

                      </select>
                      <button type="submit" class="ml-2 inline-flex items-center rounded-md border border-transparent bg-brand-purple-light px-3 py-2 text-sm font-medium leading-4 text-white shadow-sm hover:bg-brand-purple-dark focus:outline-none focus:ring-2 focus:ring-brand-purple-light focus:ring-offset-2">Submit</button>
                  </form>
                @else
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-brand-pink-dark">
                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                  </svg>
                  <p class="ml-1 font-semibold text-brand-pink-dark">Completed</p>

                @endif
                </div>
              </li>
            
              <li class="py-5">
                <div class="inline-flex items-center">
                  <h4 class="text-lg font-semibold">2. Setup Rewards</h4>
                </div>
                <p class="mt-1 text-gray-500">Create & customize rewards for your community</p>
                <div class="inline-flex items-center w-full mt-2">
                  @if(count($project->rewards) == 0)
                  <a href="/rewards" type="button" class="inline-flex items-center rounded-md border border-transparent bg-brand-purple-light px-3 py-2 text-sm font-medium leading-4 text-white shadow-sm hover:bg-brand-purple-dark focus:outline-none focus:ring-2 focus:ring-brand-purple-light focus:ring-offset-2">Edit Rewards</a>
                  @else
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-brand-pink-dark">
                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                  </svg>
                  <p class="ml-1 font-semibold text-brand-pink-dark">{{ count($project->rewards) }} Rewards created</p>

                  @endif
                </div>
              </li>
            
              <li class="py-5">
                <div class="inline-flex items-center">
                  <h4 class="text-lg font-semibold">3. Launch 🚀</h4>
                </div>
                <p class="mt-1 text-gray-500">Share your karmabot integration with your community</p>
                <div class="inline-flex items-center w-full mt-2">
                  <button type="button" class="inline-flex items-center rounded-md border border-transparent bg-brand-purple-light px-3 py-2 text-sm font-medium leading-4 text-white shadow-sm hover:bg-brand-purple-dark focus:outline-none focus:ring-2 focus:ring-brand-purple-light focus:ring-offset-2">Share on Twitter</button>
                </div>
              </li>
            
          </ul>
        </div>
      </div>
    </div>
  @else
    <div class="col-span-3 md:col-span-1">
      <div class="overflow-hidden rounded-lg bg-white shadow p-6">
        <h2 class="text-xl font-medium text-gray-900" id="announcements-title">Pending Redemptions </h2>
        <div class="mt-6 flow-root">
          @if(count($project->redemptions->where('delivered',0)) > 0)
          <ul role="list" class="-my-5 divide-y divide-gray-200">
            @foreach($project->redemptions->where('delivered',0)->sortByDesc('created_at') as $redemption)
              <li class="py-5">
                <a href="/redemptions" class="flex items-center transition ease-in-out delay-150 duration-300 transform hover:scale-105">
                  <img src="/icons/{{ $redemption->reward->icon }}" class="w-10 h-10" />
                  <div class="ml-2">
                    <h4 class="text-lg font-semibold">{{ $redemption->reward->name }}</h4>
                    <p class="text-gray-500">{{ $redemption->created_at->diffForHumans() }}</p>
                  </div>
                </a>
              </li>
            @endforeach
          </ul>
          @else
          <p class="text-center text-gray-500 text-lg">No pending redemptions</p>
          @endif
        </div>
      </div>
    </div>
  @endif
<script>
  let labelsBarChart = {!! $daysArray !!};
  let volumeData = {!! $volumeArray !!};
  let royaltyData = {!! $royaltiesArray !!};
  let ratesData = {!! $ratesArray !!};
  const dataBarChart = {
    labels: labelsBarChart,
    datasets: [
      {
        type: 'bar',
        label: "Volume",
        backgroundColor: "rgb(93, 19, 124,0.5)",
        borderWidth: 1,
        borderColor: "rgb(93, 19, 124)",
        data: volumeData,
        minBarLength: 4,
        order: 1,
        yAxisID: 'volume-axis',
      },
      {
        type: 'bar',
        label: "Royalties",
        backgroundColor: "rgb(255, 140, 200,0.5)",
        borderWidth: 1,
        borderColor: "rgb(255, 140, 200)",
        minBarLength: 4,
        data: royaltyData,
        order: 2,
        yAxisID: 'royalty-axis',
      },
      {
        type: 'bar',
        label: "Fulfillement Rate (%)",
        backgroundColor: "rgb(255, 207, 75,0.5)",
        borderWidth: 1,
        borderColor: 'rgb(255, 207, 75)',
        minBarLength: 4,
        data: ratesData,
        order: 0,
        yAxisID: 'rate-axis',
      },
    ],
  };

  const configBarChart = {
    type: "bar",
    data: dataBarChart,
    maintainAspectRatio: false,
    options: { 
      interaction: {
        intersect: false,
        mode: 'index',
      },
      scales: {
        'x': {
          ticks: {
            display: false,
          },
          grid: {
            display: false,
          },
        },
        'royalty-axis': {
            type: 'linear',
            display: true,
            position: 'right',
            grid: {
              display: false,
            },
            border: {
              color: '#fcfef6',

            },
            ticks: {
              display: false,
            }
        },
        'rate-axis': {
            type: 'linear',
          beginAtZero: true,
            display: true,
            position: 'right',
            grid: {
              display: false,
            },
            border: {
              color: '#fcfef6',
            },
            ticks: {
              display: false,
            }
        },
        'volume-axis': {
          type: 'linear',
          beginAtZero: true,
          display: true,
          position: 'left',
          grid: {
            display: false,
          },
          border: {
              color: '#fcfef6',

          },
          ticks: {
            display: false,
          }
        }
      }
    },
  };

  var chartBar = new Chart(
    document.getElementById("chartBar"),
    configBarChart
  );

  function updateData(days,event)
  {
    chartBar.data.datasets[0].data = volumeData.slice(0,days);
    chartBar.data.datasets[1].data = royaltyData.slice(0,days);
    chartBar.data.datasets[2].data = ratesData.slice(0,days);
    chartBar.data.labels = labelsBarChart.slice(0,days);

    chartBar.update();

    document.querySelectorAll(".timeframe").forEach(tmf => {
      tmf.classList.remove('selectedTimeframe');
    });

    event.classList.add('selectedTimeframe');
  }
</script>
@endsection