<?php


namespace App\Repository;


use App\Models\User;

class UserRepository extends Repository
{

    public function getAuthUser(): User
    {
        return User::first();
    }


    public function createUser($name, $email): User
    {
        $user        = new User();
        $user->name  = $name;
        $user->email = $email;
        $user->save();

        return $user;
    }
}
