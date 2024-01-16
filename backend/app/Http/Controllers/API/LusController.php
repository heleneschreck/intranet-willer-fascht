<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Lus;
use Illuminate\Http\Request;

class LusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lus = lus::all();
        return response()->json($lus);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
          // La validation de données
          $this->validate($request, [
            'participants_id' => 'required|max:100',
            'message_id' => 'required|max:100',
            'conversation_id' => 'required|max:100',
            'Lu' => 'required|max:100',
            
        ]);

        // On crée un nouvel utilisateur
        $user = Lus::create([
            'participants_id' => $request->participants_id,
            'message_id' => $request->message_id,
            'conversation_id' => $request->conversation_id,
            'Lu' => $request->Lu,
          
        ]);
       
        // On retourne les informations du nouvel utilisateur en JSON
        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lus  $lus
     * @return \Illuminate\Http\Response
     */
    public function show(Lus $lus)
    {
        return response()->json($lus, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lus  $lus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $lus = Lus::find($id);
        $lus->update($request->all());
        return $lus;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lus  $lus
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lus = Lus::findOrFail($id);
        if ($lus)
            $lus->delete();

        else
            return response()->json('error');
        return response()->json(null);
    }
}
