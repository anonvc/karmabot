<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;


class DiscordController extends Controller
{

  public function redirect()
  {
    return Socialite::driver('discord')->scopes(['guilds','guilds.members.read'])->redirect();
  }

  public function callback()
  {
    $discordUser = Socialite::driver('discord')->stateless()->user();
    $user = User::updateOrCreate([
        'discord_id' => $discordUser->id,
    ], [
        'username' => $discordUser->name,
        'discriminator' => $discordUser->user['discriminator'],
        'email' => $discordUser->email ?: NULL,
        'avatar' => $discordUser->avatar ?: NULL,
        'verified' => $discordUser->user['verified'],
        'access_token' => $discordUser->token,
        'refresh_token' => $discordUser->refreshToken,
    ]);
    Auth::login($user);

    return redirect('dashboard');
  }

  public function install()
  {
    $permissions_code = 18432; // Send Message, Embed Links
    return Socialite::driver('discord')->bot()->permissions($permissions_code)->redirect();
  }

}
