<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\ApiController;
use App\Services\API\AuthService;
use App\Http\Requests\API\AuthRequest;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{
    private $service;
    public function __construct(AuthService $sr) {
        Parent::__construct();
        $this->service = $sr;
    }
    
    public function login(AuthRequest $req){
        $request = $req->validated();
        $customer = $this->service->customerExist($request['mobile']);
        if (!isset($customer->id)) {
            return $this->sendError("Failure",['User does not exist']);
        } 
        if (!Hash::check($request['password'], $customer->password)) {
            return $this->sendError("Failure",["Password Doesn't Match"]);
        }
        $customer->token = $customer->createToken('Follower Customer Token')->accessToken;
        return $this->sendResponse($customer);
    }
    
    public function logout(){
        if(!auth()->check()) {
            return $this->sendError("Failure",['User Not Logged In']);
        }
        $user = auth()->user();
        $user->token()->revoke();
        return $this->sendResponse($user);
    }
}
