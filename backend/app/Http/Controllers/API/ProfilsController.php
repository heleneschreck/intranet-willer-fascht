<?php

namespace App\Http\Controllers\API;

use App\Models\Profils;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;


class ProfilsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $images = Profils::all();
        $imageData = [];
        
        foreach ($images as $image) {
            $imageUrl = asset('storage/' . $image->image);
            $imageData[] = [
                'id' => $image->id,
                'url' => $imageUrl,
                'user_id' => $image->user_id,

            ];
        }
    
        return response()->json($imageData);
    } 

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:7000',
            'user_id' => 'required'
        ]);
        $image_path = $request->file('image')->store('image', 'public');

        $data = Profils::create([
            'image' => $image_path,
            'user_id' => $request->user_id,
        ]);

        return response($data, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Profils  $profils
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         // Supposez que vous avez un modèle Image avec une colonne 'path' qui enregistre le chemin de l'image
    $image = Profils::find($id);

    if (!$image) {
        return response()->json(['message' => 'Image not found'], 404);
    }

    return response()->json(['image_url' => asset('storage/'.$image->path)], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Profils  $profils
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $profils = Profils::find($id);

        // Vérifiez si le profil existe
        if (!$profils) {
            return response()->json(['message' => 'Profil not found'], 404);
        }
    
        // Validez les champs entrés dans la requête
        $this->validate($request, [
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:7000',
       
        ]);
    
        // Mettez à jour les champs avec les nouvelles valeurs
        $profils->image = $request->has('image') ? $request->file('image')->store('image', 'public') : $profils->image;
        
    
        // Sauvegardez les modifications dans la base de données
        $profils->save();
    
        // Retournez une réponse indiquant le succès
        return response()->json($profils, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Profils  $profils
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $image = Profils ::find($id);

        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }
    
        // Supprimer l'image du stockage
        Storage::disk('public')->delete($image->image);
    
        // Supprimer l'enregistrement de la base de données
        $image->delete();
    
        return response()->json(['message' => 'Image deleted successfully'], 200);
    }
}
