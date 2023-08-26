<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Messages;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messages = Messages::all();
        return response()->json($messages);
    }


    /**
     * Get the participants by user_id.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function getMessageByConversation($conversation_id)
    {
        $messages = Messages::where('conversation_id', $conversation_id)->get();
        return response()->json($messages);
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
            'conversation_id' => 'required',
            'message' => 'required',
            'Lu' => 'required',

        ]);
        $conversation = Messages::create([
            'user_id' => $request->user_id,
            'conversation_id' => $request->conversation_id,
            'message' => $request->message,
            'Lu' => $request->Lu,
        ]);
        return response()->json($conversation, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Messages  $messages
     * @return \Illuminate\Http\Response
     */
    public function show(Messages $messages)
    {
        return response()->json($messages);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Messages  $messages
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Messages $messages)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Messages  $messages
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        

        $messages = Messages::findOrFail($id);
        if ($messages)
            $messages->delete();

        else
            return response()->json('error');
        return response()->json(null);
    }
}
