<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use App\Models\User;
use App\Repository\PlaylistRepository;
use App\Repository\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class UserPlaylistController
 *
 * @see     \UserPlaylistControllerCest
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
     */
    public function store(Request $request): Response
    {
        $auth_user = $this->userRepository->getAuthUser();
        $playlist  = $this->playlistRepository->createUserPlaylist($request['name'], $auth_user, true);

        return new Response($playlist);
    }


    /**
     * Display the specified resource.
     *
     * @param Playlist $playlist
     *
     * @return Response
     */
    public function show(Playlist $playlist): Response
    {
        $auth_user = $this->userRepository->getAuthUser();

        $this->checkIfUserCanReadPlaylist($playlist, $auth_user);

        return new Response($playlist);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request  $request
     * @param Playlist $playlist
     *
     * @return Response
     */
    public function update(Request $request, Playlist $playlist)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Playlist $playlist
     *
     * @return Response
     */
    public function destroy(Playlist $playlist)
    {
        $auth_user = $this->userRepository->getAuthUser();

        $this->checkIfUserCanReadPlaylist($playlist, $auth_user);
        $this->checkIfUserCanUpdatePlaylist($playlist, $auth_user);

        $playlist->playlist_records()->delete();
        $playlist->delete();

        return new Response('Playlist deleted.', Response::HTTP_NO_CONTENT);
    }


    /**
     * @param Playlist $playlist
     * @param User     $auth_user
     *
     * @return void
     */
    private function checkIfUserCanReadPlaylist(Playlist $playlist, User $auth_user): void
    {
        // Playlist is public.
        if ( ! $playlist->is_private)
        {
            return;
        }

        // Playlist is owned by user.
        if ($playlist->user_id == $auth_user->id)
        {
            return;
        }

        // User cannot read this playlist.
        throw new UnauthorizedException("User cannot read this playlist.", 403);
    }


    private function checkIfUserCanUpdatePlaylist(Playlist $playlist, $auth_user)
    {
        // Playlist is owned by user.
        if ($playlist->user_id == $auth_user->id)
        {
            return;
        }

        // User cannot read this playlist.
        throw new AccessDeniedHttpException("User cannot manipulate with this playlist.");

    }
}
