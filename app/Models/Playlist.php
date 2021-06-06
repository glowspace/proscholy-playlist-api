<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
