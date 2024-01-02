<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Candidats;
use Illuminate\Http\Request;

class CandidatsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $candidats = Candidats::all();
        return response()->json($candidats);
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
            'nom' => 'required',
            'prenom' => 'required',
            'telephone' => 'required',
            'email' => 'required',
            'vu' => 'required',
        ]);
        $candidats = Candidats::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'vu' => $request->vu
        ]);
        return response()->json($candidats, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Candidats  $candidats
     * @return \Illuminate\Http\Response
     */
    public function show(Candidats $candidats)
    {
        return response()->json($candidats);
    }

    public function getCandidatsBySee($vu)
    {
        $candidats = Candidats::where('vu', $vu)->get();
        $count = $candidats->count();
        $candidatsData = [
          'count' => $count
        ];
        return response()->json($candidatsData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Candidats  $candidats
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $candidats = Candidats::find($id);
        $candidats->update($request->all());
        return $candidats;
    }

    public function destroy($id)
    {
        // $candidats = Candidats::findOrFail($id);
        // if ($candidats)
        //     $candidats->delete();
        // else
        //     return response()->json('error');
        // return response()->json(null);
        $candidats = Candidats::find($id);

        if (!$candidats) {
            return response()->json(['error' => 'Candidat non trouvé.'], 404);
        }
    
        $candidats->delete();
    
        return response()->json(['message' => 'Candidat supprimé avec succès']);
    }



    /**
 * Update 'vu' field for multiple resources.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  string  $vu
 * @return \Illuminate\Http\Response
 */
public function updateByVu(Request $request, $vu)
{

   $this->validate($request, [
        'new_vu' => 'required', // Validation du nouveau champ 'vu'
    ]);

    // Mettez à jour tous les utilisateurs ayant la même valeur sur l'entrée 'vu'
    Candidats::where('vu', $vu)->update(['vu' => $request->new_vu]);

    return response()->json(['message' => 'Utilisateurs mis à jour avec succès']);
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Candidats  $candidats
     * @return \Illuminate\Http\Response
     */
}
