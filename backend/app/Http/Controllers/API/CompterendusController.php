<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Compterendu;
use Illuminate\Http\Request;

class CompterendusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $compterendu = Compterendu::all();
        return response()->json($compterendu);
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
            'date' => 'required',
            'participants' => 'required',
            'contenus' => 'required',
        ]);
        $compterendu = Compterendu::create([
            'date' => $request->date,
            'participants' => $request->participants,
            'contenus' => $request->contenus
        ]);
        return response()->json($compterendu, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Compterendu  $compterendu
     * @return \Illuminate\Http\Response
     */
    public function show(Compterendu $compterendu)
    {
        return response()->json($compterendu);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Compterendu  $compterendu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $compterendu = Compterendu::find($id);
        $compterendu->update($request->all());
        return $compterendu;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Compterendu  $compterendu
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $compterendu = Compterendu::findOrFail($id);
        if ($compterendu)
            $compterendu->delete();
        else
            return response()->json('error');
        return response()->json(null);
    }
}
