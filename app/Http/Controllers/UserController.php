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
 * Class User
 *
 * @package Petstore30
 *
 * @author  Donii Sergii <doniysa@gmail.com>
 *
 * @OA\Schema(
 *     title="User",
 *     description="User",
 * )
 */

/**
 
 * @OA\Tag(
 *     name="User",
 *     description="Operations about user",
 *     @OA\ExternalDocumentation(
 *         description="Find out more about store",
 *         url="http://swagger.io"
 *     )
 * )
 * @OA\Server(
 *     description="SwaggerHUB API Mocking",
 *     url="https://virtserver.swaggerhub.com/swagger/Petstore/1.0.0"
 * )
 * @OA\ExternalDocumentation(
 *     description="Find out more about Swagger",
 *     url="http://swagger.io"
 * )
 */




class UserController extends Controller
{
    //


/**
 * @SWG\Get(
 *      path="/users/{id}",
 *      operationId="getUserById",
 *      tags={"User"},
 *      summary="Get project information",
 *      description="Returns project data",
 *      @SWG\Parameter(
 *          name="id",
 *          description="User id",
 *          required=true,
 *          type="integer",
 *          in="path"
 *      ),
 *      @SWG\Response(
 *          response=200,
 *          description="successful operation"
 *       ),
 *      @SWG\Response(response=400, description="Bad request"),
 *      @SWG\Response(response=404, description="Resource Not Found"),
 *      security={
 *         {
 *             "oauth2_security_example": {"write:projects", "read:projects"}
 *         }
 *     },
 * )
 *
 */

    public function showUser($id){
        return Response::json([
            'data' => User::findOrFail($id) 
        ], 200);
        
        
        
    }
/**
 * @SWG\Get(
 *      path="/users",
 *      operationId="getUsers",
 *      tags={"User"},
 *      summary="Get project information",
 *      description="Returns project data",
 *      @SWG\Response(
 *          response=200,
 *          description="successful operation"
 *       ),
 *      @SWG\Response(response=400, description="Bad request"),
 *      @SWG\Response(response=404, description="Resource Not Found"),
 *      security={
 *         {
 *             "oauth2_security_example": {"write:projects", "read:projects"}
 *         }
 *     },
 * )
 *
 */
    public function showUsers() {
        
        return Response::json([
            'data' => User::paginate(5) 
        ], 200);
        
    }
/**
 * @SWG\Post(
 *      path="/users",
 *      operationId="InsertUser",
 *      tags={"User"},
 *      summary="",
 *      description="Returns project data",
 *      @SWG\Response(
 *          response=200,
 *          description="successful operation"
 *       ),
 *      @SWG\Response(response=400, description="Bad request"),
 *      @SWG\Response(response=404, description="Resource Not Found"),
 *      security={
 *         {
 *             "oauth2_security_example": {"write:projects", "read:projects"}
 *         }
 *     },
 * )
 *
 */
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
/**
 * @SWG\Put(
 *      path="/users/{id}",
 *      operationId="UpdateUserById",
 *      tags={"User"},
 *      summary="Update User",
 *      description="Update user data",
 *      @SWG\Parameter(
 *          name="id",
 *          description="User id",
 *          required=true,
 *          type="integer",
 *          in="path"
 *      ),
 *      @SWG\Response(
 *          response=200,
 *          description="successful operation"
 *       ),
 *      @SWG\Response(response=400, description="Bad request"),
 *      @SWG\Response(response=404, description="Resource Not Found"),
 *      security={
 *         {
 *             "oauth2_security_example": {"write:projects", "read:projects"}
 *         }
 *     },
 * )
 *
 */
    public function updateUser(Request $request){
        if ($request->email) {
            $userEmail = $request->email;
        }
        if ($request->name) {
            $userName = $request->name;
        }
        if ($request->password) {
            $password = $request->password;
            $password = bcrypt($password);
        }
        $UpdateUser = User::where('email', '=',  $userEmail)->first();
        
        
        if ($UpdateUser) {
            $UpdateUser->email = $userEmail;
            $UpdateUser->name = $userName;
            $UpdateUser->password = $password;
            $UpdateUser->save();
            return Response::json([
                'message' => "success" 
            ], 200);  
        } else {
            return Response::json([
                'message' => "no user found" 
            ])->setStatusCode(404);
        }       
                
    }
/**
 * @SWG\Delete(
 *      path="/users/{id}",
 *      operationId="deleteUserById",
 *      tags={"User"},
 *      summary="delete user",
 *      description="Delete User",
 *      @SWG\Parameter(
 *          name="id",
 *          description="User id",
 *          required=true,
 *          type="integer",
 *          in="path"
 *      ),
 *      @SWG\Response(
 *          response=200,
 *          description="successful operation"
 *       ),
 *      @SWG\Response(response=400, description="Bad request"),
 *      @SWG\Response(response=404, description="Resource Not Found"),
 *      security={
 *         {
 *             "oauth2_security_example": {"write:projects", "read:projects"}
 *         }
 *     },
 * )
 *
 */
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
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return response()->json(compact('token'));
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
            'firstname' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
        ]);
        $user = User::first();
        $token = JWTAuth::fromUser($user);
        
        return Response::json(compact('token'));
    }
}


