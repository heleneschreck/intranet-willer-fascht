<?php

namespace App\Http\Controllers\API;
use App\Models\User;
use App\Models\Profils;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return User
     */
    public function createUser(Request $request)
    {
        try {
            // valider
            $validateUser = Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'prenom' => 'required',
                    'telephone' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required',
                    'niveau' => 'required'

                ]
            );
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validations error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            $user = User::create([
                'name' => $request->name,
                'prenom' => $request->prenom,
                'telephone' => $request->telephone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'niveau' => $request->niveau
            ]);

   $profils = Profils::create([
            'image' => 'https://images.unsplash.com/photo-1605136292546-36994b3f5b59?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1174&q=80',
            'user_id' => $user->id
        ]);
     
   
            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'id' => $user->id,
                'name' => $user->name,
                'prenom' => $user->prenom,
                'telephone' => $user->telephone,
                'email' => $user->email,
                'password' => $user->password,
                'niveau' => $user->niveau,  
                'id' => $profils->id, 
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
/**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request)
{
    try {
        $validateUser = Validator::make($request->all(),
        [
            'email' => 'required',
            'password' => 'required'

        ]);
        if($validateUser->fails()) {
            return response()->json([
                'status'=>false,
                'message' => 'validations error',
                'errors' => $validateUser->errors()
            ], 401);
        }
        if(!Auth::attempt($request->only(['email', 'password']))){
            return response()->json([
                'status'=>false,
                'message' => 'Email & Password does not match with our record.',
            ], 401);
        }
        $user = User::where('email', $request->email)->first();
    
        return response()->json([
            'status' => true,
            'message' => 'User logged Successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken,
            'id' => $user->id,
            'name' => $user->name,
            'prenom' => $user->prenom,
            'telephone' => $user->telephone,
            'email' => $user->email,
            'password' => $user->password,
            'niveau' => $user->niveau,
        
        
        ], 200);
    
    
    } catch (\Throwable $th) {
        return response()->json([
            'status'=>false,
            'message' => $th -> getMessage(),
        ], 500);   
    }
}

}
