<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\illustrations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class IllustrationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $images = illustrations::all();
        $imageData = [];
        
        foreach ($images as $image) {
            $imageUrl = asset('storage/' . $image->image);
            $imageData[] = [
                'id' => $image->id,
                'url' => $imageUrl,
                'article_id' => $image->article_id,
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
    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:7000',
            'article_id' => 'required'
        ]);
        $image_path = $request->file('image')->store('image', 'public');

        $data = illustrations::create([
            'image' => $image_path,
            'article_id' => $request->article_id,
        ]);

        return response($data, Response::HTTP_CREATED);
    }

    public function getIllustrationByArticle($article_id)
    {
        
        $illustrations = illustrations::where('article_id', $article_id)->get();
        return response()->json($illustrations);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\illustrations  $illustrations
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $image = illustrations::find($id);

        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }
    
        return response()->json(['image_url' => asset('storage/'.$image->path)], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\illustrations  $illustrations
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, illustrations $illustrations)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\illustrations  $illustrations
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $image = illustrations::find($id);

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
