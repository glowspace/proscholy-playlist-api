<?php

namespace App\Repository;

use App\Models\Group;
use App\Models\Playlist;
use App\Models\User;

class PlaylistRepository extends Repository
{

    public function getUserPlaylistsWithRecords(User $user)
    {
        return $user->playlists->load('playlist_records');
    }


    public function getGroupPlaylistsWithRecords(Group $group)
    {
        return $group->playlists->load('playlist_records');
    }


    public function createUserPlaylist($name, User $user, bool $private): Playlist
    {
        $playlist             = new Playlist();
        $playlist->name       = $name;
        $playlist->user_id    = $user->id;
        $playlist->is_private = $private;
        $playlist->save();

        return $playlist;
    }


    public function createGroupPlaylist($name, Group $group, bool $private): Playlist
    {
        $playlist              = new Playlist();
        $playlist->name        = $name;
        $playlist->group_id    = $group->id;
        $playlist->is_private  = $private;
        $playlist->is_archived = false;
        $playlist->save();

        return $playlist;
    }


    public function deleteGroupPlaylist(Playlist $playlist)
    {
        $playlist->playlist_records()->delete();
        $playlist->delete();
    }


}
