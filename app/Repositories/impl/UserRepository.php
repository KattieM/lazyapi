<?php
namespace App\Repositories;

use App\User;

/**
 * Class UserRepository.
 */
class UserRepository implements UserRepositoryInterface
{
    public function model()
    {
        return User::class;
    }

    public function validateUser($request)
    {
        $request->validate(['firstName' => 'required|max:191',
            'lastName' => 'required|max:191',
            'username' => 'required|unique:users,surname|max:191',
            'email' => 'required|unique:users,email|email|max:191',
        ], [
            'username.unique' => 'Username already taken.',
            'email.unique' => 'Email already taken.',
        ]);
    }
}