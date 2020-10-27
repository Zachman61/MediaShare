<?php

namespace App\Http\Controllers;

use App\DiscordApi;
use App\User;
use Illuminate\Http\RedirectResponse;
use SocialiteProviders\Manager\OAuth2\User as DiscordUser;
use Illuminate\Support\Str;
use Socialite;

class AuthController extends Controller
{
    public function login() : RedirectResponse
    {
        return Socialite::driver('discord')->redirect();
    }

    public function loginCallback() : RedirectResponse
    {
        /** @var DiscordUser $discordUser */
        $discordUser = Socialite::driver('discord')->user();

//        $client = new DiscordApi();
//        if (!$client->assertMemberExists((int) $discordUser->getId()))
//        {
//            abort(403);
//        }

        $existingUser =  User::where('discord_id', $discordUser->getId())->first();
        if (empty($existingUser))
        {
            $user = new User;
            $user->username = $discordUser->getName();
            $user->discord_id = (int) $discordUser->getId();
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

        return redirect('/');
    }

    public function logout() : RedirectResponse
    {
        auth()->logout();

        return redirect('/');
    }
}
