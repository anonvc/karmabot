<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Transaction;
use App\Models\Karma;
use App\Models\Wallet;
use App\Models\Reward;
use App\Models\Redemption;
use Session;


class MemberController extends Controller
{
    // TBD : HEAVY REFACTORING

  public function login($collection_uid)
  {

    $project = Project::where('collection_uid',$collection_uid)->first();

    if(!$project)
    {
      return "Project not found.";
    }

    return view('members.member_login',['project' => $project, 'message' => 'Authenticate']);
  }


  public function login_submit($collection_uid, Request $request)
  {

    $project = Project::where('collection_uid',$collection_uid)->with('rewards')->first();

    if(!$project)
    {
      return "Project not found.";
    }

    $base58 = new \StephenHill\Base58();

    $request->validate([
      'message' => 'required|string',
      'wallet' => 'required|string',
      'signedMessage' => 'required|string',
    ]);

    $address = $request->input('wallet');
    $signingWallet = $base58->decode($address);
    $signature = base64_decode($request->input('signedMessage'));

    $result = sodium_crypto_sign_verify_detached($signature,$request->input('message'),$signingWallet);

    if($result == false)
    {
        return redirect()->back()->withInput()->withErrors(['signature' => ['Could not verify signature.']]);
    }
    
    $wallet = Wallet::where('address',$address)->first();

    if(!$wallet)
    {
      $wallet = new Wallet([
        'isNew' => true,
        'address' => $address,
        'chainId' => $project->chainId
      ]);
    }

    $request->session()->put('memberWallet',$wallet);

    return redirect()->route('member_dashboard',['collection_uid' => $project->collection_uid]);
  }

  public function dashboard($collection_uid, Request $request)
  {
    if(!$request->session()->has('memberWallet'))
    {
      return redirect()->route('member_login',['collection_uid' => $project->collection_uid]);
    }

    $project = Project::where('collection_uid',$collection_uid)->with('rewards')->first();

    if(!$project)
    {
      return "Project not found.";
    }

    $wallet = $request->session()->get('memberWallet');

    $karma_points = 0;
    $redemptions = null;
    $royalties = 0;

    if(!isset($wallet->isNew))
    {
      $karma_points = $wallet->karma_points;
      $redemptions = Redemption::where('projectId',$project->id)->where('walletId',$wallet->id)->get();
      $royalties = Transaction::where('walletId',$wallet->id)->where('projectId',$project->id)->sum('royalty');
    }

    $currency = $project->chain->currency;

    return view('members.member_dashboard',['project' => $project, 'wallet' => $wallet, 'redemptions' => $redemptions, 'royalties' => $royalties, 'message' => 'Authenticate', 'currency' => $currency ]);
  }


  public function claim($collection_uid, Request $request)
  {
    if(!$request->session()->has('memberWallet'))
    {
      return redirect()->route('member_login',['collection_uid' => $project->collection_uid]);
    }


    $project = Project::where('collection_uid',$collection_uid)->with('rewards')->first();

    if(!$project)
    {
      return "Project not found.";
    }

    $wallet = $request->session()->get('memberWallet');

    if(!$wallet)
    {
      return redirect()->back()->withInput()->withErrors(['claim_error' => ['You do not have enough karma points to claim this reward.']]);
    }

    $reward = Reward::where('id',$request->input('rewardId'))->first();

    if($reward->priceInPoints > $wallet->karma_points)
    {
      return redirect()->back()->withInput()->withErrors(['claim_error' => ['You do not have enough karma points to claim this reward.']]);
    }

    if($reward->redemptionInfo != null && !$request->input('informationInput'))
    {
      return redirect()->back()->withInput()->withErrors(['claim_error' => ['Please fill in the required information to claim this reward.']]);
    }
    
    if($reward->inventory < 1)
    {
      return redirect()->back()->withInput()->withErrors(['claim_error' => ['This reward is currently unavailable.']]);
    }

    Redemption::Create([
      'projectId' => $project->id,
      'walletId' => $wallet->id,
      'rewardId' => $reward->id,
      'priceInPoints' => $reward->priceInPoints,
      'info' => $request->input('informationInput')
    ]);

    $reward->inventory = $reward->inventory - 1;
    $reward->save();
    
    return redirect()->route('member_dashboard',['collection_uid' => $project->collection_uid])->with('success','Your claim has been successfully submitted! Scroll to the bottom of this page to track it\'s delivery.');
  }
}
