<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Reward;
use App\Jobs\SendDiscordMessage;

class RewardController extends Controller
{
    //
  public function index()
  {
    $user = Auth::user();

    $project = Project::where('userId',$user->id)->with('rewards')->first();
    return view('rewards',[
      'user' => $user,
      'project' => $project,
    ]);
  }

  public function create()
  {
    $user = Auth::user();

    $project = Project::where('userId',$user->id)->with('rewards')->first();


    return view('rewards_create',[
      'user' => $user,
      'project' => $project,
    ]);
  }

  public function edit($id)
  {
    $user = Auth::user();

    $project = Project::where('userId',$user->id)->with('rewards')->first();

    $reward = Reward::where('projectId',$project->id)->where('id',$id)->first();

    if(!$reward)
    {
      return redirect()->route('rewards')->with('error','The selected reward could not be found.');
    }

    return view('rewards_edit',[
      'user' => $user,
      'project' => $project,
      'reward' => $reward,
    ]);
  }

  public function delete($id)
  {
    $user = Auth::user();

    $project = Project::where('userId',$user->id)->with('rewards')->first();

    $reward = Reward::where('projectId',$project->id)->where('id',$id)->first();

    if(!$reward)
    {
      return redirect()->route('rewards')->with('error','The selected reward could not be found.');
    }

    if($reward->redemptions()->exists())
    {
      return redirect()->route('rewards')->with('error','The selected reward cannot be deleted because it has already been redeemed.');
    }

    $reward->delete();

    return redirect()->route('rewards');
  }

  public function submit(Request $request)
  {
    $user = Auth::user();

    $request->validate([
      'rewardId' => 'nullable|integer|min:1',
      'name' => 'required|string|min:3|max:100',
      'description' => 'required|string|min:5|max:200',
      'icon' => 'required_without:rewardId|image|mimes:png,gif,jpg,jpeg|max:600',
      'price' => 'required|integer|min:1',
      'inventory' => 'required|integer|min:1',
      'redemptionInfo' => 'nullable|string|max:200'
    ]);

    $project = Project::where('userId',$user->id)->first();

    if($request->has('rewardId'))
    {
      $reward = Reward::where('id',$request->input('rewardId'))->where('projectId',$project->id)->first();

      if(!$reward)
      {
        return redirect()->back()->withInput()->with('error','The selected reward does not exist');
      }
    }
    else
    {
      $reward = new Reward;
    }
    
    if($request->has('icon'))
    {  
      try
      {
        $imageName = time().'.'.$request->icon->extension();
        $request->icon->move(public_path('icons'), $imageName);
      }
      catch(\Exception $e)
      {
        return redirect()->back()->withInput()->withErrors(['icon' => ['There was an error uploading your icon. Error: '.$e->getMessage()]]);
      }

      $reward->icon = $imageName;
    }


    $reward->projectId = $project->id;
    $reward->name = $request->input('name');
    $reward->description = $request->input('description');
    $reward->name = $request->input('name');
    $reward->priceInPoints = $request->input('price');
    $reward->inventory = $request->input('inventory');
    $reward->redemptionInfo = $request->input('information');

    $reward->save();
    
    if($project->discord_channel_id != null && !$request->has('rewardId'))
    {
      $title = ':mega: New Reward Available';
      $message = '__**'.$reward->name."**__\n".$reward->description."\n\n".'**Karma:** '.$reward->priceInPoints." KP \n".'**Available:** '.$reward->inventory." \n\n".'[**Claim this reward**]('.config('app.url').'/claim/'.$reward->id.')'." \n\n";

      SendDiscordMessage::dispatch($project->discord_guild_id,$project->discord_channel_id,$title,$message,$reward->icon);
    }
    return redirect('rewards');
  }

}
