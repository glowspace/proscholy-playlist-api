<?php

namespace App\Http\Controllers\Api\Group;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Playlist;
use App\Models\User;
use App\Repository\PlaylistRepository;
use App\Repository\UserRepository;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Exception;

/**
 * Class PlaylistController
 *
 * @see     \GroupPlaylistControllerCest
 * @package App\Http\Controllers\Api\Group
 */
class PlaylistController extends Controller
{

    /**
     * @var PlaylistRepository
     */
    private $playlistRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;


    public function __construct(
        PlaylistRepository $playlistRepository,
        UserRepository $userRepository
    ) {
        $this->playlistRepository = $playlistRepository;
        $this->userRepository     = $userRepository;

        // TODO: only for early prototyping!!!
        Auth::login(User::first());
    }


    /**
     * Display a listing of the resource.
     *
     * @param $group_id
     *
     * @return Response
     */
    public function index($group_id): Response
    {
        // Group from route param
        $group = Group::findOrFail($group_id);

        $playlists = $this->playlistRepository->getGroupPlaylistsWithRecords($group);

        return new Response($playlists);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store($group_id, Request $request)
    {
        // Group from route param
        $group = Group::findOrFail($group_id);

        $this->authorize('update', [Playlist::class, $group]);
        $playlist = $this->playlistRepository->createGroupPlaylist($request['name'], $group, true);

        return new Response($playlist);
    }


    /**
     * Display the specified resource.
     *
     * @param          $group_id
     * @param Playlist $playlist
     *
     * @return Response
     * @throws AuthorizationException
     * @see \GroupPlaylistControllerCest::showGroupPlaylist()
     */
    public function show($group_id, Playlist $playlist): Response
    {
        $this->validateRouteIntegrity($group_id, $playlist);

        $this->validateIsGroupPlaylist($playlist);
        $this->authorize('view', $playlist);

        return new Response($playlist);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param          $group_id
     * @param Playlist $playlist
     * @param Request  $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update($group_id, Playlist $playlist, Request $request): Response
    {
        $this->validateRouteIntegrity($group_id, $playlist);

        $this->validate($request, [
            'name'        => 'string',
            'is_private'  => 'bool',
            'is_archived' => 'bool',
            'datetime'    => 'date',
        ]);

        $this->validateIsGroupPlaylist($playlist);
        $this->authorize('update', $playlist);

        if ($request->has('name'))
        {
            $playlist->name = $request['name'];
        }

        if ($request->has('is_private'))
        {
            $playlist->is_private = $request['is_private'];
        }

        if ($request->has('is_archived'))
        {
            $playlist->is_archived = $request['is_archived'];
        }

        if ($request->has('datetime'))
        {
            $playlist->datetime = Carbon::parse($request['datetime']);
        }

        $playlist->save();

        return new Response($playlist);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param          $group_id
     * @param Playlist $playlist
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($group_id, Playlist $playlist)
    {
        $this->validateRouteIntegrity($group_id, $playlist);

        $this->validateIsGroupPlaylist($playlist);
        $this->authorize('delete', $playlist);

        $this->playlistRepository->deleteGroupPlaylist($playlist);

        return new Response('Playlist deleted.', Response::HTTP_NO_CONTENT);
    }


    /**
     * Throw exception if playlist is not user playlist.
     *
     * @param Playlist $playlist
     *
     * @return bool
     */
    public function validateIsGroupPlaylist(Playlist $playlist): bool
    {
        if ( ! $playlist->isGroupPlaylist())
        {
            throw new Exception("This is not group playlist.");
        }

        return true;
    }


    private function validateRouteIntegrity($group_id, Playlist $playlist)
    {
        // Playlist must belong to requested group
        if ($playlist->group_id != $group_id)
        {
            throw new Exception('Playlist not found in this group.');
        }

        return Group::findOrFail($group_id);
    }
}
