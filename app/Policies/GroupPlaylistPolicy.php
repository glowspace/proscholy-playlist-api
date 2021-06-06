<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\Playlist;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class GroupPlaylistPolicy
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
        $playlist_group = $playlist->group;

        if ( ! $this->checkIfUserIsMemberOfPlaylistGroup($user, $playlist_group))
        {
            Response::deny('User cannot update this group playlist.', 403);
        }
    }


    /**
     * Determine whether the user can create models.
     *
     * @param User  $user
     * @param Group $group
     *
     * @return mixed
     */
    public function create(User $user, Group $group)
    {
        if ( ! $this->checkIfUserIsMemberOfPlaylistGroup($user, $group))
        {
            Response::deny('User cannot create playlist at this group.', 403);
        }

        Response::allow();
    }


    /**
     * Determine whether the user can update the model.
     *
     * @param User     $user
     * @param Playlist $playlist
     *
     * @return mixed
     */
    public function update(User $user, Playlist $playlist)
    {
        $this->checkIfPlaylistIsGroupPlaylist($playlist);
        $playlist_group = $playlist->group;

        if ( ! $this->checkIfUserIsMemberOfPlaylistGroup($user, $playlist_group))
        {
            Response::deny('User cannot update this group playlist.', 403);
        }

        Response::allow();
    }


    /**
     * Determine whether the user can delete the model.
     *
     * @param User     $user
     * @param Playlist $playlist
     *
     * @return mixed
     */
    public function delete(User $user, Playlist $playlist)
    {
        $this->checkIfPlaylistIsGroupPlaylist($playlist);
        $playlist_group = $playlist->group;

        if ( ! $this->checkIfUserIsMemberOfPlaylistGroup($user, $playlist_group))
        {
            Response::deny('User cannot delete this group playlist.', 403);
        }

        Response::allow();
    }


    private function checkIfPlaylistIsGroupPlaylist(Playlist $playlist): void
    {
        if ($playlist->group_id)
        {
            return;
        }

        Response::deny('Group playlist with this id not found.', 404);
    }


    /**
     * @param Group $playlist_group
     * @param User  $user
     *
     * @return mixed
     */
    private function checkIfUserIsMemberOfPlaylistGroup(User $user, Group $playlist_group)
    {
        return $playlist_group->members()->contain('id', $user->id);
    }
}
