<?php

namespace App;

use RestCord\DiscordClient;
use RestCord\Model\Guild\GuildMember;

class DiscordApi
{
    private DiscordClient $client;
    private $guildId;

    public function __construct()
    {
        $this->client = new DiscordClient([
            'token' => config('services.discord.bot_token')
        ]);

        $this->guildId = config('services.discord.guild_id');
    }

    public function assertMemberExists($userId)
    {
        try {
            $guildMember = $this->client->guild->getGuildMember([
                'guild.id' => intval($this->guildId),
                'user.id' => intval($userId)
            ]);
            if ($guildMember instanceof GuildMember)
            {
                return true;
            }
            return false;
        }
        catch(\Exception $exception)
        {
            logger()->error($exception->getMessage());
            return false;
        }
    }

    public function createDMChannelForUser(User $user)
    {
        try
        {
            $channel = $this->client->user->createDm([
                'recipient_id' => (int) $user->discord_id
            ]);

            return $channel->id;
        }
        catch(\Exception $exception)
        {
            logger()->error($exception->getMessage());
            return false;
        }
    }
}
