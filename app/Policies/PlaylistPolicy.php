<?php

namespace App\Policies;

use App\Models\Group;
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
    public function view(User $user, Playlist $playlist, ?Group $group): bool
    {
        if ($playlist->isUserPlaylist())
        {
            // Playlist is public.
            if ( ! $playlist->is_private)
            {
                return true;
            }

            // User must be owner of playlist.
            if ($this->checkIfUserIsOwnerOfPlaylist($playlist, $user))
            {
                return true;
            }

            return false;
        }
        elseif ($playlist->isGroupPlaylist())
        {
            // Playlist is public.
            if ( ! $playlist->is_private)
            {
                return true;
            }

            // Playlist must belong to requested group
            if ($playlist->group_id != $group->id)
            {
                return false;
            }

            // User is member of group
            if ($user->can('show', $playlist->group))
            {
                return true;
            }

            return false;
        }

        throw new Exception('Invalid playlist type during authorization.');
    }


    /**
     * Determine whether the user can create models.
     *
     * @param User       $user
     * @param Group|null $group
     *
     * @return bool
     */
    public function create(User $user, ?Group $group): bool
    {
        if ($group && $user->cannot('update', $group))
        {
            return false;
        }

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
