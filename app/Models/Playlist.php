<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Playlist
 *
 * @property int $id
 * @property string $name
 * @property int $is_private
 * @property int|null $user_id
 * @property int|null $group_id
 * @property string|null $datetime
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Group|null $group
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PlaylistRecord[] $playlist_records
 * @property-read int|null $playlist_records_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist query()
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereIsPrivate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereUserId($value)
 * @mixin \Eloquent
 */
class Playlist extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function group()
    {
        return $this->belongsTo(Group::class);
    }


    public function playlist_records()
    {
        return $this->hasMany(PlaylistRecord::class, 'playlist_id');
    }


    public function addSongLyricRecord(int $song_lyric_id, $title = null): PlaylistRecord
    {
        $record                = new PlaylistRecord();
        $record->playlist_id   = $this->id;
        $record->song_lyric_id = $song_lyric_id;
        $record->title         = $title;
        $record->order         = 1;
        $record->save();

        return $record;
    }


    public function isUserPlaylist(): bool
    {
        if($this->user_id)
        {
            return true;
        }

        return false;
    }

    public function isGroupPlaylist(): bool
    {
        if($this->group_id)
        {
            return true;
        }

        return false;
    }
}
