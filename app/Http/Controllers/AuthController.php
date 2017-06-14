<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\User;

class AuthController extends Controller
{
    /**
     * [login Loggin user and create token]
     * @param  string email [description]
     * @param  string password [description]
     * @param  string rememberMe [description]
     * @return [json]           [description]
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            //if ($request->rememberMe)
                // Expires token more time
            //$user = Auth::user();
            $user = $request->user();
            // Creating a token without scopes...
            $user->token = $user->createToken('auth')->accessToken; // 30 days CHANGE expires to 1 day GET EXPIRES TIME TO CONF VARIABLE
            // Creating a token with scopes...
            //$token = $user->createToken('My Token', ['place-orders'])->accessToken;
            $json = [
                'success' => $user,
                'code' => 200,
                'message' => 'You are Logged in.'
            ];
            return response()->json($json, 200);
        }
        else{
            $json = [
                'error' => true,
                'code' => 401,
                'message' => 'Unauthorized'
            ];
            return response()->json($json, 401);
        }
    }

    /**
     * [logout Logout user and revoke token]
     * @param  Request $request [description]
     * @return [json]           [description]
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        $json = [
            'success' => true,
            'code' => 200,
            'message' => 'You are Logged out.'
        ];
        return response()->json($json, '200');
    }

    /**
     * [register Create user and create token]
     * @param  string name [description]
     * @param  string email [description]
     * @param  string password [description]
     * @param  string rePassword [description]
     * @return [json]           [description]
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:users',
            'email' => 'required|unique:users|email',
            'password' => 'required',
            'rePassword' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            $json = [
                'error' => $validator->errors(),
                'code' => 422,
                'message' => 'Unprocessable Entity'
            ];
            return response()->json($json, 422);
        }

        $data = $request->all();
        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);
        // Creating a token without scopes...
        $user->token = $user->createToken('auth')->accessToken; // 30 days down expires to 1 day
        // Creating a token with scopes...
        //$token = $user->createToken('My Token', ['place-orders'])->accessToken;

        $json = [
            'success' => $user,
            'code' => 200,
            'message' => 'You are register'
        ];
        return response()->json($json, 200);
    }

    /**
     * [authUser Get user authenticated]
     * @param  Request $request [description]
     * @return [json]           [description]
     */
    public function authUser(Request $request)
    {
        $json = [
            'success' => $request->user(),
            'code' => 200,
            'message' => 'Logged in User'
        ];
        return response()->json($json, 200);
    }
}
