<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Tache;
use Illuminate\Http\Request;

class TacheController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tache = Tache::all();
        return response()->json($tache);
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
            'end'=>'required',
            'status_id'=>'required',
            'user_id' => 'required',
            'project_id' => 'required',         
        ]);
        $tache = Tache::create([
            'title'=> $request->title,
            'end'=> $request->end,
            'status_id'=> $request->status_id,
            'user_id' => $request->user_id,
            'project_id' => $request->project_id,
        ]);
        return response()->json($tache, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tache  $tache
     * @return \Illuminate\Http\Response
     */
    public function show(Tache $tache)
    {
        return response()->json($tache);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tache  $tache
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tache = Tache::find($id);
        $tache->update($request->all());
        return $tache;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tache  $tache
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tache = Tache::findOrFail($id);
        if ($tache)
        $tache->delete();
         else
            return response()->json('error');
        return response()->json(null);  
    
    }

}
