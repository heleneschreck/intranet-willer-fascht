<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $conversations = Conversation::latest()->get();
        return response()->json($conversations);
    }
    
     /**
     * Get the participants by user_id.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function getConversationByCreateur($user_id)
    {
        $conversation = Conversation::where('user_id', $user_id)->get();
        return response()->json($conversation);
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

        ]);
        $conversation = Conversation::create([
            'user_id' => $request->user_id,
        ]);
        return response()->json($conversation, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Conversation  $conversation
     * @return \Illuminate\Http\Response
     */
    public function show(Conversation $conversation)
    {
        return response()->json($conversation);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Conversation  $conversation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Conversation $conversation)
    {
        $conversation->delete();
        return response()->json();
    }
}
