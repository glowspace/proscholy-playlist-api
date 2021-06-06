<?php


namespace App\Repository;


use App\Models\Group;
use App\Models\User;

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
    }


    public function checkIfAlreadyMember(Group $group, $member): bool
    {
        if ($group->users()->contain($member))
        {
            return true;
        }

        return false;
    }
}
