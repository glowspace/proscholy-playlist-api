<?php

namespace App\Repository;

use App\Models\User;

class PlaylistRepository
{

    public function getUserPlaylists(User $user)
    {
        return $user->playlists;
    }
}
