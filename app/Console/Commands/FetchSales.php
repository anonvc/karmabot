<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\AlchemyServiceApi;
use App\Services\MagicEdenApiService;

class FetchSales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:sales';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $magicEdenApi;
    protected $alchemyApi;

    public function __construct(MagicEdenApiService $magicEdenApi,AlchemyServiceApi $alchemyApi)
    {
      parent::__construct();
      $this->magicEdenApi = $magicEdenApi;
      $this->alchemyApi = $alchemyApi;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $projects = Project::orderBy('lastRefreshed','ASC')->limit(10)->get();

        foreach($projects as $project)
        {

          try
          {
            $response = $this->magicEdenApi->getTransactions($project->collection_uid);
          }
          catch(\Exception $e)
          {
            \Log::error('FetchSales Error: Collection #'.$project->id.' - '.$e->getMessage());
            continue;
          }
          
          $transactions = $response->json();

          foreach($transactions as $transaction)
          {
            $buyer = Wallet::FirstOrCreate(
              ['address' => $transaction['buyer']],
              ['chainId' => $project->chainId]
            );

            $tx = Transaction::FirstOrCreate(
              [
                'signature' => $transaction['signature']
              ],
              [ 
                'projectId' => $project->id,
                'chainId' => $project->chainId,
                'walletId' => $buyer->id,
                'price' => $transaction['price'],
                'blockTime' => \Carbon\Carbon::parse($transaction['blockTime']),
              ]
            );
          }



        }
        return Command::SUCCESS;
    }
}
