<?php

namespace App\Http\Controllers\Api;

use App\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use ApiResponser;
    public function register(UserRequest $request){
        $data = $request->validated();
        $user = User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);

        return $this->success(
            UserResource::make($user),
            "Successfully added User"
        );
    }
}
