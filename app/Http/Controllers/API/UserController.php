<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderbyDesc('id')->get();
        if($users->count()>0){

            return response()->json(
                ['status'=>200,
            'users'=>$users],200);
           
        } else{

            return response()->json([
            'status'=>404,
            'messages'=>'No Record found'],404);

                
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
        $user = User::find($id);
        if($user){

            return response()->json(
                ['status'=>200,
                'user'=>$user
            ],200);

        }else{
            return response()->json(
                ['status'=>404,
                'messages'=>'user Not found'
            ],404);

        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required',
            'password'=>'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=> 422,
                'messages'=>  $validator->messages()

            ],422);
        }else{

            $user = user::find($id);
            if($user)
            {
                $user->update([
                    'name'=>$request->name,
                    'email'=>$request->email,
                    'password'=>Hash::make($request->password),
                ]);
                return response()->json([
                    'status'=> 200,
                    'messages'=> 'Mise a jour utilisateur réussie',
    
                ],200);


            }else{
                return response()->json([
                    'status'=>404,
                    'messages'=>'user Not found'
                ],404);

            }

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if($user)
        {
            $user->delete();
            return response()->json([
                'status'=> 200,
                'messages'=> 'User supprimé',

            ],200);


        }else{
            return response()->json(
                ['status'=>404,
                'messages'=>'User Not found'],404);

        }
    }
}
