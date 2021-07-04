<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CustomSongLyric
 *
 * @property int $id
 * @property string $name
 * @property string $lyrics
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CustomSongLyric newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomSongLyric newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomSongLyric query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomSongLyric whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomSongLyric whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomSongLyric whereLyrics($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomSongLyric whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomSongLyric whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CustomSongLyric extends Model
{
    use HasFactory;
}
