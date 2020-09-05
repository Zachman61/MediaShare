<?php

namespace App\Http\Controllers;

use App\DiscordApi;
use App\User;
use SocialiteProviders\Manager\OAuth2\User as DiscordUser;
use Illuminate\Support\Str;
use Socialite;

class AuthController extends Controller
{
    public function login()
    {
        return Socialite::driver('discord')->redirect();
    }

    public function loginCallback()
    {
        /** @var DiscordUser $discordUser */
        $discordUser = Socialite::driver('discord')->user();

        $client = new DiscordApi();
        if (!$client->assertMemberExists($discordUser->getId()))
        {
            abort(403);
        }

        $existingUser =  User::where('discord_id', $discordUser->getId())->first();
        if (empty($existingUser))
        {
            $user = new User;
            $user->username = $discordUser->getName();
            $user->discord_id = $discordUser->getId();
            $user->avatar = $discordUser->getAvatar();
            $user->api_key =  Str::random(60);
            $user->saveOrFail();
        }
        else
        {
            $existingUser->update([
                'avatar' => $discordUser->getAvatar(),
            ]);
            $user = $existingUser;
        }


        auth()->login($user);

        return redirect()->route('home');
    }

    public function logout()
    {
        auth()->logout();

        return redirect()->route('home');
    }
}
