<?php

namespace App\Http\Controllers\API;

use App\Models\cocktail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class CocktailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cocktail = cocktail::orderbyDesc('id')->get();
        if($cocktail->count()>0){

            return response()->json(
                ['status'=>200,
            'cocktail'=>$cocktail],200);
           
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
            'description'=>'required',
            'recette'=>'required',

        ]);
        if($validator->fails())
        {
            return response()->json([
                'status'=> 422,
                'errors'=>  $validator->messages()

            ],422);
        }else{
            $cocktail = cocktail::create([
                'name'=>$request->name,
                'description'=>$request->description,
                'recette'=>$request->recette,

            ]);

            if($cocktail){
                return response()->json([
                    'status'=> 200,
                    'messages'=> 'Validation réussie',
    
                ],200);


            }else{
                return response()->json([
                    'status'=> 500,
                    'messages'=> 'Enregistrement cocktail échouée.',
    
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
        $cocktail = cocktail::find($id);
        if($cocktail){

            return response()->json(
                ['status'=>200,
                'cocktail'=>$cocktail
            ],200);

        }else{
            return response()->json(
                ['status'=>404,
                'messages'=>'cocktail Not found'
            ],404);

        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        {
            $validator = Validator::make($request->all(),[
            'name'=>'required|max:191',
            'description'=>'required',
            'recette'=>'required',
            ]);
            if($validator->fails()){
                return response()->json([
                    'status'=> 422,
                    'messages'=>  $validator->messages()
    
                ],422);
            }else{
    
                $cocktail = cocktail::find($id);
                if($cocktail)
                {
                    $cocktail->update([
                        'name'=>$request->name,
                        'description'=>$request->description,
                        'recette'=>$request->recette,
                    ]);
                    return response()->json([
                        'status'=> 200,
                        'messages'=> 'Mise a jour cocktail réussie',
        
                    ],200);
    
    
                }else{
                    return response()->json([
                        'status'=>404,
                        'messages'=>'cocktail Not found'
                    ],404);
    
                }
    
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cocktail = cocktail::find($id);
        if($cocktail)
        {
            $cocktail->delete();
            return response()->json([
                'status'=> 200,
                'messages'=> 'cocktail supprimé',

            ],200);


        }else{
            return response()->json(
                ['status'=>404,
                'messages'=>'cocktail Not found'],404);

        }
    }
}
