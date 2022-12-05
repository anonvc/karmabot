<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Collection;



class AlchemyServiceApi {
    protected string $uri;
    protected string $key;

    public function __construct()
    {
      $this->uri = config('services.alchemy-solana.uri');
      $this->key = config('services.alchemy-solana.key');
    }
    
    public function getTransactionRoyalty(string $transactionSignature)
    {
      $uri = $this->uri.$this->key;

      $apiMethod = "getTransaction";

      $response = Http::withHeaders(['accept' => 'application/json','content-type' => 'application/json',])
        ->withBody(
        '{
          "id":1,
          "jsonrpc":"2.0",
          "method":"'.$apiMethod.'",
          "params": [ "'.$transactionSignature.'",{ "encoding":"jsonParsed" } ]
        }', 
        'application/json'
      )->throw()->post($uri);

      $royalty = 0;
      try
      {
        $royalty_count = count($response['result']['transaction']['message']['instructions']);

        for ($counter = 3; $counter < $royalty_count; $counter++) {
          $royalty = $royalty + $response['result']['transaction']['message']['instructions'][$counter]['parsed']['info']['lamports'];
        }
      }
      catch(\Exception $e)
      {
      } 
      
      return $royalty;
    } 
}