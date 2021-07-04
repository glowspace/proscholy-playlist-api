<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PlaylistRecord
 *
 * @property int                             $id
 * @property int                             $playlist_id
 * @property int|null                        $song_lyric_id
 * @property string|null                     $title
 * @property int                             $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PlaylistRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlaylistRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlaylistRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|PlaylistRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlaylistRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlaylistRecord whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlaylistRecord wherePlaylistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlaylistRecord whereSongLyricId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlaylistRecord whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlaylistRecord whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $type
 * @property int|null $title_tag_id
 * @property string|null $title_custom
 * @property string $name
 * @property int $previous_record_id
 * @method static \Illuminate\Database\Eloquent\Builder|PlaylistRecord whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlaylistRecord wherePreviousRecordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlaylistRecord whereTitleCustom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlaylistRecord whereTitleTagId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlaylistRecord whereType($value)
 * @property-read \App\Models\Playlist $playlist
 */
class PlaylistRecord extends Model
{
    use HasFactory;

    const TYPE_PROSCHOLY = 'proscholy';
    const TYPE_CUSTOM = 'custom';


    public function playlist()
    {
        return $this->belongsTo(Playlist::class);
    }
}
