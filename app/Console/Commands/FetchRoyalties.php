<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\Karma;
use App\Services\AlchemyServiceApi;
use App\Services\MagicEdenApiService;
use App\Jobs\SendDiscordMessage;

class FetchRoyalties extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:royalties';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch royalties for a batch of transaction';

    protected $alchemyApi;
    protected $discordApi;

    public function __construct(AlchemyServiceApi $alchemyApi)
    {
      parent::__construct();
      $this->alchemyApi = $alchemyApi;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $transactions = Transaction::where('royalty',null)->limit(10)->with('project')->with('wallet')->get();
        if(count($transactions) == 0)
        {
          return Command::SUCCESS;
        }
        
        $project = $transactions->first()->project;
        
        foreach($transactions as $transaction)
        {
          $royalty = $this->alchemyApi->getTransactionRoyalty($transaction->signature);
          $transaction->royalty= $royalty / 1000000000;
          $transaction->save();

          if($transaction->royalty > 0)
          {

            $karma = Karma::Create([
              'walletId' => $transaction->walletId,
              'projectId' => $transaction->projectId,
              'transactionId' => $transaction->id,
              'points' => $transaction->royalty*$project->karmaValue,
            ]);
            if($project->discord_channel_id != null)
            {      
              $title = 'Karma Alert';
              $message = '**Wallet:** '.$transaction->wallet->address.''."\n".'**Royalty:** '.$transaction->royalty_with_currency."\n".'**Karma Earned:** '.$karma->points.' KP'."\n\n";

              SendDiscordMessage::dispatch($project->discord_guild_id,$project->discord_channel_id,$title,$message);
            }
          }
  
          sleep(1);
        }
          $project->lastRefreshed = \Carbon\Carbon::now();
          $project->save();
        
        return Command::SUCCESS;
    }
}
