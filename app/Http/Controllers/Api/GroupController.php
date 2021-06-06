<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Repository\GroupRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class GroupController extends Controller
{
    /**
     * @var GroupRepository
     */
    private $group_repository;


    public function __construct(GroupRepository $groupRepository)
    {
        $this->group_repository = $groupRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $groups = $this->group_repository->getUserGroups(Auth::user());

        return new Response($groups);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return Response
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function store(Request $request): Response
    {
        $this->authorize('create', Group::class);

        $this->validate($request, [
            'name' => 'required|string',
        ]);

        $group = $this->group_repository->createGroup($request['name']);

        return new Response($group);
    }


    /**
     * Display the specified resource.
     *
     * @param Group $group
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show(Group $group)
    {
        $this->authorize('view', $group);

        return new Response($group);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Group   $group
     *
     * @return Response
     */
    public function update(Request $request, Group $group)
    {
        $this->authorize('update', $group);

        $this->validate($request, [
            'name' => 'string',
            'dashboard_message' => 'string'
        ]);
    }


    public function addMember()
    {

    }


    public function updateMember()
    {

    }


    public function deleteMember()
    {

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Group $group
     *
     * @return Response
     */
    public function destroy(Group $group)
    {
        //
    }
}
