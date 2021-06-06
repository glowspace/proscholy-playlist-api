<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use App\Repository\GroupRepository;
use Exception;
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
    public function index(): Response
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
    public function show(Group $group): Response
    {
        $this->authorize('view', $group);

        $group->load('users');

        return new Response($group);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Group   $group
     *
     * @return Response
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function update(Request $request, Group $group): Response
    {
        $this->authorize('update', $group);

        $this->validate($request, [
            'name'              => 'string',
            'dashboard_message' => 'string',
        ]);

        return new Response($group);
    }


    public function addMember(Group $group, Request $request): Response
    {
        $this->authorize('update', $group);

        $this->validate($request, [
            'user_id' => 'exists:users',
        ]);

        $member = User::findOrFail($request->input('user_id'));

        if ($this->group_repository->checkIfAlreadyMember($group, $member))
        {
            throw new Exception("User is already member of this group.", 403);
        }

        $this->group_repository->addMember($group, $member);

        return new Response($group);
    }


    /**
     * @throws AuthorizationException
     * @throws ValidationException
     * @throws Exception
     */
    public function updateMember(Group $group, Request $request): Response
    {
        $this->authorize('update', $group);

        $this->validate($request, [
            'user_id' => 'exists:users',
            'role'    => 'required|string',
        ]);

        $member = User::findOrFail($request->input('user_id'));
        $role   = $request->input('role');

        if ( ! $this->group_repository->checkIfAlreadyMember($group, $member))
        {
            throw new Exception("User is not member of this group.", 403);
        }

        $this->group_repository->updateMember($group, $member, $role);

        return new Response($group);
    }


    /**
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function deleteMember(Group $group, Request $request): Response
    {
        $this->authorize('update', $group);

        $this->validate($request, [
            'user_id' => 'exists:users',
        ]);

        $member = User::findOrFail($request->input('user_id'));

        if ( ! $this->group_repository->checkIfAlreadyMember($group, $member))
        {
            throw new Exception("User is not member of this group.", 403);
        }


        $this->group_repository->removeMember($group, $member);

        return new Response($group);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Group $group
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy(Group $group): Response
    {
        $this->authorize('delete', $group);

        $this->group_repository->delete($group);

        return new Response(['message' => 'Group was deleted.'], Response::HTTP_NO_CONTENT);
    }
}
