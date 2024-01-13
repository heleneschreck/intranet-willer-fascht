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
        try {
            $this->validate($request, [
                'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:7000',
                'article_id' => 'required'
            ]);
    
            // Reste du code pour le stockage de l'image
    
            $image_path = $request->file('image')->store('image', 'public');
            $data = illustrations::create([
                'image' => $image_path,
                'article_id' => $request->article_id,
            
            ]);
    
            return response($data, Response::HTTP_CREATED);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Code existant...
        
            return response()->json([
                'error' => 'Validation failed',
                'errors' => $e->validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            // Ajoutez cette partie pour afficher des détails sur l'exception
            report($e);
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }

    public function getIllustrationByArticle($article_id)
    {
        
        $illustrations = illustrations::where('article_id', $article_id)->get();     
        $count = $illustrations->count();
        $illustrationsData = [
          'count' => $count
        ];
        return response()->json($illustrationsData);
    }
    public function getIllustrationByArticleReduits($article_id)
{
    $illustrations = illustrations::where('article_id', $article_id)->get();     
    
    $imageData = [];

    foreach ($illustrations as $illustration) {
        $imageUrl = asset('storage/' . $illustration->image);
        $imageData[] = [
            'id' => $illustration->id,
            'url' => $imageUrl,
            'article_id' => $illustration->article_id,
            'created_at' => $illustration->created_at
        ];
    }

    return response()->json($imageData);
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
    /**
 * Remove all images associated with a specific article.
 *
 * @param  int  $article_id
 * @return \Illuminate\Http\Response
 */
public function destroyByArticle($article_id)
{
    // Récupérer toutes les images liées à l'article
    $images = illustrations::where('article_id', $article_id)->get();

    // Vérifier si des images existent pour l'article
    if ($images->isEmpty()) {
        return response()->json(['message' => 'No images found for the specified article'], 404);
    }

    // Supprimer chaque image du stockage et de la base de données
    foreach ($images as $image) {
        // Supprimer l'image du stockage
        Storage::disk('public')->delete($image->image);

        // Supprimer l'enregistrement de la base de données
        $image->delete();
    }

    return response()->json(['message' => 'All images for the article deleted successfully'], 200);
}

}
