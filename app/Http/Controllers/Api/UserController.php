<?php

namespace App\Http\Controllers\Api;

use App\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
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
            UserResource::make($user, null),
            "Successfully added User"
        );
    }

    public function login(UserRequest $request)
    {
        $data = $request->validated();
        $res = Auth::attempt($request->validated());
        if(!$res){
            throw new HttpResponseException(response([
                'errors' => [
                    'username or password is wrong'
                ]
                ], 401
            ));
        }

        $user = User::where('email', $data['email'])->first();
        $token = $user->createToken('Personal Access Token')->accessToken;

        return $this->success(
            UserResource::make($user, $token),
            "Berhasil Login"
        );
    }
}
