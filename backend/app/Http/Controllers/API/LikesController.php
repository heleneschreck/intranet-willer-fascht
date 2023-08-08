<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Likes;
use Illuminate\Http\Request;

class LikesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $likes = Likes::all();
        return response()->json($likes);
    }
    /**
     * Get the participants by image_id.
     *
     * @param  int  $image_id
     * @return \Illuminate\Http\Response
     */
    public function getLikesByImage($image_id)
    {
        $likes = Likes::where('image_id', $image_id)->get();
        $count = $likes->count();

        $likesData = [
            'likes' => $likes,
            'count' => $count,
        ];

        return response()->json($likesData);
    }
    public function getLikesByUserAndImage($user_id, $image_id)
    {
        $likes = Likes::where('user_id', $user_id)->where('image_id', $image_id)->get();

        return response()->json($likes);
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
            'user_id' => 'required',
            'image_id' => 'required',

        ]);
        $likes = Likes::create([
            'user_id' => $request->user_id,
            'image_id' => $request->image_id,

        ]);
        return response()->json($likes, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Likes  $likes
     * @return \Illuminate\Http\Response
     */
    public function show(Likes $likes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Likes  $likes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Likes $likes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Likes  $likes
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $likes = Likes::findOrFail($id);
        if ($likes)
            $likes->delete();

        else
            return response()->json('error');
        return response()->json(null);
    }
}
