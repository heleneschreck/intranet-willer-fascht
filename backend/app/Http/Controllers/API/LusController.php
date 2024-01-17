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


    public function getLusByUserAndConversation($participants_id, $conversation_id)
    {

        $count = Lus::where('participants_id', $participants_id)->where('conversation_id', $conversation_id)
        ->where('Lu', 0) // Ajoutez cette condition pour filtrer les objets avec Lu = 0
        ->count();

    $lusData = [
        'count' => $count
    ];

    return response()->json($lusData);
    //     $lus = lus::where('participants_id', $participants_id)->get();
    //     $count = $lus->count();
    //     $lusData = [
    //         'count' => $count
    //     ];
    //     return response()->json($lusData);
    }

    public function getCountByParticipant($participants_id)
    {
        $count = Lus::where('participants_id', $participants_id)
            ->where('Lu', 0) // Ajoutez cette condition pour filtrer les objets avec Lu = 0
            ->count();

        $lusData = [
            'count' => $count
        ];

        return response()->json($lusData);
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
 * Update all resources with the same participants_id, conversation_id, and Lu value.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  int  $participants_id
 * @param  int  $conversation_id
 * @param  int  $Lu
 * @return \Illuminate\Http\Response
 */
public function updateByParticipantAndConversation(Request $request, $participants_id, $conversation_id, $Lu)
{
    // Validate the request data if needed
    // $this->validate($request, [
    //     'your_validation_rules_here',
    // ]);

    // Update all records that match the criteria
    Lus::where('participants_id', $participants_id)
        ->where('conversation_id', $conversation_id)
        ->where('Lu', $Lu)
        ->update($request->all());

    // Optionally, you can return a response if needed
    return response()->json(['message' => 'Records updated successfully']);
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
