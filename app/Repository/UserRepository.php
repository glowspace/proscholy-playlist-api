<?php


namespace App\Repository;


use App\Models\User;

class UserRepository extends Repository
{

    public function getAuthUser(): User
    {
        return User::first();
    }


    public function createUser(): User
    {
        $user        = new User();
        $user->auth_token = '123456789';
        $user->save();

        return $user;
    }
}
