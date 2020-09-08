<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Media
 *
 * @package App
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $type
 * @property string $status
 * @property string $hash
 * @property string|false $filename
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Media newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Media newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Media query()
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereUserId($value)
 * @mixin \Eloquent
 * @property int $is_private
 * @property-read string $link
 * @property-read string $thumbnail
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereIsPrivate($value)
 */
class Media extends Model
{
    protected $fillable = [
        'title', 'type', 'user_id', 'status', 'hash', 'filename'
    ];

    protected $appends = [
        'link', 'thumbnail'
    ];

    protected $with = ['user'];

    protected $hidden = [
        'id'
    ];

    protected $attributes = [
        'filename' => '',
        'hash' => ''
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getLinkAttribute() : string
    {
        return url('/m/'. $this->hash);
    }

    public function getThumbnailAttribute() : string
    {
        if ($this->type === 'video') {
            return url('t/'. $this->hash);
        }

        return $this->link;
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('hash', $value)->firstOrFail();
    }
}
