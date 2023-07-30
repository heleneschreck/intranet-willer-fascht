<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Noparticipants;
use Illuminate\Http\Request;

class NoparticpantsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $noparticipants = Noparticipants::all();
        return response()->json($noparticipants);
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
            'noparticipants_id' =>'required',
            'user_id' =>'required',
            'rendezvous_id' =>'required',

        ]);
            $noparticipants = Noparticipants::create([
            'noparticipants_id' => $request['participants_id'],
            'user_id' =>$request->noparticipants_id,
            'rendezvous_id' =>$request-> rendezvous_id,
            ]);
            return response()->json($noparticipants, 201);
    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Noparticipants  $noparticipants
     * @return \Illuminate\Http\Response
     */
    public function show(Noparticipants $noparticipants)
    {
        return response()->json($noparticipants);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Noparticipants  $noparticipants
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $noparticipants = Noparticipants::find($id);
        $noparticipants->update($request->all());
        return $noparticipants;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Noparticipants  $noparticipants
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $noparticipants = Noparticipants::findOrFail($id);
        if($noparticipants)
        $noparticipants->delete();
        else
        return response()->json('error');
        return response()->json(null);
    }
}
