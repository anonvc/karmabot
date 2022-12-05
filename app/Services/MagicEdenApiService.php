<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Collection;



class MagicEdenApiService {
    protected string $uri;


    public function __construct()
    {
      $this->uri = config('services.magic-eden.uri');
    }

    public function getTransactions(string $collectionSymbol,$offset = 0)
    {
      $path = 'collections/'.$collectionSymbol.'/activities';

      $params = '?limit=1000&offset='.$offset.'&type=buyNow'; 


      $response = Http::acceptJson()->throw()->get($this->uri.$path.$params);

      
      return $response;
    } 

    public function getCollection($collectionSymbol)
    {
      $path = 'collections/'.$collectionSymbol;

      $response = Http::acceptJson()->throw()->get($this->uri.$path);

      return $response;
    }
}