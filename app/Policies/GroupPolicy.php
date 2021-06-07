<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use App\Repository\GroupRepository;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
{
    use HandlesAuthorization;

    /**
     * @var GroupRepository
     */
    private $group_repository;


    public function __construct(GroupRepository $groupRepository)
    {
        $this->group_repository = $groupRepository;
    }


    /**
     * Determine whether the user can view the model.
     *
     * @param User  $user
     * @param Group $group
     *
     * @return mixed
     */
    public function view(User $user, Group $group): bool
    {
        $is_member = $this->group_repository->checkIfUserIsMemberOfGroup($group, $user);

        if ($is_member)
        {
            return true;
        }

        return false;
    }


    /**
     * Determine whether the user can create models.
     *
     * @return bool
     */
    public function create(): bool
    {
        return true;
    }


    /**
     * Determine whether the user can update the model.
     *
     * @param User  $user
     * @param Group $group
     *
     * @return mixed
     */
    public function update(User $user, Group $group)
    {
        return $this->group_repository->checkIfUserIsAdminOfGroup($group, $user);
    }


    /**
     * Determine whether the user can delete the model.
     *
     * @param User  $user
     * @param Group $group
     *
     * @return mixed
     */
    public function delete(User $user, Group $group)
    {
        return $this->group_repository->checkIfUserIsAdminOfGroup($group, $user);
    }
}
