<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Soustaches;
use Illuminate\Http\Request;

class SoustachesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $soustaches = Soustaches::all();
        return response()->json($soustaches);
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
            'title' => 'required',
            'isvalidate'=>'required',
            'tache_id' => 'required',         
        ]);
        $soustaches = Soustaches::create([
            'title'=> $request->title,
            'isvalidate'=>$request->isvalidate,
            'tache_id' => $request->tache_id,
        ]);
        return response()->json($soustaches, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Soustaches  $soustaches
     * @return \Illuminate\Http\Response
     */
    public function show(Soustaches $soustaches)
    {
        return response()->json($soustaches);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Soustaches  $soustaches
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $soustaches = Soustaches::find($id);
        $soustaches->update($request-> all());
        return $soustaches;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Soustaches  $soustaches
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $soustaches = Soustaches::findOrFail($id);
        if ($soustaches)
        $soustaches->delete();
         else
            return response()->json('error');
        return response()->json(null); 
    }
}
