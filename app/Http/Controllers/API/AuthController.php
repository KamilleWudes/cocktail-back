<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required|max:191',
            'email'=>'required|email|max:191|unique:users,email',
            'password'=>'required|min:8',

        ]);
        if($validator->fails())
        {
            return response()->json([
                'status'=> 422,
                'errors'=>  $validator->messages()

            ],422);
        }else{
            $user = User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>Hash::make($request->password),

            ]);

            if($user){
                return response()->json([
                    'status'=> 200,
                    'messages'=> 'Validation réussie',
    
                ],200);


            }else{
                return response()->json([
                    'status'=> 500,
                    'messages'=> 'Enregistrement user échouée.',
    
                ],500);

            }
           
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'status'=> 422,
                'errors'=>  $validator->messages()

            ],422);
        }

        //Request is validated
        //Crean token
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                 'success' => false,
                 'message' => 'Authentification incorrecte.',
                ], 400);
            }
        } catch (JWTException $e) {
     return $credentials;
            return response()->json([
                 'success' => false,
                 'message' => 'Could not create token.',
                ], 500);
        }
  
   //Token created, return with success response and jwt token
   $cookie = Cookie('token', $token, 60 * 1); // 60 * 24 -> 1 day

        return response()->json([
            'success' => true,
            'token' => $token,

        ])->withCookie($cookie);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function logout()
    {
        $cookie = Cookie::forget('token');
        request()->user()->tokens()->delete();

        return response([
            'message' => 'Vous vous êtes déconnecté avec succès.'
        ])->withCookie($cookie);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
