<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PlaylistRecord
 *
 * @property int $id
 * @property int $playlist_id
 * @property int|null $song_lyric_id
 * @property string|null $title
 * @property int $order
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
 */
class PlaylistRecord extends Model
{
    use HasFactory;
}
