<?php


namespace App\Repository;


use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class GroupRepository extends Repository
{
    public function getUserGroups(User $user)
    {
        return $user->groups;
    }


    public function createGroup($name): Group
    {
        $group       = new Group();
        $group->name = $name;
        $group->save();

        return $group;
    }


    public function addMember(Group $group, User $user, $role = 'member')
    {
        $group->users()->attach($user->id, ['role' => $role]);
    }


    public function updateMember(Group $group, User $user, $role)
    {
        $group->users()->updateExistingPivot($user->id, ['role' => $role]);
    }


    public function removeMember(Group $group, User $user)
    {
        $group->users()->detach($user->id);
    }


    public function delete(Group $group)
    {
        $group->users()->detach();
        $group->delete();
    }


    public function checkIfUserIsMemberOfGroup(Group $group, User $user): bool
    {
        if ($this->findUserInGroup($group, $user->id))
        {
            return true;
        }

        return false;
    }


    public function checkIfUserIsAdminOfGroup(Group $group, User $user): bool
    {
        if ($this->findUserInGroup($group, $user->id)->pivot->role == User::ROLE_ADMIN)
        {
            return true;
        }

        return false;
    }


    /**
     * @param Group $group
     * @param int   $user_id
     *
     * @return User|Collection|Model|null
     */
    public function findUserInGroup(Group $group, int $user_id)
    {
        return $group->users()
            ->find($user_id);
    }
}
