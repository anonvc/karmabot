<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\DiscordApiService;

class SendDiscordMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $tries = 2;

    public $backoff = 3;

    protected $guild_id;

    protected $channel_id;

    protected $title;

    protected $message;

    protected $icon;


    public function __construct(string $guild_id, string $channel_id, string $title, string $message, string $icon = null)
    {
        //
      $this->guild_id = $guild_id;
      $this->channel_id = $channel_id;
      $this->title = $title;
      $this->message = $message;
      $this->icon = $icon;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(DiscordApiService $discordApi)
    {

      $discordApi->sendBotMessage($this->channel_id,$this->title,$this->message,$this->icon);
      //usleep(500000);
    }


}
