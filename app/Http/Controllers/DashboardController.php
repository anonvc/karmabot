<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Transaction;
use App\Services\DiscordApiService;

class DashboardController extends Controller
{
    //
  public function index(DiscordApiService $discordApi)
  {
    $user = Auth::user();

    $project = Project::where('userId',$user->id)->first();
    $allTransactions = Transaction::where('projectId',$project->id)->get();;
    $totalTransactionsCount = $allTransactions->count();
    $unprocressedTransactionsCount = $allTransactions->where('royalty',null)->count();
    $processedTransactions = Transaction::where('royalty','!=',null)
      ->selectRaw('DATE_FORMAT(blockTime, "%e %b %Y") as day, SUM(price) AS volume, SUM(royalty) AS royalties')
      ->where('projectId',$project->id)
      ->orderBy('day','ASC')
      ->groupBy('day');

    $daysArray = $processedTransactions->pluck('day');
    $volumeArray = $processedTransactions->pluck('volume');
    $royaltiesArray = $processedTransactions->pluck('royalties');
    $ratesArray = collect();

    foreach($daysArray as $key => $day)
    {
      $withRoyaltyCount = Transaction::where('blockTime','>=',\Carbon\Carbon::parse($day))->where('royalty','>',0)->count();
      $withoutRoyaltyCount = Transaction::where('blockTime','>=',\Carbon\Carbon::parse($day))->where('royalty',0)->count();
      if($withoutRoyaltyCount == 0)
      {
        $ratesArray->push(100);
      }
      else
      {
        $ratesArray->push(($withRoyaltyCount/$withoutRoyaltyCount)*100); 
      }
      
    }

    $channels = null;

    if($project->discord_channel_id == null)
    {
      $channels = $discordApi->getChannelsFromGuild($project->discord_guild_id);
    }
    

    return view('dashboard',[
      'user' => $user,
      'project' => $project,
      'allTransactions' => $allTransactions,
      'totalTransactionsCount' => $totalTransactionsCount,
      'unprocressedTransactionsCount' => $unprocressedTransactionsCount,
      'daysArray' => $daysArray->take(100),
      'volumeArray' => $volumeArray->take(100),
      'royaltiesArray' => $royaltiesArray->take(100),
      'ratesArray' => $ratesArray->take(100),
      'channels' => $channels,
      
    ]);
  }
}
