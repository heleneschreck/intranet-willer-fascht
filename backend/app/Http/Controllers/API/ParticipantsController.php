<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Participants;
use Illuminate\Http\Request;
use App\Models\User;
class ParticipantsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $participants = Participants::all();
        return response()->json($participants);
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
            'participants_id' =>'required',
            'user_id' =>'required',
            'complement' =>'required',
            'rendezvous_id' =>'required',

        ]);
            $participants = Participants::create([
            'participants_id' => $request ->participants_id,
            'user_id' =>$request->user_id,
            'complement' =>$request->complement,
            'rendezvous_id' =>$request-> rendezvous_id,
            ]);
            return response()->json($participants, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Participants  $participants
     * @return \Illuminate\Http\Response
     */
    public function show(Participants $participants)
    {
        return response()->json($participants);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Participants  $participants
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $participants = Participants::find($id);
        $participants->update($request->all());
        return $participants;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Participants  $participants
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $participants = Participants::findOrFail($id);
        if($participants)
        $participants->delete();
        else
        return response()->json('error');
        return response()->json(null);
    }
}
