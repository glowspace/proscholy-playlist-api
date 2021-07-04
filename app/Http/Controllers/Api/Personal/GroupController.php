<?php

namespace App\Http\Controllers\Api\Personal;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use App\Repository\GroupRepository;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Collection;
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
        $this->validate($request, [
            'name' => 'required|string',
        ]);

        $this->authorize('create', Group::class);

        $group = $this->group_repository->createGroup($request['name']);
        $this->group_repository->addMember($group, Auth::user(), User::ROLE_ADMIN);

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

        if ($request->has('name'))
        {
            $group->name = $request['name'];
        }

        if ($request->has('dashboard_message'))
        {
            $group->dashboard_message = $request['dashboard_message'];
        }

        $group->save();

        return new Response($group);
    }


    /**
     * @throws AuthorizationException
     * @throws ValidationException
     * @throws Exception
     */
    public function addMember(Group $group, Request $request): Response
    {
        $this->authorize('update', $group);

        $this->validate($request, [
            'user_id' => 'exists:users',
        ]);

        $member = User::findOrFail($request->input('user_id'));

        if ($this->group_repository->checkIfUserIsMemberOfGroup($group, $member))
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

        if ( ! $this->group_repository->checkIfUserIsMemberOfGroup($group, $member))
        {
            throw new Exception("User is not member of this group.", 403);
        }

        $this->group_repository->updateMember($group, $member, $role);

        return new Response($group);
    }


    /**
     * @throws AuthorizationException
     * @throws ValidationException
     * @throws Exception
     */
    public function deleteMember(Group $group, Request $request): Response
    {
        $this->authorize('update', $group);

        $this->validate($request, [
            'user_id' => 'exists:users',
        ]);


        $member = $this->group_repository->findUserInGroup($group, $request->input('user_id'));

        $new_member_collection = $group->users->forget($member->id);

        if ( ! $this->checkNewMemberCollectionIntegrity($new_member_collection))
        {
            throw new Exception('Cannot remove this user from group because there would be no admin.', Response::HTTP_BAD_REQUEST);
        }

        if ( ! $this->group_repository->checkIfUserIsMemberOfGroup($group, $member))
        {
            throw new Exception("User is not member of this group.", Response::HTTP_BAD_REQUEST);
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


    private function checkNewMemberCollectionIntegrity(Collection $member_collection): bool
    {
        // Group must have at least 1 admin
        foreach ($member_collection as $user)
        {
            if ($user->pivot->role == User::ROLE_ADMIN)
            {
                return true;
            }
        }

        return false;
    }
}
