<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Discord\Builders\MessageBuilder;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Interactions\Interaction;
use Discord\Discord;
use Discord\Parts\Interactions\Command\Command  as DiscordCommand; // Please note to use this correct namespace!
use App\Services\DiscordApiService;
use App\Models\Project;
use App\Models\Karma;
use App\Models\Transaction;
use App\Models\Reward;
use App\Models\Wallet;



class DiscordListener extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'discord:listen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listen & respond to bot commands from Discord';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    { 
        $discordApiService = new DiscordApiService();

        $discord = new Discord([
            'token' => config('services.discord.token'),
        ]);


        $discord->on('ready', function (Discord $discord) use ($discordApiService) {

            $discord->listenCommand('rewards', function (Interaction $interaction) use ($discord,$discordApiService) {
                  $interaction->acknowledgeWithResponse();
                  $project = Project::where('discord_guild_id', $interaction->guild_id)->first();

                  $rewards = Reward::where('projectId',$project->id)->get();

                  $message = '';
                  if(count($rewards) == 0)
                  {
                    $message = 'No rewards available at the moment.';
                  }
                  else
                  {
                    foreach($rewards as $reward)
                    {
                  
                      $message = $message.'__**'.$reward->name."**__\n".$reward->description."\n\n".'**Karma:** '.$reward->priceInPoints." KP \n".'**Available:** '.$reward->inventory." \n\n".'[**Claim this reward**]('.config('app.url').'/'.$project->collection_uid.')'." \n\n---------------------------\n\n";
                    }
                  }
                  $title = 'Rewards';

                  $embed_data = $discordApiService->createEmbedData($title, $message,null);
                  $embed = new Embed($discord, $embed_data);
                  $interaction->updateOriginalResponse(MessageBuilder::new()->addEmbed($embed));
            });

            $discord->listenCommand('airdrop', function (Interaction $interaction) use ($discord,$discordApiService) {
                  $interaction->acknowledgeWithResponse();
                  $wallet_address = $interaction->data->options['wallet']->value;
                  $karma_airdrop = $interaction->data->options['karma']->value;

                  $project = Project::where('discord_guild_id', $interaction->guild_id)->first();
                  $wallet = Wallet::where('address', $wallet_address)->first();

                  $title = 'Airdrop Error';
                  $message = 'The wallet address couldn\'t be found in this server.';
                  if($wallet)
                  {
                    $title = 'Karma Airdrop :tada:';
                    $message = '**Wallet:** '.$wallet->address."\n".'**Karma Airdropped:** '.$karma_airdrop.' KP';
                  }
                  $embed_data = $discordApiService->createEmbedData($title, $message, null);
                  $embed = new Embed($discord, $embed_data);
                  $interaction->updateOriginalResponse(MessageBuilder::new()->addEmbed($embed));
                  Karma::Create([
                    'walletId' => $wallet->id,
                    'projectId' => $project->id,
                    'points' => $karma_airdrop
                  ]);
            });

            $discord->listenCommand('karma', function (Interaction $interaction) use ($discord,$discordApiService) {
                  $interaction->acknowledgeWithResponse();
                  $wallet_address = $interaction->data->options['wallet']->value;

                  $project = Project::where('discord_guild_id', $interaction->guild_id)->first();
                  $wallet = Wallet::where('address', $wallet_address)->first();

                  $title = 'Karma Error';
                  $message = 'The wallet address couldn\'t be found in this server.';
                  if($wallet)
                  {
                    $title = 'Karma Points';
                    $message = '**Wallet:** '.$wallet->address."\n".'**Karma:** '.$wallet->karma_points.' KP';
                  }
                  $embed_data = $discordApiService->createEmbedData($title, $message, null);
                  $embed = new Embed($discord, $embed_data);
                  $interaction->updateOriginalResponse(MessageBuilder::new()->addEmbed($embed));
            });

            $discord->listenCommand('leaderboard', function (Interaction $interaction) use ($discord,$discordApiService) {
                $interaction->acknowledgeWithResponse();
                $guild_id = $interaction->guild_id;

                $leaderboard = Karma::whereHas('project', function ($query) use ($guild_id){
                  $query->where('discord_guild_id',  $guild_id);
                })->selectRaw('walletId, sum(points) as points')->with('wallet')->with('wallet.transactions')->groupBy('walletId')
                ->orderBy('points','DESC')->limit(10)
                ->get();

                $message = '';
                foreach($leaderboard as $key => $item)
                {
                  if($key == 0)
                  {
                    $row = ':first_place: **'.$item->wallet->address."** \n";
                  }
                  elseif($key == 1)
                  {
                    $row = ':second_place: **'.$item->wallet->address."** \n";
                  }
                  elseif($key == 2)
                  {
                    $row = ':third_place: **'.$item->wallet->address."** \n";
                  }
                  else
                  {
                    $row = '**'.$item->wallet->address."** \n";
                  }

                  $details = '**Royalties:** '.$item->wallet->transactions->sum('royalty').' '.$item->wallet->transactions->first()->currency.' - **Karma:** '.$item->points." KP \n\n";
                  
                  $message = $message.$row.$details;
                }

                $title = 'Top 10 Leaderboard';
                $embed_data = $discordApiService->createEmbedData($title, $message, null);
                $embed = new Embed($discord, $embed_data);
                $interaction->updateOriginalResponse(MessageBuilder::new()->addEmbed($embed));
            });
        });


        try
        {
          $discord->run();
        }
        catch(\Exception $e)
        {
          \Log::error($e->getMessage());
        }
        
        return Command::SUCCESS;
    }
}
