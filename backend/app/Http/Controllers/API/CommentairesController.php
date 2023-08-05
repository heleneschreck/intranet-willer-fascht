<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Commentaires;
use Illuminate\Http\Request;

class CommentairesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $commentaires = Commentaires::all();
        return response()->json($commentaires);
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
            'user_id' => 'required',
            'image_id' => 'required',
            'commentaire' => 'required',
        ]);  
        $commentaires = Commentaires::create([
            'user_id' => $request->user_id,
            'image_id' => $request->image_id,
            'commentaire' => $request->commentaire
        ]);
        return response()->json($commentaires, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Commentaires  $commentaires
     * @return \Illuminate\Http\Response
     */
    public function show(Commentaires $commentaires)
    {
        return response()->json($commentaires);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Commentaires  $commentaires
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $commentaires = Commentaires::find($id);
        $commentaires->update($request->all());
        return $commentaires; 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Commentaires  $commentaires
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $commentaires = Commentaires::findOrFail($id);
        if ($commentaires)
            $commentaires->delete();
        else
            return response()->json('error');
        return response()->json(null);
    }
}
