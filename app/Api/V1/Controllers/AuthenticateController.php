<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use \Tymon\JWTAuth\Exceptions\JWTException;

use App\User;

class AuthenticateController extends Controller
{
  public function authenticate(Request $request) {

    $user = User::where('email', '=', $request->input('email'))
      ->where('password', '=', md5($request->input('password')))
      ->first();

    try {
      if(! $user instanceof User) {
        return response()->json(['error' => 'invalid_credentials'], 401);
      }
      // create a token for the user
      if (! $token = JWTAuth::fromUser($user)) {
        return response()->json(['error' => 'invalid_credentials'], 401);
      }
    } catch(JWTException $e) {
      // something went wrong whilst attempting to encode the token
      return response()->json(['error' => 'could_not_create_token'], 500);
    }
    
    // all good so return the token
    return response()->json(compact('token'));
  }
}
