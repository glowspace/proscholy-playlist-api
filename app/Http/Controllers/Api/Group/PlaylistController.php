<?php

namespace App\Http\Controllers\Api\Group;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Playlist;
use App\Models\User;
use App\Repository\PlaylistRepository;
use App\Repository\UserRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

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
        $group = Group::findOrFail($group_id);

        $this->authorize('create', [Playlist::class, $group]);
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
    public function show($group_id, Playlist $playlist)
    {
        $group = Group::findOrFail($group_id);
        $this->authorize('view', [$playlist, $group]);

        return new Response($playlist);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
