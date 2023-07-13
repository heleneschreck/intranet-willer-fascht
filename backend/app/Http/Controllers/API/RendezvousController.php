<?php

namespace App\Http\Controllers\API;

use App\Models\Rendezvous;
use App\Models\participants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RendezvousController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rendezvous = Rendezvous::all();

        //$rendezvous = $rendezvous->sortByDesc('start');
        return response()->json($rendezvous);
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
            'start' => 'required',
            'end' => 'required',
            'title' => 'required',
            'description' => 'required',
            'backgroundcolor' => 'required',
        ]);
        $rendezvous = Rendezvous::create([
            'start'=> $request->start,
            'end'=> $request->end,
            'title'=> $request->title,
            'description'=> $request->description,
            'backgroundcolor'=> $request->backgroundcolor,

        ]);

        $participants = Participants::create([
            'participants_id' => '',
            'noparticipants_id' =>'',
            'rendezvous_id' =>$rendezvous->id,
            ]);
        return response()->json([
            'id' => $rendezvous->id,
            'id_participants' => $participants->id,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rendezvous  $rendezvous
     * @return \Illuminate\Http\Response
     */
    public function show(Rendezvous $rendezvous)
    {
        return response()->json($rendezvous);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rendezvous  $rendezvous
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    //     $user = User::find($id);
        // $user->update($request->all());
        // return $user;
        $rendezvous = Rendezvous::find($id);
        $rendezvous->update($request->all());
        return $rendezvous;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rendezvous  $rendezvous
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rendezvous = Rendezvous::findOrFail($id);
        if ($rendezvous)
        $rendezvous->delete();
     
        else
            return response()->json('error');
        return response()->json(null);

    
    }
}
