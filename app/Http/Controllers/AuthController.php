<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\ProfileResource;
use App\Http\Utilities\HttpUtility;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(AuthRepository $authRepo, HttpUtility $httpUtility)
    {
        $this->authRepo = $authRepo;
        $this->httpUtility = $httpUtility;
    }

    public function register(RegisterRequest $request){
        $data = [];
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);
       
        $user = $this->authRepo->create($data);
        $result = [
            'user' => new ProfileResource($user),
            'token' => $user->createToken('movie')->accessToken
        ];

        return $this->httpUtility->successResponse($result, "Register successfully");
    }

    public function login(LoginRequest $request){

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $data = [
                "user" => new ProfileResource(Auth::user()),
                'token' =>  Auth::user()->createToken('movie')->accessToken

            ];

            return $this->httpUtility->successResponse($data, "Login successfully");
        }
        else{
            return $this->httpUtility->badRequestResponse(null, "Login Failed");
        }

    }
}
