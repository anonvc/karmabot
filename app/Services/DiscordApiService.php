<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DiscordApiService {
  protected string $uri;

  public function __construct()
  {
    $this->uri = config('services.discord.uri');
  }

  public function getUserGuilds(string $access_token)
  {
    $response = Http::withToken($access_token)->throw()->get($this->uri.'users/@me/guilds');

    return json_decode($response->body());
  }

  public function getBotGuilds()
  {
    $response = Http::withHeaders(['Authorization' => 'Bot '.env('DISCORD_BOT_TOKEN')])->throw()->get($this->uri.'/users/@me/guilds');

    return json_decode($response->body());
  }

  public function sendBotMessage($channel_id, $title,$message, $icon)
  {
    $embed_data = $this->createEmbedData($title,$message,$icon);
    Http::withHeaders(['Authorization' => 'Bot '.env('DISCORD_BOT_TOKEN')])->throw()->post($this->uri.'/channels/'.$channel_id.'/messages',[
      'embeds' => [$embed_data]
    ]);

  }

  public function createEmbedData($title,$message,$icon)
  {
    $logoUrl = 'https://s2.coinmarketcap.com/static/img/coins/64x64/18876.png';
    $iconUrl = $logoUrl;

    if($icon != null)
    {
      $iconUrl = config('app.url').'/icons/'.$icon; 
    }

    $embed_data = [
        'type' => 'article',
        'thumbnail' => [
            'url' => $iconUrl,
        ],
        'title' => $title,
        'description' => $message,
        'color' => 12,
        'footer' => array(
            'icon_url'  => $logoUrl,
            'text'  => 'Powered by karmabot.app',
        ),
    ];

    return $embed_data;
  }

  public function getChannelsFromGuild(string $guild_id)
  {
    $response = Http::withHeaders(['Authorization' => 'Bot '.env('DISCORD_BOT_TOKEN')])->throw()->get($this->uri.'guilds/'.$guild_id.'/channels');

    return json_decode($response->body());
  }
}