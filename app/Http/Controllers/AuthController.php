<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Jornalista;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function register(Request $request){

        $validator = Validator::make($request->all(),[
            'nome' =>'required|min:4|max:255',
            'sobrenome' =>'required|min:4|max:255',
            'email' =>'required|email|unique:jornalistas',
            'password' => 'required'

        ]);

        if($validator->fails()) {
            $error = $validator->errors();

            return response()->json($error, 400);
        } else { 

            $jornalista = Jornalista::create([
                'nome' => $request->input('nome'),
                'sobrenome'=> $request->input('sobrenome'),
                'email'=> $request->input('email'),
                'password' => bcrypt($request->input('password'))
            ]);

            $token = Auth::login($jornalista);
            return response()->json([
                'status' => 'sucesso',
                'message' => 'Jornalista criado com sucesso',
                'user' => $jornalista,
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        }
    }


    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());



        

    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 5
        ]);
    }
}