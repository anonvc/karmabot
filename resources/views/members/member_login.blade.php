@extends('layouts.backend')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="https://unpkg.com/@solana/web3.js@latest/lib/index.iife.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/base-58@0.0.1/Base58.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tweetnacl-util@0.15.0/nacl-util.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tweetnacl@1.0.1/nacl.min.js"></script>
<script src="https://bundle.run/buffer@6.0.3"></script>

<div class="py-12">
  <div class="max-w-sm mx-auto p-6 lg:p-8 bg-white rounded-lg shadow">
    <div class="flex items-center justify-center">
      <img class="hidden h-16 w-auto shadow rounded-full" src="https://cdn.discordapp.com/icons/{{ $project->discord_guild_id }}/{{ $project->image }}.png" alt="{{ $project->name }}">
      <img class="block h-16 w-auto shadow rounded-full" src="https://i1.sndcdn.com/artworks-1QBreaFKunPD2e6w-WcqJ4A-t500x500.jpg" alt="{{ $project->name }}">
      <h1 class="text-3xl font-bold text-gray-900 ml-3">{{ $project->name }} </h1>
    </div>
    @include('includes.errors')
    <p class="mt-6 text-center text-gray-500">Login to {{ $project->name }}'s Karma dashboard, claim your karma points and unlock exclusive rewards.</p>
    @error('signature')
        <div class="mt-1 text-red-500 text-center">{{ $message }}</div>
    @enderror
    <div class="flex items-center justify-center mt-2">
      <button id="login-button" onclick="phantomLogin()" class="inline-flex items-center bg-brand-purple-light hover:bg-brand-purple-dark text-white px-3 py-2 rounded-lg mt-6">
        <img src="/phantom-icon-purple.png" class="w-8 h-8" />
        <span class="ml-2 font-semibold">Login with Phantom Wallet</span>
      </button>
    </div>
    <div class="public-key" style="display: none"></div>
  </div>
</div>
  
  <div class="flex justify-center">
    <a type="button" href="/" class="inline-flex items-center text-gray-900 mt-4">
      <img src="/logo.png" class="w-6 h-6" />
      <span class="ml-2 font-semibold">Powered by Karmabot.app</span>
    </a>
  </div>
<form id="claimForm" class="hidden" method="post" action="{{ route('member_login_submit',['collection_uid' => $project->collection_uid ]) }}">
  @csrf
  <input type="hidden" name="message" value="{{ $message }}" />
  <input type="hidden" name="wallet" id="wallet" />
  <input type="hidden" name="signedMessage" id="signedMessage" />
</form>

<script>
    async function phantomLogin() {

        const isPhantomInstalled = window.solana && window.solana.isPhantom;
        if (!isPhantomInstalled) {
            alert("Phantom browser extension is not installed!");
        } else {
            try {
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

                document.getElementById('signedMessage').setAttribute('value',signatureBase64);
                document.getElementById('wallet').setAttribute('value', resp.publicKey);
                document.getElementById('claimForm').submit();
            } catch (err) {
              console.log(err);
              alert(err.message);
            }
        }
    }


</script>
@endsection