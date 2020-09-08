<?php


namespace App;


use Laravel\Passport\Client;

/**
 * App\PassportClient
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $name
 * @property string|null $secret
 * @property string|null $provider
 * @property string $redirect
 * @property bool $personal_access_client
 * @property bool $password_client
 * @property bool $revoked
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\AuthCode[] $authCodes
 * @property-read int|null $auth_codes_count
 * @property-read string|null $plain_secret
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \App\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|PassportClient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PassportClient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PassportClient query()
 * @method static \Illuminate\Database\Eloquent\Builder|PassportClient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PassportClient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PassportClient whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PassportClient wherePasswordClient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PassportClient wherePersonalAccessClient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PassportClient whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PassportClient whereRedirect($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PassportClient whereRevoked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PassportClient whereSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PassportClient whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PassportClient whereUserId($value)
 * @mixin \Eloquent
 */
class PassportClient extends Client
{
    public function skipsAuthorization()
    {
        return true;
    }
}
