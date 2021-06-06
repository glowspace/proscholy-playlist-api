<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use App\Models\User;
use App\Repository\PlaylistRepository;
use App\Repository\UserRepository;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserPlaylistController
 *
 * @see     \UserPlaylistControllerCes,
 * t
 * @package App\Http\Controllers\Api
 */
class UserPlaylistController extends Controller
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
     * @return Response
     */
    public function index(): Response
    {
        $auth_user = $this->userRepository->getAuthUser();
        $playlists = $this->playlistRepository->getUserPlaylistsWithRecords($auth_user);

        return new Response($playlists);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(Request $request): Response
    {
        $this->authorize('create', Playlist::class);

        $playlist = $this->playlistRepository->createUserPlaylist($request['name'], Auth::user(), true);

        return new Response($playlist);
    }


    /**
     * Display the specified resource.
     *
     * @param Playlist $playlist
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show(Playlist $playlist): Response
    {
        $this->authorize('view', $playlist);

        return new Response($playlist);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request  $request
     * @param Playlist $playlist
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update(Request $request, Playlist $playlist): Response
    {
        $this->authorize('update', $playlist);
        $this->validate($request, [
            'name'       => 'string',
            'is_private' => 'bool',
            'datetime'   => 'date',
        ]);

        if ($request->has('name'))
        {
            $playlist->name = $request['name'];
        }

        if ($request->has('is_private'))
        {
            $playlist->is_private = $request['is_private'];
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
     * @param Playlist $playlist
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy(Playlist $playlist): Response
    {
        $this->authorize('delete', $playlist);

        $playlist->playlist_records()->delete();
        $playlist->delete();

        return new Response('Playlist deleted.', Response::HTTP_NO_CONTENT);
    }
}
