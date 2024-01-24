<?php

namespace App\Services;

use App\Models\User;
use App\Traits\crud;

class UserService extends BaseService
{
    use crud;

    public function model()
    {
        return User::class;
    }

    public function createUser($request)
    {
        $user = $this->query()->firstOrCreate([
            'phone' => $request->phone
        ], $request->all());
        return $user;
    }

}
