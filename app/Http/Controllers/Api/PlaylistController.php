<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use App\Repository\PlaylistRepository;
use App\Repository\UserRepository;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $auth_user = $this->userRepository->getAuthUser();

        $playlists = $this->playlistRepository->getUserPlaylistsWithRecords($auth_user);

        return new Response($playlists);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Display the specified resource.
     *
     * @param Playlist $playlist
     *
     * @return Response
     */
    public function show(Playlist $playlist)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param Playlist $playlist
     *
     * @return Response
     */
    public function edit(Playlist $playlist)
    {
        //
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
        //
    }
}
