<?php

namespace App\Http\Controllers\Api;

use App\Classes\NossaClasse;
use App\Classes\MyResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\RequestFrm;
use App\Models\Slug;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Str;

class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $Model;
    public $data;
    public $ClassModel;
    public $NamefolderImage;
    public $PathsView;
    public $PathsRoute;
    public $listVars = [
        'meta' => [
            'title' => 'Lista Titulo Default',
            'h1' => 'H1 Default',
        ],
        'heads' => [
            'title',
            'name',
            'user_id',
            'published_at',
            'created_at',
            'updated_at',
        ]
    ];

    public $searchParams;

    public $comandos = [];

    public $tableBuild;

    public $message = 'O sistema se comportou de maneira estranha. Tente novamente!';
    public $status = 500;
    public $return = null;
    public string $typeOfReturn = 'json';

    // ganhar valor em Permissões.php quando a permissão é 'restrict'
    public $setIsRestrict = true;

    // for a set current CustomerRequest
    public $currentCustomerRequest = null;

    ///
    public function __construct()
    {
        $this->comandos = [
            //'show'=>[$this->PathsRoute.'.show', 'user_id'],
            'edit' => [$this->PathsRoute . '.edit', 'id'],
            'destroy' => [$this->PathsRoute . '.destroy', 'id'],
            'forceDelete' => [$this->PathsRoute . '.forceDelete', 'id'],
            'restore' => [$this->PathsRoute . '.restore', 'id']
        ];
    }

    function mySchema($data = [])
    {
        try {
            $r = $this->data['campos'];

            $formVueRefect = new \App\Classes\FormVueRefect();
            $r = $formVueRefect->mountSchema($r, $data);

            $btns = [
                'submit' =>
                [
                    'type' => 'button',
                    'buttonLabel' => 'Salvar',
                    'submits' => true
                ]
                // ] +
                //     [
                //         'reset' =>
                //         [
                //             'type' => 'button',
                //             'buttonLabel' => 'Cancelar',
                //             'secondary' => true,
                //             'resets' => true,
                //             'id'=>'btnDynamicFormCancel',
                //         ]
            ];

            if (request()->steps) {

                $btns = [];
                $countR = count($r);
                $media = round(count($r) / request()->steps, 0, PHP_ROUND_HALF_DOWN);

                $returnSteps = [];
                $kSteps = 0;
                $i = 0;
                $n = $media; //request()->steps;
                while ($i <= $countR) {
                    $stepsKey = 'page' . $kSteps;
                    $returnSteps[$stepsKey] = [
                        //'label'=> $stepsKey,
                        'elements' => array_keys(array_slice($r, $i, $n, true)),
                        'labels' => [
                            'previous' => 'Anterior',
                            'next' => 'Próximo'
                        ]
                    ];

                    $i += $n;
                    $kSteps++;
                }

                // retira os btn 'labels' do ultimo step
                unset($returnSteps[array_key_last($returnSteps)]['labels']['next']);
                //$returnSteps[array_key_last($returnSteps)]['labels']['next']['finish'] = 'Finalizar';

                // inseri os btn 'submit' no ultimo step
                //$returnSteps[array_key_last($returnSteps)]['elements'] = array_merge($returnSteps[array_key_last($returnSteps)]['elements'] , array_keys($btns));

                $return['steps'] = $returnSteps;
            }

            $return['schema'] = $r + $btns;

            //dd($return);

            return $this->sendResponse($return, 'sucesso', 200);
        } catch (\Exception $e) {
            $this->message = $e;
            $this->status = $e; //409;
        }

        return $this->sendError($this->message, $this->status);
    }

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message, $status = 200)
    {
        return (new MyResponse())->sendResponse($result, $message, $status);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($errorMessages, $status = 404)
    {
        return (new MyResponse())->sendError($errorMessages, $status);
    }

    /**
     * Verifica a existência do token de autorização de acesso do usuário logado no sistema
     * @return string|null
     */
    public function userToToken(string $key = null)
    {
        try {
            //$token = !empty(getallheaders()['Authorization']) ? (string) getallheaders()['Authorization'] : $key;
            $token = $key ?? (string) getallheaders()['Authorization'];

            if (!$token) {
                return false;
            }

            [$id, $token] = explode('Bearer', $token, 2);

            $return  = PersonalAccessToken::findToken($token);
            //$return = $key ? $token->tokenable->{$key} : $token->tokenable;

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function checkLogin()
    {
        $user = auth('sanctum')->user();
        if (!empty($user->id)) {
            return $this->sendResponse($this->userToToken(), 'Check Login ok');
        }
        return $this->sendResponse(false, 'Check Login false');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        try {
            //chama a função para acrescimo da restrição das permissões, se for o caso.
            $this->isRestrict();
            $this->message = 'Sucesso';
            $this->status = 200;

            $r = $this->Model->includeTablesRelations();

            if (request()->has('oderByColumn')) {
                $r = $r->orderBy(request()->oderByColumn, request()->oderBy ?? 'asc');
            }

            $r = $r->get();
            return $this->sendResponse($r, $this->message, $this->status);
        } catch (\Exception $e) {
            dd($e);
            $this->message = $e;
            $this->status = $e;
        }

        return $this->sendError($this->message, $this->status);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            //chama a função para acrescimo da restrição das permissões, se for o caso.
            $this->isRestrict();

            $this->message = 'Item não localizado ou não existente em nosso banco de dados';
            $success = false;

            $r = $this->Model->includeTablesRelations();

            if (request()->has('oderByColumn')) {
                $r = $r->orderBy(request()->oderByColumn, request()->oderBy ?? 'asc');
            }

            $r = $r->paginate(20);

            if ($r) {
                $this->message = 'Sucesso';
                $this->status = 200;
                $success = true;
            }

            $r->put('success', $success);

            $r['listVars'] = $this->listVars;
            $r['campos'] = $this->data['campos'] ?? [];

            return response()->json($r, $this->status);
            //return $this->sendResponse($r, $this->message, $this->status);
        } catch (\Exception $e) {
            dd($e);
            $this->message = $e;
            $this->status = $e;
        }

        return $this->sendError($this->message, $this->status);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //chama a função para acrescimo da restrição das permissões, se for o caso.
        $this->isRestrict();

        return $this->mySchema();
    }

    /**
     *
     */
    public function store(RequestFrm $request)
    {
        //chama a função para acrescimo da restrição das permissões, se for o caso.
        $this->isRestrict();

        $request->merge($this->myTraits($request));

        // validação dos dados
        $NossaClasse = new NossaClasse();
        $request->validate(
            $NossaClasse->colunasValidate($this->data['campos'])
        );

        try {

            $user_id = auth('sanctum')->user()->id;
            $request->merge(['user_id' => $user_id]);

            if (!empty($request->password)) {
                $request->merge(['password' => bcrypt($request->password)]);
            }

            //slug
            if ($request->has('slug')) {

                $slug = $request->slug;

                if (empty($request->slug)) {
                    $slug = url($this->createSlug($this->PathsRoute) . '/' . uniqid());
                }

                if (Slug::where('redirect', $slug)->first()) {
                    $slug .= '-' . uniqid();
                }

                $request->merge(['slug' => $slug]);
            }

            //$r = $request->except(['image', 'urlReturn', 'slug']);
            $r = $request->except(['urlReturn', 'slug']);

            $q = $this->Model->create($r);

            //slug
            if ($request->has('slug')) {
                Slug::create([
                    'name' => (string) route($this->PathsRoute . '.show', $q->id),
                    'redirect' => (string) $request->slug,
                    'user_id' => (int) $user_id,
                    //'module'=> (string) last(explode('\\', get_class($this))),
                    'module' => (string) get_class($this),
                    'module_id' => (int) $q->id,
                ]);
            }

            $this->return = $q;
            $this->status = 200;
            $this->message = 'Item CRIADO com sucesso!';
            return $this->returnSuccess();
        } catch (\Exception $e) {
            $this->message = $e; //$e->getMessage();
            $this->status = $e;
        }

        return $this->returnError();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mySlug($slug)
    {
        //chama a função para acrescimo da restrição das permissões, se for o caso.
        $this->isRestrict();

        try {
            $r = Slug::where('redirect', url()->to('/') . '/' . $slug)->firstOrFail(); //->first()->toArray()
            return (new $r->module())->show($r->module_id);
            if (!$r) {
                $this->message = 'Item não localizado ou não existente em nosso banco de dados';
                $this->status = 200;
                return $this->sendResponse($r, $this->message, $this->status);
            }
        } catch (\Exception $e) {
            $this->message = $e;
            'Erro ao pesquisar o item. Provavelmente não existe em nosso banco de dados.';
            $this->status = $e;
        }
        return $this->sendError($this->message, $this->status);
    }

    public function show($id)
    {
        //chama a função para acrescimo da restrição das permissões, se for o caso.
        $this->isRestrict();

        try {
            $r = $this->Model->where('id', $id)->firstOrFail();
            //$r['configSistems'] = ConfigSistem::all();
            $this->message = 'Item pesquisado com sucesso!';
            $this->status = 200;

            if (!$r) {
                $this->message = 'Item não localizado ou não existente em nosso banco de dados';
            }
            return $this->sendResponse($r, $this->message, $this->status);
        } catch (\Exception $e) {
            $this->status = $e;
            $this->message = $e;
        }

        return $this->sendError($this->message, $this->status);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //chama a função para acrescimo da restrição das permissões, se for o caso.
        try {
            $this->isRestrict();

            $r = $this->Model->includeTablesRelations();
            $r = $r->findOrFail($id)->toArray();

            if (!$r) {
                return throw new \Exception('Item não localizado ou não existente em nosso banco de dados', 409);
            }

            return $this->mySchema($r);
        } catch (\Exception $e) {
            $this->message = $e;
            $this->status = $e;
        }

        return $this->sendError($this->message, $this->status);
    }

    function myTraits($var)
    {
        $r = [];

        foreach ($this->data['campos'] as $k => $v) {

            switch (strtolower($v['html']['fieldType'])) {
                case 'file':
                    $keyFieldWhitFile[] = $k;
                    unset($this->data['campos'][$k]['html']['validation']);

                    //$fieldTypeFile[$k] = $var->$k;
                    $fieldTypeFile = $var->$k ?? null;

                    if ($var->has($k) and !empty($var->$k['data']['pathFile'])) {
                        $fieldTypeFile = $var->$k['data']['pathFile'];
                    }

                    if ($var->has($k) and is_string($var->$k) and str_contains($var->$k, 'storage/')) {
                        $fieldTypeFile = (explode('storage/', $var->$k))[1];
                    }

                    // if ($var->has($k) and is_array($var->$k) and !empty($var->$k['nameFile'])) {
                    //     $fieldTypeFile[$k] = $var->$k['nameFile'];
                    // }

                    //echo '<br>'. $var->has($k) .' - '. str_contains($fieldTypeFile[$k], 'storage/');
                    //print_r($fieldTypeFile);

                    //print_r($fieldTypeFile);

                    $r[$k] = $fieldTypeFile;

                    break;

                case 'checkbox':
                    if ($var->has($k) and substr($v['html']['options'], 0, 2) == 'db') {
                        //array_push($except, $k);
                        $vCheckbox = implode(',', $var->$k);
                        $vCheckbox = str_replace("'", '', $vCheckbox);
                        $vCheckbox = str_replace("'", '', $vCheckbox);

                        $r[$k] = $vCheckbox;
                    }

                    break;
            }
        }

        //dd('saiu');
        return $r;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RequestFrm $request, $id)
    {
        $id = $id ?? $request->id;

        //chama a função para acrescimo da restrição das permissões, se for o caso.
        $this->status = 200;
        $this->message = 'Item EDITADO com sucesso!';

        try {
            $this->isRestrict();
            $except = ['urlReturn', 'password'];

            $request->merge($this->myTraits($request));

            // validação dos dados
            $NossaClasse = new NossaClasse();
            $request->validate(
                $NossaClasse->colunasValidate($this->data['campos'], $id)
            );

            $user_id = auth('sanctum')->user()->id;
            $request->merge(['user_id' => $user_id]);

            $q = $this->Model->findOrFail($id);

            if (!empty($request->password)) {
                $q['password'] = bcrypt($request->password);
            }

            //increment
            if ($request->has('increment')) {
                $q->increment($request->increment);
                //return $this->sendResponse($q, $this->message, $this->status);
            }

            //decrement
            if ($request->has('decrement')) {
                $q->decrement($request->decrement);
                //return $this->sendResponse($q, $this->message, $this->status);
            }

            //slug
            if ($request->has('slug')) {

                if (empty($request->slug)) {
                    $request->merge(
                        [
                            'slug' => url(
                                $this->createSlug($this->PathsRoute)
                                    . '/' . $q->id
                            )
                        ]
                    );
                }

                if (!Slug::where('redirect', '=', $request->slug)->first()) {
                    Slug::create([
                        'name' => (string) route($this->PathsRoute . '.show', $q->id),
                        'redirect' => (string) $request->slug,
                        'user_id' => (int) $user_id,
                        //'module'=> (string) last(explode('\\', get_class($this))),
                        'module' => (string) get_class($this),
                        'module_id' => (int) $q->id,
                    ]);
                }
            }

            $r = $request->except($except);

            $q->update($r);

            return $this->sendResponse($q, $this->message, $this->status);
        } catch (\Exception $e) {
            $this->status = $e;
            $this->message = $e;
        }

        return $this->sendError($this->message, $this->status);
    }

    public function uploadTemporary(RequestFrm $request)
    {
        if ($request->file('image')) {
            $image =
                $request->uploadFile([
                    'file' => $request->file('image'),
                    'path' => 'uploads' . '/' . $this->NamefolderImage,
                ]);

            return $this->sendResponse($image, 'Arquivo enviado com sucesso', 200);
        }

        $this->status = 409;
        $this->message = 'Nenhum arquivo foi encontrado! Tente novamente';
        return $this->sendError($this->message, $this->status);
    }

    public function removeTemporary(RequestFrm $request)
    {
        if ($image = $request->removeFile([
            'file' => $request->image,
            'path' => 'uploads' . '/' . $this->NamefolderImage,
        ])) {

            return $this->sendResponse($image, 'Arquivo removido com sucesso', 200);
        }

        $this->status = 409;
        $this->message = 'O arquivo ja havia sido removido';
        return $this->sendError($this->message, $this->status);
    }

    /**
     *
     */
    public function list($var = null)
    {
        $a = [
            'comandos' => $this->comandos,
            $var,
        ];
        //return $this->sendResponse($this->TableBuild(array_merge($a, $this->listVars)), 'success', 200);
        return $this->sendResponse($this->TableBuild(), 'success', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //chama a função para acrescimo da restrição das permissões, se for o caso.
        $this->isRestrict();

        try {
            $q = $this->Model->where('id', $id);

            if (!$q->count()) {
                throw new \Exception('Ocorreu um erro, verifique se o item para LIXEIRA ou tente novamente. Ou vocÊ não tem essa permissão!');
            }

            //slug
            $qSlug = $q->get();
            if (!empty($qSlug->slug)) {
                Slug::where('slug', $qSlug->slug)->delete();
            }

            $q->delete();
            $this->status = 200;
            $this->message = 'Item para LIXEIRA com sucesso!';
            return $this->sendResponse('success', $this->message, $this->status);
        } catch (\Exception $e) {
            $this->status = $e; //409;
            $this->message = $e; //$e->getMessage();
        }
        return $this->sendError($this->message, $this->status);
    }

    /**
     *
     */
    public function restore(RequestFrm $request, $id)
    {
        //chama a função para acrescimo da restrição das permissões, se for o caso.
        $this->isRestrict();

        try {
            $r = $this->Model->withTrashed()->findOrFail($id);

            //slug
            if (!empty($r->slug)) {
                Slug::where('slug', $r->slug)->delete();
            }

            $r->restore();

            $this->status = 200;
            $this->message = 'Item RECICLADO com sucesso!';

            return $this->sendResponse($r, $this->message, $this->status);
        } catch (\Exception $e) {
            $this->status = $e; //409;
            $this->message = $e; //$e->getMessage();
        }

        return $this->sendError($this->message, $this->status);
    }

    /**
     * Restore all archived users
     *
     * @param User $user
     *
     * @return \Illuminate\Http\Response
     */
    /* public function restoreAll()
    {
        User::onlyTrashed()->restore();
    } */
    public function forceDelete(RequestFrm $request, $id)
    {
        //chama a função para acrescimo da restrição das permissões, se for o caso.
        $this->isRestrict();

        try {
            $q = $this->Model->withTrashed();

            if (is_numeric($request->id)) {
                $q = $q->where($request->collumn ?? 'id', $request->id);
                //$q = $q->findOrFail($request->id);
            } elseif (!empty($id)) {
                $q = $q->where($request->collumn ?? 'id', $id);
            }


            if (!$q->count()) {
                throw new \Exception('Nenhum item localizado');
            }

            $q2 = $q->firstOrFail();

            //image
            if (!empty($q2->image)) {
                File::delete(
                    (public_path() . str_replace(
                        '/',
                        '\\',
                        //($q->first())->image
                        $q2->image
                    )
                    )
                );
            }

            //slug
            if (!empty($q2->slug)) {
                Slug::where('redirect', $q2->slug)->delete();
            }

            $q->forceDelete();

            $this->status = 200;
            $this->message = 'Item REMOVIDO permanentemente!';

            return $this->sendResponse($q, $this->message, $this->status);
        } catch (\Exception $e) {
            $this->status = $e; //409;
            $this->message = $e; //$e->getMessage();
        }

        return $this->sendError($this->message, $this->status);
    }

    ///
    public static function distanceCalculation($point1_lat, $point1_long, $point2_lat, $point2_long, $unit = 'km', $decimals = 2)
    {
        $degrees = rad2deg(acos((sin(deg2rad($point1_lat)) * sin(deg2rad($point2_lat))) + (cos(deg2rad($point1_lat)) * cos(deg2rad($point2_lat)) * cos(deg2rad($point1_long - $point2_long)))));
        switch ($unit) {
            case 'km':
                $distance = $degrees * 111.13384;
                break;
            case 'mi':
                $distance = $degrees * 69.05482;
                break;
            case 'nmi':
                $distance =  $degrees * 59.97662;
        }
        return round($distance, $decimals);
    }

    ///
    function distancia($lat1, $lon1, $lat2, $lon2)
    {
        $lat1 = deg2rad($lat1);
        $lat2 = deg2rad($lat2);
        $lon1 = deg2rad($lon1);
        $lon2 = deg2rad($lon2);

        $dist = (6371 * acos(cos($lat1) * cos($lat2) * cos($lon2 - $lon1) + sin($lat1) * sin($lat2)));
        $dist = number_format($dist, 3, '.', '');
        return $dist;
    }

    function moveCoordinates($lat, $lon, $distance)
    {
        // Earth radius in meters
        $earthRadius = 6371000; // meters

        // Convert distance to radians
        $distance /= $earthRadius;

        // Convert current latitude and longitude to radians
        $lat = deg2rad($lat);
        $lon = deg2rad($lon);

        $r = [];
        foreach ([0, 90, 180, 270] as $k => $v) {
            $bearing = deg2rad($v);
            $r[$v]['lat'] = rad2deg(asin(sin($lat) * cos($distance) + cos($lat) * sin($distance) * cos($bearing)));
            $r[$v]['lng'] = rad2deg($lon + atan2(sin($bearing) * sin($distance) * cos($lat), cos($distance) - sin($lat) * sin($r[$v]['lat'])));
        }
        return $r;
    }

    function qq()
    {
        return DB::table('users')
            ->whereRaw(
                'ST_Distance_Sphere(point(lng, lat),point(-21.3796535, -45.5157768)) *.000621371192',
                '<=',
                1 //-- Less than one mile apart 
                //'<= 1609.34400061 // Less than one mile apart, in meters 
            )
            //->groupBy('status')
            ->get()->toArray();
        //->toSql();
    }

    //public function scopeDistanceSphere($query, $lat, $lng, $radiusInMeter = 100)
    public function distanceSphere($lat, $lng, $radiusInMeter = 100)
    {
        // Approx 1 meter in coordinates
        $offsetOneMeter = 0.000111;

        // Calculate radius with offset for the boundary box
        $radiusOffset = ($radiusInMeter * $offsetOneMeter) + $offsetOneMeter;

        // Set boundary box points
        $latOffsetMinus = $lat - $radiusOffset;
        $latOffsetPlus = $lat + $radiusOffset;
        $lngOffsetMinus = $lng - $radiusOffset;
        $lngOffsetPlus = $lng + $radiusOffset;

        return DB::table('users')
            ->whereBetween('lat', [$latOffsetMinus, $latOffsetPlus])
            ->whereBetween('lng', [$lngOffsetMinus, $lngOffsetPlus])
            //->whereRaw("ST_distance_sphere(point($lng, $lat),point(lng, lat)) > $radiusInMeter")
            ->toSql();
        //->get()->toArray();
    }

    public function migrate()
    {
        //chama a função para acrescimo da restrição das permissões, se for o caso.
        $this->isRestrict();
        $NossaClasse = new \App\Classes\NossaClasse();
        echo $NossaClasse->createMigratarions($this->data['campos']);
    }

    public function breadcrumb($var = null)
    {
        # code...
    }

    public function lazyLoad($r = null)
    {
        //chama a função para acrescimo da restrição das permissões, se for o caso.
        $this->isRestrict();

        if (empty($r)) {
            $r['data'] = $this->Model->latest()->paginate(10);
            $r['meta'] = $this->listVars['meta'];
        }

        if (request()->ajax()) {
            $view = view('components.public.imoveis-card')->with('r', $r)->render();
            return response()->json(['view' => $view, 'nextPageUrl' => $r['data']->nextPageUrl()]);
        }
        return view('public.' . $this->PathsView . '.lazyLoad')->with('r', $r);
    }

    //public function search($query = null)
    public function search(RequestFrm $request, $query = null)
    {
        //chama a função para acrescimo da restrição das permissões, se for o caso.
        $this->isRestrict();

        //try {

        //$r = $this->Model;
        $r = $this->Model->includeTablesRelations();

        if (!empty($query)) {

            //xss-input-sanitization
            $query = strip_tags($query);
            //$request->merge(['query'=>$query]);

            // $request->validate([
            //     'query' => 'nullable|string|max:255|min:1',
            // ]);

            $r = $r->where('id', $query);
            foreach ($this->searchParams as $k => $v) {
                $r = $r->orWhere($v, 'like', '%' . $query . '%');
            }
        }

        //dd($r->toSql());
        //dd($r->get()->toArray());

        $r = $r->latest()->paginate(10);
        //$r = $r->paginate(10);

        //$r = $r->latest()->get()->toArray();

        //dd($r);

        $this->message = 'Pesquisa realizada com sucesso';
        $this->status = 200;

        if (!$r) {
            $this->message = 'Item não localizado ou não existente em nosso banco de dados';
            return $this->sendError($this->message, $this->status);
        }

        // $r['configSistems'] = ConfigSistem::all();
        // $r['meta'] = [
        //     'title' => 'Pesquisando por: ' . $query,
        //     'h1' => 'Pesquisando por: ' . $query,
        //     'description' => 'Localizamos o total de: 0' . $r->total() . ' item(ns)',
        // ];

        //return response()->json($r, 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_INVALID_UTF8_IGNORE);

        return $this->sendResponse($r, $this->message, $this->status);
        /* } catch (\Exception $e) {
            //$this->message = 'Erro ao pesquisar o item. Provavelmente não existe em nosso banco de dados.';
            return $this->sendError($e, $e);
        } */
    }

    /**
     * Função para verificação de permissões do sistema
     * @return bool
     */
    public function isRestrict($tableName = null)
    {
        // se não set 'auth:sanctum' na route ai deixa passar
        if (!array_search('auth:sanctum', Route::current()->action['middleware'])) {
            return true; //return $p->autorize();
        }

        // por padrão = true
        if (!$this->setIsRestrict) {
            return true;
        }

        // se o middleware pedir a consulta de restrição
        $p = new Permissoes();
        $user = auth('sanctum')->user();

        // full return true
        if ($p->autorize()->action == 'full') {
            return true;
        }

        if (!$tableName and !method_exists($this->Model, 'getTable')) {
            $tableName = Str::plural(strtolower(Str::snake($this->ClassModel)));
        }

        //dd($this->Model->getTable(), $p->autorize()->action);
        //dd((string) get_class($this), $next($request), $request->all, $permissoes);

        if ($p->autorize()->action == 'restrict' and Schema::hasColumn($tableName ?? $this->Model->getTable(), 'user_id')) {

            // retorna os resultados com base no paramentro '$p->autorize()->param'
            if (!empty($p->autorize()->param)) {
                $param = \App\Models\UsersLevel::where('id', (explode(':', $p->autorize()->param))[1], $user->users_level_id);
                $this->Model = $this->Model->whereIn('user_id', array_values($param->get('id')->pluck('id')->toarray()));
                return true;
            }

            // retorna com a restrição de 'user_id'
            $this->Model = $this->Model->where('user_id', $user->id);
            return true;
        }

        return throw new \Exception('Você não tem a permissão necessária!', 401);
        //return false;
    }

    public static function createSlug($title, $separator = '-')
    {
        return Str::slug($title, $separator);
    }

    /**
     * Cria os dados dentro de uma tabela
     * $var = array
     */
    public function TableBuild($var = [], $userId = null)
    {
        // pega o nome da tabela model
        $selfDBTable = Str::plural(strtolower(Str::snake($this->ClassModel)));
        if (method_exists($this->Model, 'getTable')) {
            $selfDBTable = $this->Model->getTable();
        }

        $r = $var;
        $r['comandos'] = $this->comandos;

        $r['meta'] = $this->listVars['meta'];
        $r['heads'] = array_intersect($this->listVars['heads'], $this->Model->fillable);
        $campos = $this->Model->campos;

        // inseri o id no começo do array
        array_splice($r['heads'], 0, 0, 'id');

        //chama a função para acrescimo da restrição das permissões, se for o caso.
        $this->isRestrict();

        $db = $this->Model;
        $r['db'] = $db;

        //pega os lixos
        if (!empty($_REQUEST['trash'])) {
            //$r['db'] = $r['dbTrash']->paginate(30, $r['heads']);
            $r['db'] = $this->Model->onlyTrashed();
            $r['comandos']['show'] = $r['comandos']['edit'] = $r['comandos']['destroy'] = null;
        }

        if (request()->has('search')) {
            $r['db'] = $r['db']->where($selfDBTable . '.id', request()->search);

            foreach ($this->searchParams as $kSearchParams => $vSearchParams) {
                $r['db'] = $r['db']->orWhere($selfDBTable . '.' . $vSearchParams, 'like', '%' . request()->search . '%');
            }
        }

        if (request()->has('user_id') or $userId) {
            $r['db'] = $r['db']->where('user_id', $userId ?? request()->user_id);
        }

        if (request()->has('oderBy')) {
            foreach (request()->oderBy as $oderByK => $oderByV) {
                $r['db'] = $r['db']->orderBy($selfDBTable . '.' . $oderByK, strtoupper($oderByV));
            }
        }

        if (empty($_REQUEST['trash'])) {
            $r['comandos']['restore'] = $r['comandos']['forceDelete'] = null;
        }

        $r['meta']['data'] = $r['db']->count();

        $r['db'] = $r['db']->paginate(30, $r['heads'])->toArray();

        $r['meta']['trash'] = $this->Model->onlyTrashed();
        
        if (request()->has('user_id') or $userId) {
            $r['meta']['trash'] = $r['meta']['trash']->where('user_id', $userId ?? request()->user_id);
        }

        $r['meta']['trash'] = $r['meta']['trash']->count();

        foreach ($r['heads'] as $k => $v) {
            switch ($v) {
                case 'id':
                    $heads_1[$v] = [
                        'label' => 'id',
                        'type' => 'int',
                    ];
                    break;

                default:
                    $heads_1[$v] = [
                        'label' => $campos[$v]['html']['label'],
                        'type' => $campos[$v]['db']['type'],
                    ];
                    break;
            }
        }

        $r['heads'] = $heads_1;

        foreach ($r['db']['data'] as $k => $v) {
            foreach ($v as $kk => $vv) {
                switch ($r['heads'][$kk]['type']) {
                        // se o campo for nos formatos abaixo, formata os dados
                    case 'date':
                    case 'timestamp':
                        // modifica a data conforme padrão
                        $r['db']['data'][$k][$kk] = \Carbon\Carbon::parse($vv)->format('d-m-Y H:i:s');
                        //die;
                        break;

                    case 'enum':
                        //dd(substr($campos[$kk]['html']['options'][$vv], 0, strlen('db.')));
                        if (is_array($campos[$kk]['html']['options'])) {
                            $r['db']['data'][$k][$kk] = $campos[$kk]['html']['options'][$vv] ?? $vv;
                            break;
                        }

                        if (substr($campos[$kk]['html']['options'][$vv], 0, strlen('db.')) == 'db.') {
                            $tableModelSelect = (array) explode('db.', $campos[$kk]['html']['options']);
                            $r['db']['data'][$k][$kk] = DB::table($tableModelSelect[1])->where('id', $vv)->get(['id', 'name'])->first();
                            $r['db']['data'][$k][$kk] = $r['db']['data'][$k][$kk]->name;
                            break;
                        }

                        $r['db']['data'][$k][$kk] = 'null';
                        break;

                        //$r['db']['data'][$k][$kk] = $campos[$kk]['html']['options'][$vv];
                        //break;

                    case 'foreignId':
                        if (!empty($campos[$kk]['html']['options'])) {
                            $tableModelSelect = (array) explode('db.', $campos[$kk]['html']['options']);
                            $r['db']['data'][$k][$kk] = DB::table($tableModelSelect[1])->where('id', $vv)->get(['id', 'name'])->first();
                            $r['db']['data'][$k][$kk] = $r['db']['data'][$k][$kk]->name ?? '';
                        }
                        break;

                    case 'decimal':
                        if (strpos($kk, 'price') !== false) {
                            $r['db']['data'][$k][$kk] = 'R$ ' . number_format($vv, 2, ',', '.');
                        }
                        break;

                    default:

                        if (!empty($campos[$kk]['html']['options'])) {

                            if (is_array($campos[$kk]['html']['options'])) {
                                $r['db']['data'][$k][$kk] = $campos[$kk]['html']['options'][$vv] ?? $vv;
                                break;
                            }

                            if (substr($campos[$kk]['html']['options'][$vv], 0, strlen('db.')) == 'db.') {
                                $tableModelSelect = (array) explode('db.', $campos[$kk]['html']['options']);

                                $defatulDb = DB::table($tableModelSelect[1]);

                                if ($vLoop = (array) explode(',', $vv)) {
                                    $defatulDbWhereIn = [];

                                    foreach ($vLoop as $kVLoop => $vVLoop) {
                                        array_push($defatulDbWhereIn, $vVLoop);
                                    }

                                    //dd($defatulDbWhereIn);

                                    $defatulDb = $defatulDb->whereIn('id', $defatulDbWhereIn)
                                        ->pluck('name');

                                    //dd($defatulDb);

                                    $r['db']['data'][$k][$kk] = implode(',', $defatulDb->toArray());

                                    //dd($k, $kk, $r['db']['data'][$k][$kk]);
                                }

                                if (is_int($vv)) {
                                    $r['db']['data'][$k][$kk] = DB::table($tableModelSelect[1])->where('id', $vv)->get(['id', 'name'])->first();
                                    $r['db']['data'][$k][$kk] = $r['db']['data'][$k][$kk]->name;
                                }
                            }

                            //dd($k, $kk, $r['db']['data'][$k][$kk]);
                            break;
                        }

                        break;
                }
            }
        }
        //dd($r['db']['data'], $r['db']);
        return $r;
    }

    ///
    public function reverb(RequestFrm $request)
    {
        $socketId = $request->input('socket_id');
        $channelName = $request->input('channel_name');

        try {
            // Generate the required format for the response
            $stringToAuth = $socketId . ':' . $channelName;
            $hashed = hash_hmac('sha256', $stringToAuth, env('REVERB_APP_SECRET'));

            return response(['auth' => env('REVERB_APP_KEY') . ':' . $hashed], 200);
        } catch (\Exception $e) {
            return response(['code' => 403, 'message' => 'Cannot authenticate reverb'], 403);
        }
    }

    /* public function reverb(RequestFrm $request)
    {

        $user = true; //auth()->user();
        $socket_id = $request['socket_id'];
        $channel_name = $request['channel_name'];
        $key = config('broadcasting.connections.pusher.key');
        $secret = config('broadcasting.connections.pusher.secret');
        $app_id = config('broadcasting.connections.pusher.app_id');
        if ($user) {

            $pusher = new Pusher($key, $secret, $app_id);
            $auth = $pusher->socketAuth($channel_name, $socket_id);
            return response($auth, 200);
        } else {
            header('', true, 403);
            echo "Forbidden";
            return;
        }
    } */

    /**
     * int $this->status = 200;
     * String $this->message = message of return
     * Bool|Object|Array $this->return = data of return
     * 
     * $typeOfReturn = bool|object|json - default = 'bool'
     */
    public function returnSuccess(string $typeOfReturn = null)
    {

        $typeOfReturn = $typeOfReturn ?? $this->typeOfReturn;

        switch ($typeOfReturn) {
            case 'bool':
            case 'b':
                return true;
                break;


            case 'o':
            case 'obj':
            case 'object':
                return $this->return;;
                break;


            case 'j':
            case 'json':
                return $this->sendResponse($this->return, $this->message, $this->status);
                break;

            default:
                // return error
                break;
        }
    }

    /**
     * int $this->status = 200;
     * String $this->message = message of return
     * Bool|Object|Array $this->return = data of return
     * 
     * $typeOfReturn = bool|object|json - default = 'bool'
     */
    public function returnError(string $typeOfReturn = null)
    {
        $typeOfReturn = $typeOfReturn ?? $this->typeOfReturn;

        switch ($typeOfReturn) {
            case 'bool':
            case 'b':
                return false;
                break;

            case 'o':
            case 'obj':
            case 'object':
                return $this->return;;
                break;

            case 'j':
            case 'json':
                return $this->sendError($this->message, $this->status);
                break;

            default:
                // return error
                break;
        }
    }


    function getDateToCartExpiresAt()
    {
        list($h, $m, $s) = explode(':', env('CART_EXPIRES_AT'));

        return Carbon::now()
            ->addHours((int) $h)
            ->addMinutes((int)$m)
            ->addSeconds((int)$s)
        ;
    }
}
