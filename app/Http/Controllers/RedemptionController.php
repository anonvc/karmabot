<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Reward;
use App\Models\Redemption;

class RedemptionController extends Controller
{
    //
  public function index()
  {
    $user = Auth::user();

    $project = Project::where('userId',$user->id)->with('redemptions')->first();
    
    return view('redemptions',[
      'user' => $user,
      'project' => $project,
    ]);
  }
  public function deliver($id)
  {
    $user = Auth::user();

    $project = Project::where('userId',$user->id)->with('redemptions')->first();
    
    $redemption = Redemption::where('id',$id)->where('projectId',$project->id)->first();

    if(!$redemption)
    {
      return redirect()->route('redemptions')->with('error','The selected redemption could not be found.');
    }

    $redemption->delivered = 1;
    $redemption->save();

    return redirect()->route('redemptions');
  }
}
