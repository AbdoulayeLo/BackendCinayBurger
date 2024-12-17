<?php

namespace App\Http\Controllers;

use App\Http\Requests\BurgerStoreRequest;
use App\Models\Article;
use App\Models\Burger;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

//use App\Http\Requests\BurgerStoreRequest;

class BurgerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $burgers=Burger::all();
        return response()->json($burgers,200);
    }
//    public function store(Request $request)
//    {
//        $valid=$request->validate([
//            'nom'=>'required|max:200',
//            'prix'=>'required',
//            'description'=>'required',
//            'image'=>'required|image|mimes:jpeg,png, jpg,gif|max:2048',
//        ]);
//        $burger= Burger::create($valid);
//        return response()->json($burger,201);
//    }
//    public function store(Request $request)
//    {
//        try {
//             $request->validate([
//                'nom' => 'required|max:200',
//                'prix' => 'required|numeric', // Ajout de la validation pour s'assurer que le prix est un nombre
//                'description' => 'required',
//                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
//            ]);
////
//            $imageName =$request->image->getClientOriginalName();
////            dd($imageName);
//            // Traitement de l'image
////        if ($request->hasFile('image')) {
//////            $imagePath = $request->file('image')->store('images', 'public'); // Stocke l'image dans le dossier public/images
////            $imageName = time().'.'.$request->image->extension();
////            $request->image->move(public_path('images'), $imageName);    $imageName = time().'.'.$request->image->extension();
////            $valid['image'] = $imageName; // Ajoute le chemin de l'image aux données validées
////        }
//
//            // Créer le burger avec les données validées
//            $burger = Burger::create([
//                'nom' => $request->nom,
//                'prix' => $request->prix,
//                'description' =>$request->description,
//                'image' => $imageName
//            ]);
//            Storage::disk('public')->put($imageName,file_get_contents($request->image));
//            return response()->json([
//                'message'=>'Burger creer avec Success'
//            ],200);
//
//        }catch (\Exception $e){
//            return response()->json([
//                'message'=>"Une erreur s'est produite"
//            ],500);
//        }
//
//
//        // Retourne la réponse avec le burger créé
//        return response()->json($burger, 201);
//    }

    public function store(Request $request)
    {
//        try {
            // Validation des données
//            $validatedData = $request->validate([
//                'nom' => 'required|max:200',
//                'prix' => 'required|numeric',
//                'description' => 'required',
//                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
//            ]);
//
//            // Traitement de l'image
//            if ($request->hasFile('image')) {
//                $imageName = time() . '.' . $request->image->extension(); //getClientOriginalExtension   49:02
//                $request->image->move(public_path('images'), $imageName);
//            } else {
//                return response()->json(['message' => "Une erreur s'est produite avec l'image"], 400);
//            }
//
//            // Création du burger avec les données validées
//            $burger = Burger::create([
//                'nom' => $validatedData['nom'],
//                'prix' => $validatedData['prix'],
//                'description' => $validatedData['description'],
//                'image' => $imageName
//            ]);
//            Log::info('Burger créé avec succès', ['burger' => $burger]);
//
//            return response()->json([
//                'message' => 'Burger créé avec succès',
//                'burger' => $burger
//            ], 201);
            $data = $request->all();
            if ($request->hasFile('image')){
                $path = $request->file('image')->store('images','public');
                $data['image'] = $path;
            }
            $burger = Burger::create($data);
            return response()->json($burger,201);

//        } catch (\Exception $e) {
//            return response()->json([
//                'message' => "Une erreur s'est produite",
//                'error' => $e->getMessage()
//            ], 500);
//        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $burger=Burger::findOrFail($id);
        return response()->json($burger,201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $burger=Burger::findOrFail($id);
            $valid=$request->validate([
                'nom'=>'required|max:200',
                'prix'=>'required',
                'description'=>'required',
                'image'=>'required'
            ]);
            $burger->update($valid);
            return response()->json( $burger,200);
        }catch (ModelNotFoundException $e){
            return response()->json(['error'=>'Article not found'],404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
