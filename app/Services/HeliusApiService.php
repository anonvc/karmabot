<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class HeliusApiService {
  protected string $uri;
  protected string $key;

  public function __construct()
  {
    $this->uri = config('services.helius.uri');
    $this->key = config('services.helius.key');
  }

  public function getTransactions(string $programAddress)
  {
    echo $this->uri.'addresses/'.$programAddress.'/nft-events?api-key='.$this->key.'&type=NFT_SALE&until=';
    $response = Http::throw()->get($this->uri.'addresses/'.$programAddress.'/nft-events?api-key='.$this->key.'&type=NFT_SALE&until=');

    return json_decode($response->body());
  }

}