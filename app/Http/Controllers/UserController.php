<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User as User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use JWTFactory;
use JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;

use Swagger\Annotations as SWG;




/**
 * @OA\Info(title="My Rest Api Project", version="0.1")
 */



class UserController extends Controller
{
    //
    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     @OA\Response(response="200", description="get a user")
     * )
     */

    public function showUser($id){
        $user = User::find($id);
        if ($user) {
            return Response::json([
                'data' => $user 
            ], 200);
        } else {
            return Response::json([
                'message' => "no user found" 
            ])->setStatusCode(404);;
        }
        
        
        
        
    }

    public function showUsers() {
        
        return Response::json([
            'data' => User::paginate(5) 
        ], 200);
        
    }

    public function addUser(Request $request) {
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'firstname' => 'required',
            'password'=> 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(),422);
        }
        $newuser = new User($request->all());
        $newuser->save();
        return Response::json([
            'message' => "success" 
        ], 201);   
    }

    private function validateInsertUser($request){
        
        $validation = Validator::make($request->toArray(), [
            'firstname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

public function updateUser($id,Request $request)
{
    // validate
    // read more on validation at http://laravel.com/docs/validation
    
        // store
        $user = User::find($id);
        // return $request;
        // return $user->password;
        if ( $user ) {
            foreach ( $request->toArray() as $key => $value ) {
                foreach( $user->toArray() as $key_user => $value_user )
                {
                    if( $key == $key_user ){                       
                        $user->$key_user = $request->$key_user;
                    }
                    
                }
            }
            
            // return $request;
            $user->save();
            return Response::json([
                'message' => "successfully saved" 
            ])->setStatusCode( 200 );;

        } else {
            return Response::json([
                'message' => "no user found" 
            ])->setStatusCode( 404 );;
        }
        
        

        // redirect
     
    
}

    public function delete($id){

        

        $user = User::find($id);

        if ($user) {
            $user->delete();
            return response()->json(['success' => 'successfully deleted'], 200);
        } else {
            return Response::json([
                'message' => "no user found" 
            ])->setStatusCode(404);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password'=> 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $credentials = $request->only('email', 'password');
        $user = User::where('email',$request->email)->first();
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        $user = $user;
        
        $token = compact('token')['token'];
        $user->token = $token;
        $response = $user;
        return response()->json($response);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'firstname' => 'required',
            'password'=> 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        User::create([
            'firstname' => $request->get('firstname'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
        ]);
        $user = User::first();
        $token = JWTAuth::fromUser($user);
        
        return Response::json(compact('token'));
    }
}


