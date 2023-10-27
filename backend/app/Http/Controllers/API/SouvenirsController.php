<?php

namespace App\Http\Controllers\API;

use App\Models\Souvenirs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class SouvenirsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
     $images = Souvenirs::all();
     $imageData = [];
     foreach ($images as $image){
        $imageUrl = asset('storage/' . $image->image);
        $imageData[] = [
            'id' => $image->id,
            'url' => $imageUrl,
            'title' => $image->title,

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
            'title' => 'required'
        ]);
        $image_path = $request->file('image')->store('image', 'public');

        $data = Souvenirs::create([
            'image' => $image_path,
            'title' => $request->title,
        ]);

        return response($data, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Souvenirs  $souvenirs
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $image = Souvenirs::find($id);

        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }
    
        return response()->json(['image_url' => asset('storage/'.$image->path)], 200);
        }
    
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Souvenirs  $souvenirs
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Souvenirs $souvenirs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Souvenirs  $souvenirs
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $image = Souvenirs ::find($id);

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
