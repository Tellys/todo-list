<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        if(count($itenDuplicate = User::where(['email' => $request->email])->get())){
            return $this->sendError('Cadastro duplicado! Já existe o cadastro '.$request->email, $itenDuplicate, 409);
        }

        try {
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['name'] =  $user->name;

            return $this->sendResponse($success, 'User register successfully.', 201);

        } catch (\Illuminate\Database\QueryException $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 409);
        }
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('authToken')->plainTextToken;
            $success['name'] =  $user->name;

            return $this->sendResponse($success, 'Usuario autenticado com sucesso.');
        }
        else{
            return $this->sendError('Usuário ou senha incorretos ou não autorizados', ['error'=>'Unauthorised'], 401);
        }
    }

    public function searchBy($array)
    {
        return User::where($array)->get();
    }

    /**
    * Logout user (Revoke the token)
    *
    * @return [string] message
    */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        $request->session()->flush();

        return response()->json([
        'message' => 'Successfully logged out'
        ]);

    }
}
