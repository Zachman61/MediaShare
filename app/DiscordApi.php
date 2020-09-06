<?php

namespace App;

use Exception;
use RestCord\DiscordClient;
use RestCord\Model\Guild\GuildMember;

class DiscordApi
{
    private DiscordClient $client;
    private int $guildId;

    public function __construct()
    {
        $this->client = new DiscordClient([
            'token' => config('services.discord.bot_token')
        ]);

        $this->guildId = (int) config('services.discord.guild_id');
    }

    public function assertMemberExists(int $userId) : bool
    {
        try {
            $guildMember = $this->client->guild->getGuildMember([
                'guild.id' => $this->guildId,
                'user.id' => $userId
            ]);

            return true;
        }
        catch(Exception $exception)
        {
            \Log::error($exception->getMessage());
            return false;
        }
    }

    /**
     * @param User $user
     * @return int|false
     */
    public function createDMChannelForUser(User $user)
    {
        try
        {
            $channel = $this->client->user->createDm([
                'recipient_id' => (int) $user->discord_id
            ]);

            return $channel->id;
        }
        catch(Exception $exception)
        {
            \Log::error($exception->getMessage());
            return false;
        }
    }
}
