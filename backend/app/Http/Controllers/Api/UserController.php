<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\RequestFrm;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class UserController extends BaseController
{
    use HasApiTokens, HasFactory, Notifiable;

    //https://www.geonames.org/
    //csrf_token()
    //$('meta[name="csrf-token"]').attr('content')

    public $tokenED;


    public function __construct()
    {
        $this->Model = new User();

        $this->data['campos'] = $this->Model->campos;
        $this->NamefolderImage = 'user';
        $this->PathsView = 'user';
        $this->PathsRoute = 'user';
        $this->ClassModel = 'User';
        $this->listVars['meta'] = [
            'title' => 'Lista de Usuarios',
            'h1' => 'Lista de Usuarios',
        ];
        $this->listVars['uriBase'] = 'user/';
        $this->listVars['heads'] = [
            'name',
            'email',
            'users_level_id',
            'name',
            'birthday',
            'email',
            'email_verified_at',
            'state',
            'city',
            'zip_code',
            'phone',
            'cell_phone',
            'created_at',
        ];

        $this->comandos = [
            //'show'=>[$this->PathsRoute.'.show', 'user_id'],
            'edit' => [$this->PathsRoute . '.edit', 'id'],
            'destroy' => [$this->PathsRoute . '.destroy', 'id'],
            'forceDelete' => [$this->PathsRoute . '.forceDelete', 'id'],
            'restore' => [$this->PathsRoute . '.restore', 'id']
        ];

        $this->tokenED = NOW()->addMinutes(config('sanctum.expiration'));
    }

    public function show($id)
    {
        //chama a função para acrescimo da restrição das permissões, se for o caso.
        $isRestrict = $this->isRestrict();

        try {
            $r = $this->Model
                ->where('id', $id)
                ->orWhere('cpf', $id)
                ->firstOrFail();

            //$r['configSistems'] = ConfigSistem::all();
            $this->message = 'Item retornato com sucesso';
            $this->status = 200;
            if (!$r) {
                $this->message = 'Item não localizado ou não existente em nosso banco de dados';
            }
            $r['cnae'] = $r->userCnae;
            return $this->sendResponse($r, $this->message, $this->status);
        } catch (\Exception $e) {
            $this->status = 409;
            $this->message = $e->getMessage();
        }

        return $this->sendError( $this->message, $this->status);
    }

    public function profile()
    {
        //chama a função para acrescimo da restrição das permissões, se for o caso.
        $this->isRestrict();

        try {
            $user = auth('sanctum')->user();

            $r = $this->Model
                ->where('id', $user->id)
                ->firstOrFail();

            //$r['configSistems'] = ConfigSistem::all();
            $this->message = 'Item retornato com sucesso';
            $this->status = 200;
            if (!$r) {
                $this->message = 'Item não localizado ou não existente em nosso banco de dados';
            }
            $r['cnae'] = $r->userCnae;
            return $this->sendResponse($r, $this->message, $this->status);
        } catch (\Exception $e) {
            $this->status = 409;
            $this->message = $e->getMessage();
        }

        return $this->sendError( $this->message, $this->status);
    }
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(RequestFrm $request)
    {
        //for a auto login by social media
        $credentials = ['email' => $request->email, 'password' => $request->password ?? $request->email . $request->name . 'laravel'];

        if (Auth::attempt($credentials)) {
            $user = auth()->user();

            $user['dataToken'] = $this->setDataToken($user);

            $this->hasVerifiedEmail($user);

            return $this->sendResponse($user, 'Usuário Logado com Sucesso');
        } else {
            return $this->sendError('Unauthorised', 'Usuário ou senha incorretos ou não autorizados', 401);
        }
    }

    function hasVerifiedEmail($user)
    {
        //return $user->hasVerifiedEmail() or $user->sendEmailVerificationNotification();
    }

    /**
     * Refresh Token api
     *
     * @return \Illuminate\Http\Response
     */
    public function refreshToken()
    {
        if ($checkLogin = $this->checkLogin()->status() !== 200) {
            return $this->sendError('Usuário não logado', 'Unauthorised', 401);
        }

        try {
            $user = auth('sanctum')->user();

            auth('sanctum')->user()->tokens()->delete();

            $user['dataToken'] = $this->setDataToken($user);

            if ($user['dataToken']['token']) {
                //$r['name'] =  $user->name;

                $this->message = 'Login Revalidado com Sucesso!';
                $this->status = 200;
                return $this->sendResponse($user, $this->message, $this->status);
            }

            $this->message = 'Login: Erro ao Revalidar';
            $this->status = 498;
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
        }

        return $this->sendError( $this->message, $this->status);
    }

    function setDataToken($user)
    {

        $r['token'] = $user->createToken(
            'authToken',
            ['*'],
            $this->tokenED
            //now()->addMinutes(config('sanctum.expiration'))
        )->plainTextToken;

        $r['expires_at'] = $this->tokenED;

        //$user['dataToken'] = $this->userToToken('Bearer' . $user['token']) ?? ['expires_at' => $this->tokenED];

        return $r;
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
    public function logout()
    {
        $this->message = 'O sistema se comportou de maneira estranha. Tente novamente';

        try {
            $r = false;
            $this->status = 200;
            //$this->status = 498;
            $this->message = 'Usuário não Logado';

            if (!empty(auth('sanctum')->user())) {
                $r = auth('sanctum')->user()->tokens()->delete();
                $this->message = 'Até Logo! Volte Sempre!';
            }

            return $this->sendResponse($r, $this->message, $this->status);
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
        }

        return $this->sendError( $this->message, $this->status);
    }

    protected function _registerOrLoginUser($data)
    {
        $user = User::where('email', $data->email)->first();
        if (!$user) {
            $user = new User();
            $user->name = $data->name;
            $user->email = $data->email;
            $user->provider_id = $data->id;
            $user->avatar = $data->avatar;
            $user->save();
        }
        Auth::login($user);
    }

    public function saveForAuthSocialMedia(RequestFrm $request)
    {
        //$isRestrict = $this->isRestrict();

        try {
            $r = $request->all(); //$request->except();

            $r['email_verified_at'] = now();
            $r['type_login'] = $request->type_login ?? null;
            $r['password'] = bcrypt($request->email . $request->name . 'laravel');

            $r['image'] = $request->uploadFile(
                [
                    'file' => $request->picture,
                    'path' => 'uploads' . '/' . $this->NamefolderImage,
                ],
                'pathFile',
                //false

            );

            $r['deleted_at'] = null; // for restore trash case true

            $r = $this->Model::withTrashed()->updateOrCreate(
                ['email' => $request->email],
                $r
            );

            return $this->login($request);
        } catch (\Throwable $e) {
            $this->status = 409;
            $this->message = $e->getMessage();
        }

        return $this->sendError( $this->message, $this->status);
    }

    function createSimple()
    {
        //chama a função para acrescimo da restrição das permissões, se for o caso.
        $this->isRestrict();

        $this->data['campos'] = [
            //'users_level_id' => $this->data['campos']['users_level_id'],
            'name' => $this->data['campos']['name'],
            'email' => $this->data['campos']['email'],
            'cell_phone' => $this->data['campos']['cell_phone'],
            'password' => $this->data['campos']['password'],
            'redirect' => [
                'html' => [
                    'fieldType' => 'hidden',
                    'label' => 'redirect',
                ],
                'db' => [
                    'type' => 'string',
                    'default' => '/success',
                ],

            ],
        ];

        $this->data['campos']['users_level_id']['html']['fieldType'] = 'hidden';
        $this->data['campos']['password']['html']['validation'] = ['required', 'min:8'];

        return $this->mySchema();
    }

    function forgotPasswordGet()
    {
        $this->isRestrict();

        $this->data['campos'] = [
            'email' => $this->data['campos']['email'],
            'redirect' => [
                'html' => [
                    'fieldType' => 'hidden',
                    'label' => 'redirect',
                ],
                'db' => [
                    'type' => 'string',
                    'default' => '/success',
                ],

            ],
        ];

        $this->data['campos']['email']['html']['validation'] = ['required', 'min:8'];

        //$this->data['campos'] = $campos;

        return $this->mySchema();
    }

    function forgotPassword(RequestFrm $request)
    {
        $this->isRestrict();

        try {
            $request->validate(['email' => 'required|email']);

            //$this->broker()->sendResetLink($request->only('email'));

            if (Password::sendResetLink($request->only('email')) === Password::RESET_LINK_SENT) {
                $this->message = 'Verifique seu e-mail, enviamos instruções para nova senha!';
                $this->status = 200;
                return $this->sendResponse('success', $this->message, $this->status);
            }

            return $this->sendError( 'Ocorreu um erro desconhecido, tente novamente!', 400);
        } catch (\Exception $e) {
            $this->message = $e;
        }

        return $this->sendError( $this->message, $this->status);
    }

    protected function broker()
    {
        return Password::broker();
    }

    function resetPasswordGet()
    {
        //chama a função para acrescimo da restrição das permissões, se for o caso.
        $this->isRestrict();

        $this->data['campos'] = [
            'password' => $this->data['campos']['password'],
            'password_confirmation' => [
                'html' => [
                    'fieldType' => 'password',
                    'label' => 'Confirme a sua senha',
                ],
                'db' => [
                    'type' => 'string',
                    'default' => '/success',
                ],

            ],
            'redirect' => [
                'html' => [
                    'fieldType' => 'hidden',
                    'label' => 'redirect',
                ],
                'db' => [
                    'type' => 'string',
                    'default' => '/success',
                ],

            ],
        ];

        $this->data['campos']['password']['html']['validation'] = ['required', 'min:8', 'confirmed'];

        //$this->data['campos'] = $campos;

        return $this->mySchema();
    }

    public function resetPassword(RequestFrm $request)
    {
        try {
            $this->message = 'Erro ao modificad sua senha, tente novamente!';
            $this->status = 400;

            $request->merge(['token' => $request->route('token'), 'email' => base64_decode($request->route('email'))]);

            $request->validate([
                'token' => 'required',
                'email' => 'required',
                'password' => 'required|confirmed|min:8',
            ]);

            $response = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function (User $user, string $password) {
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ])->setRememberToken(Str::random(60));

                    $user->save();

                    event(new PasswordReset($user));
                }
            );

            if ($response == Password::PASSWORD_RESET) {
                $this->message = 'Senha modificada com sucesso';
                $this->status = 200;
                return $this->sendResponse('success', $this->message, $this->status);
            }

            return $this->sendError( 'Ocorreu um erro desconhecido, tente novamente!', 400);
        } catch (\Exception $e) {
            $this->message = $e;
        }

        return $this->sendError( $this->message, $this->status);
    }
}
