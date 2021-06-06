<?php

namespace App\Policies;

use App\Models\Playlist;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Response as Res;

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
            $this->playlistMustBeUserPlaylist($playlist);
            $this->checkIfUserIsOwnerOfPlaylist($playlist, $user);
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
            $this->playlistMustBeUserPlaylist($playlist);
            $this->checkIfUserIsOwnerOfPlaylist($playlist, $user);
        }

        return true;
    }


    private function playlistMustBeUserPlaylist(Playlist $playlist): void
    {
        if ($playlist->user_id)
        {
            return;
        }

        Response::deny('Personal playlist with this id not found.', Res::HTTP_NOT_FOUND);
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
