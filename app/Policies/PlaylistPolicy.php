<?php

namespace App\Policies;

use App\Models\Playlist;
use App\Models\User;
use Exception;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlaylistPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param User     $user
     * @param Playlist $playlist
     *
     * @return bool
     * @throws Exception
     */
    public function view(User $user, Playlist $playlist): bool
    {
        if ($playlist->isUserPlaylist())
        {
            // Playlist is public.
            if ( ! $playlist->is_private)
            {
                return true;
            }

            // User must be owner of playlist.
            if ( ! $this->checkIfUserIsOwnerOfPlaylist($playlist, $user))
            {
                return false;
            }

            return true;
        }

        throw new Exception('Invalid playlist type during authorization.');
    }


    /**
     * Determine whether the user can create models.
     *
     * @return bool
     */
    public function create(): bool
    {
        return true;
    }


    /**
     * Determine whether the user can update the model.
     *
     * @param User     $user
     * @param Playlist $playlist
     *
     * @return bool
     */
    public function update(User $user, Playlist $playlist): bool
    {
        if ($playlist->isUserPlaylist())
        {
            // User must be owner of playlist.
            if ( ! $this->checkIfUserIsOwnerOfPlaylist($playlist, $user))
            {
                return false;
            }
        }

        return true;
    }


    /**
     * Determine whether the user can delete the model.
     *
     * @param User     $user
     * @param Playlist $playlist
     *
     * @return bool
     */
    public function delete(User $user, Playlist $playlist): bool
    {
        if ($playlist->isUserPlaylist())
        {
            // User must be owner of playlist.
            if ( ! $this->checkIfUserIsOwnerOfPlaylist($playlist, $user))
            {
                return false;
            }
        }

        return true;
    }


    private function checkIfUserIsOwnerOfPlaylist(Playlist $playlist, User $user): bool
    {
        if ($playlist->user_id == $user->id)
        {
            return true;
        }

        return false;
    }
}
