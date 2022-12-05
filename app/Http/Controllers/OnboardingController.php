<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\Http;
use App\Services\DiscordApiService;
use App\Services\MagicEdenApiService;

class OnboardingController extends Controller
{
  
  private $discordApi;
  private $magicEdenApi;

  public function __construct(DiscordApiService $discordApi,MagicEdenApiService $magicEdenApi)
  {
    $this->discordApi = $discordApi;
    $this->magicEdenApi = $magicEdenApi;
  }

  public function index()
  {
    $user = Auth::user();

    try
    {
      $guilds = $this->discordApi->getUserGuilds($user->access_token);
    }
    catch(\Exception $e)
    {
      return 'Error: '.$e->getMessage();
    }

    return view('onboarding',['user' => $user, 'guilds' => $guilds]);
  }

  public function submit(Request $request)
  {
    $user = Auth::user();

    $request->validate([
      'blockchain' => 'required|integer|min:2|max:2',
      'contract_address' => 'nullable|required_if:blockchain,1|string|min:42|max:42',
      'collection_symbol' => 'nullable|required_if:blockchain,2|string|min:2|max:200',
      'karma_value' => 'required|numeric|min:0.0001|max:1000',
      'guild' => 'required|string|min:5|max:100',
    ]);

    $collection_uid = $request->input('contract_address');

    // Verify Collection Exists
    if($request->input('blockchain') == 2)
    {

      $collection_uid = $request->input('collection_symbol');

      try
      {
        $this->magicEdenApi->getCollection($collection_uid);
      }
      catch(\Exception $e)
      {
        return redirect()->back()->withInput()->withErrors(['collection_symbol' => ['Collection not found. Error: '.$e->getMessage()]]);
      }   
    }

    // Get Bot Guilds
    try
    {
      $botGuilds = $this->discordApi->getBotGuilds();
    }
    catch(\Exception $e)
    {
      return redirect()->back()->withInput()->withErrors(['bot_error' => ['There was an error connecting to discord, please try again later. Error: '.$e->getMessage()]]);
    }


    foreach($botGuilds as $guild)
    {
      if($guild->id == $request->input('guild'))
      {

        $project = Project::Create([
          'userId' => $user->id,
          'chainId' => $request->input('blockchain'),
          'name' => $guild->name,
          'image' => $guild->icon,
          'collection_uid' => $collection_uid,
          'discord_guild_id' => $guild->id,
          'karmaValue' => $request->input('karma_value')
        ]);

        return redirect('dashboard');
      }
    }

    return redirect()->back()->withInput()->withErrors(['bot_error' => ['KarmaBot wasn\'t found on the selected server. Please install KarmaBot on your server and try again.']]);
  }

  public function set_channel(Request $request)
  {
    $user = Auth::user();

    $request->validate([
      'channel_id' => 'required|string'
    ]);


    $project = Project::where('userId',$user->id)->first();

    $project->discord_channel_id = $request->input('channel_id');
    $project->save();

    return redirect()->route('dashboard');
  }
}
