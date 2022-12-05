@extends('layouts.backend')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="https://unpkg.com/@solana/web3.js@latest/lib/index.iife.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/base-58@0.0.1/Base58.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tweetnacl-util@0.15.0/nacl-util.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tweetnacl@1.0.1/nacl.min.js"></script>
<script src="https://bundle.run/buffer@6.0.3"></script>

<div class="flex items-center justify-center">
  <img class="hidden h-16 w-auto shadow rounded-full" src="https://cdn.discordapp.com/icons/{{ $project->discord_guild_id }}/{{ $project->image }}.png" alt="{{ $project->name }}">
  <img class="block h-16 w-auto shadow rounded-full" src="https://i1.sndcdn.com/artworks-1QBreaFKunPD2e6w-WcqJ4A-t500x500.jpg" alt="{{ $project->name }}">
  <h1 class="text-3xl font-bold text-gray-900 ml-3">{{ $project->name }} </h1> 
</div>
<div class="flex justify-center mt-4">
  <span class="border-2 border-brand-purple-light text-brand-purple-light font-semibold px-4 py-2 text-xs rounded-lg">Logged in as {{ $wallet->short_address }}</span>
</div>
<div class="max-w-lg mx-auto mt-6 space-y-6">
  @include('includes.errors')
  @error('claim_error')
      <div class="mt-1 text-red-500 text-center">{{ $message }}</div>
  @enderror
  @if (Session::has('success'))
    <div class="mt-1 text-brand-purple-light text-center">{{ Session::get('success') }}</div>
  @endif
  <div class="mt-2">
    <div class="grid grid-cols-2 gap-6">
      <div class="p-6 lg:p-8 bg-white rounded-lg shadow col-span-1">
        <div class="inline-flex">
          <h4 class="text-4xl font-extrabold">{{ $wallet->karma_points }}</h4>
          <img src="/logo.png" class="ml-2 w-6 h-6" />
        </div>
        <p class="text-gray-500 text-normal">Karma Points</p>
      </div>
      <div class="p-6 lg:p-8 bg-white rounded-lg shadow col-span-1">
        <div class="inline-flex">
          <h4 class="text-4xl font-extrabold">{{ $royalties }}</h4>
          <span class="ml-2 font-bold text-brand-purple-light text-xl">{{ $currency }}</span>
         </div>
        <p class="text-gray-500 text-normal">Royalties Paid</p>
      </div>
    </div>



    <div class="public-key" style="display: none"></div>
  </div>
  <div class="p-6 lg:p-8 bg-white rounded-lg shadow ">
    <h1 class="text-2xl font-bold">🎁 <span class="ml-1">Rewards</span></h1>
    <p class="mt-2 text-sm text-gray-500">Use your karma points to claim available rewards.</p>
    <div class="mt-6 space-y-6">
      @foreach($project->rewards as $reward)
        <a  onclick='return claimReward("{{ addslashes($reward->redemptionInfo) ?: 0}}",{{ $reward->id }}, {{ $reward->priceInPoints }})' class="cursor-pointer flex transition ease-in-out delay-150 duration-300 transform hover:scale-105">
          <img src="/icons/{{ $reward->icon }}" class="w-20 h-20" />
          <div class="w-full ml-2">
            <div class="w-full flex items-center justify-between">
              <h2 class="text-lg font-semibold">{{ $reward->name}}</h2>
              <div class="inline-flex items-center space-x-2">
                <p class="bg-brand-pink-dark inline-flex items-center font-medium text-white px-2 text-sm rounded-lg">
                  {{ $reward->inventory }} available
                </p>
                <p class="bg-brand-purple-light inline-flex items-center px-2 rounded-lg">
                  <span class="text-white text-sm font-medium">{{ $reward->priceInPoints }}</span>
                  <img src="/logo.png" class="ml-1 w-4 h-4" />
                </p>
              </div>
            </div>
            <p class="text-gray-500 text-sm">{{ $reward->description }}</p>
          </div>
        </a>
      @endforeach
      @if(count($project->rewards) == 0)
        <p class="text-center mt-2 text-gray-500">No rewards available at the moment, please check again later</p>
      @endif
    </div>
  </div>

  @if(count($redemptions) > 0)
  <div class="p-6 lg:p-8 bg-white rounded-lg shadow ">
    <h1 class="text-2xl font-bold">🎉 <span class="ml-1">Claimed</span></h1>
    <p class="mt-2 text-sm text-gray-500">Your previously claimed rewards.</p>
    <div class="mt-6 space-y-6">
      @foreach($redemptions as $redemption)
        <div class="flex">
          <img src="/icons/{{ $redemption->reward->icon }}" class="w-20 h-20" />
          <div class="w-full ml-2">
            <div class="w-full flex items-center justify-between">
              <h2 class="text-lg font-semibold">{{ $redemption->reward->name}}</h2>
              <div class="inline-flex items-center space-x-2">
                @if($redemption->delivered == 0)
                  <p class="bg-brand-yellow inline-flex items-center font-medium text-gray-900 px-2 text-sm rounded-lg">
                    Pending
                  </p>
                @else
                  <p class="bg-brand-pink-dark inline-flex items-center font-medium text-white px-2 text-sm rounded-lg">
                    Delivered
                  </p>
                @endif
                <p class="bg-brand-purple-light inline-flex items-center px-2 rounded-lg">
                  <span class="text-white text-sm font-medium">{{ $redemption->priceInPoints }}</span>
                  <img src="/logo.png" class="ml-1 w-4 h-4" />
                </p>
              </div>
            </div>
            <p class="text-gray-500 text-sm">{{ $redemption->created_at->diffForHumans() }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>
  @endif
</div>

<div class="flex justify-center mt-10">
  <a type="button" href="/" class="inline-flex items-center text-gray-900 mt-4">
    <img src="/logo.png" class="w-6 h-6" />
    <span class="ml-2 font-semibold">Powered by Karmabot.app</span>
  </a>
</div>
<form id="claimForm" class="hidden" method="post" action="{{ route('member_claim',['collection_uid' => $project->collection_uid ]) }}">
  @csrf
  <input type="hidden" name="message" value="{{ $message }}" />
  <input type="hidden" name="wallet" id="wallet" />
  <input type="hidden" name="signedMessage" id="signedMessage" />
  <input type="hidden" name="informationInput" id="informationInput" />
  <input type="hidden" name="rewardId" id="rewardId" />
</form>

<script>
    async function claimReward(informationRequest,rewardId,rewardPrice) {
        console.log('0');
        let confirmation = confirm('Claim this reward for '+rewardPrice+' Karma points?');
        console.log('1');
        if(!confirmation)
        {
          return;
        }
        console.log('2');

        const isPhantomInstalled = window.solana && window.solana.isPhantom;
        console.log('3');
        if (!isPhantomInstalled) {
            alert("Phantom browser extension is not installed!");
        } else {
        console.log('4');
            let informationInput = null;
            if(informationRequest != 0)
            {
        console.log('5');
              informationInput = prompt("Please enter the following : " + informationRequest);

              if(!informationInput)
              {
                alert('Please fill in the required information to claim this reward.');
                return;
              }
            }
        console.log('6');

            try {
        console.log('7');
                const resp = await window.solana.connect();
                console.log('Account: ' + resp.publicKey.toString());
                const encodedMessage = new TextEncoder().encode('{{ $message }}');
                const signedMessage = await window.solana.signMessage(encodedMessage, "utf8");
                console.log((signedMessage.signature));
                document.getElementById('wallet').setAttribute('value', resp.publicKey);
                document.getElementById('signedMessage').setAttribute('value',signedMessage.signature);

                const signatureBase64 = buffer.Buffer.from(signedMessage.signature).toString('base64');

                const signatureUint8 = new Uint8Array(atob(signatureBase64).split('').map(c => c.charCodeAt(0)))

                let decrypted = nacl.sign.detached.verify(
                  encodedMessage,
                  signatureUint8,
                  resp.publicKey.toBytes());
                console.log(decrypted);

                document.getElementById('informationInput').setAttribute('value',informationInput);
                document.getElementById('signedMessage').setAttribute('value',signatureBase64);
                document.getElementById('wallet').setAttribute('value', resp.publicKey);
                document.getElementById('rewardId').setAttribute('value', rewardId);
                document.getElementById('claimForm').submit();
            } catch (err) {
              console.log(err);
              alert(err.message);
              return;
            }
        }
    }


</script>
@endsection