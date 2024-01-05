<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Articles;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Articles::all();

        return response()->json($articles);
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
            'content' => 'required',
            'color' => 'required',
            'size' => 'required',
            'weight' => 'required',
            'italique' => 'required',
            'user_id' => 'required',
        ]);
        $articles = Articles::create([
            'title' => $request->title,
            'content' => $request->content,
            'color' => $request->color,
            'size' => $request->size,
            'weight' => $request->weight,
            'italique' => $request->italique,
            'user_id' => $request->user_id,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Articles  $articles
     * @return \Illuminate\Http\Response
     */
    public function show(Articles $articles)
    {
        return response()->json($articles);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Articles  $articles
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $articles = Articles::find($id);
        $articles->update($request->all());
        return $articles;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Articles  $articles
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $articles = Articles::findOrFail($id);
        if ($articles)
        $articles->delete();
     
        else
            return response()->json('error');
        return response()->json(null);

    }
}
