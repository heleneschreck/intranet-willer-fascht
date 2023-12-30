<?php

namespace App\Http\Controllers\Api;

use App\Models\Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ImageController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $images = Image::all();
        $imageData = [];
        
        foreach ($images as $image) {
            $imageUrl = asset('storage/' . $image->image);
            $imageData[] = [
                'id' => $image->id,
                'url' => $imageUrl,
                'title' => $image->title,
                'user_id' => $image->user_id,
                'created_at' => $image->created_at
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
    public function imageStore(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:7000',
            'title' => 'required',
            'user_id' => 'required'
        ]);
        $image_path = $request->file('image')->store('image', 'public');

        $data = Image::create([
            'image' => $image_path,
            'title' => $request->title,
            'user_id' => $request->user_id,
        ]);

        return response($data, Response::HTTP_CREATED);
}
public function show($id)
{
    // Supposez que vous avez un modèle Image avec une colonne 'path' qui enregistre le chemin de l'image
    $image = Image::find($id);

    if (!$image) {
        return response()->json(['message' => 'Image not found'], 404);
    }

    return response()->json(['image_url' => asset('storage/'.$image->path)], 200);
}
/**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Participants  $participants
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $image = Image::find($id);

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