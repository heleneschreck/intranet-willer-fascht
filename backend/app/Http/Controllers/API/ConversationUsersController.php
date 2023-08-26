<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Conversation_Users;
use Illuminate\Http\Request;

class ConversationUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $conversation_Users = Conversation_Users::all();
        return response()->json($conversation_Users);
    }

  /**
     * Get the participants by user_id.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function getMessagesByConversations($conversation_id)
    {
        $conversation = Conversation_Users::where('conversation_id', $conversation_id)->get();
        return response()->json($conversation);
    }
  /**
     * Get the participants by user_id.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function getMessagesByUser($users_id)
    {
        $conversation = Conversation_Users::where('users_id', $users_id)->get();
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
            'users_id' => 'required',
            'conversation_id' => 'required',

        ]);
        $conversation_Users = Conversation_Users::create([
            'users_id' => $request->users_id,
            'conversation_id' => $request->conversation_id,
        ]);
        return response()->json($conversation_Users, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Conversation_Users  $conversation_Users
     * @return \Illuminate\Http\Response
     */
    public function show(Conversation_Users $conversation_Users)
    {
        return response()->json($conversation_Users);

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Conversation_Users  $conversation_Users
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $conversation_Users = Conversation_Users::findOrFail($id);
        if ($conversation_Users)
        $conversation_Users->delete();

        else
            return response()->json('error');
        return response()->json(null);
    }
}
