<?php

namespace App\Policies;

use App\Models\Playlist;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Response as Res;

class UserPlaylistPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param User     $user
     * @param Playlist $playlist
     *
     * @return void
     */
    public function view(User $user, Playlist $playlist)
    {
        // Playlist must not be group playlist.
        $this->playlistMustBeUserPlaylist($playlist);

        // Playlist is public.
        if ( ! $playlist->is_private)
        {
            Response::allow();
        }

        // User must be owner of playlist.
        $this->userMustBeOwnerOfThisPlaylist($playlist, $user);
        Response::allow();
    }


    /**
     * Determine whether the user can create models.
     *
     * @return void
     */
    public function create()
    {
        Response::allow();
    }


    /**
     * Determine whether the user can update the model.
     *
     * @param User     $user
     * @param Playlist $playlist
     *
     * @return void
     */
    public function update(User $user, Playlist $playlist)
    {
        $this->playlistMustBeUserPlaylist($playlist);
        $this->userMustBeOwnerOfThisPlaylist($playlist, $user);

        Response::allow();
    }


    /**
     * Determine whether the user can delete the model.
     *
     * @param User     $user
     * @param Playlist $playlist
     *
     * @return void
     */
    public function delete(User $user, Playlist $playlist)
    {
        $this->playlistMustBeUserPlaylist($playlist);
        $this->userMustBeOwnerOfThisPlaylist($playlist, $user);

        Response::allow();
    }


    private function playlistMustBeUserPlaylist(Playlist $playlist): void
    {
        if ($playlist->user_id)
        {
            return;
        }

        Response::deny('Personal playlist with this id not found.', Res::HTTP_NOT_FOUND);
    }


    private function userMustBeOwnerOfThisPlaylist(Playlist $playlist, User $user)
    {
        if ($playlist->user_id == $user->id)
        {
            return;
        }

        Response::deny('User does not own this playlist.', Res::HTTP_FORBIDDEN);
    }
}
