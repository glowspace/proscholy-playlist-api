<?php

namespace App\Http\Controllers\Api\Group;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Repository\PlaylistRepository;
use App\Repository\UserRepository;
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
     */
    public function create($group_id)
    {
        $group = Group::findOrFail($group_id);
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
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        //
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
