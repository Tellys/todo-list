<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\RequestFrm;
use App\Models\TennisCourt;

class TennisCourtController extends BaseController
{
    public function __construct()
    {
        $this->Model = new TennisCourt();
        $this->Model->addIncludeTablesRelations = [
            'tennis_court_calendar',
            'tennis_court_description',
            'tennis_court_media',
            //'tennis_court_involvement',
            'tennis_court_opening_hour',
        ];

        $this->data['campos'] = $this->Model->campos;
        $this->NamefolderImage = 'tennis-court';
        $this->PathsView = 'tennisCourt';
        $this->PathsRoute = 'tennis-court';
        $this->ClassModel = 'TennisCourt';
        $this->listVars['meta'] = [
            'title' => 'Lista de Usuarios',
            'h1' => 'Lista de Usuarios',
        ];
        $this->listVars['uriBase'] = 'tennis-court/';
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

        $this->searchParams = [
            'name', 'city', 'state', 'address', 'address_neighborhood', 'description'
        ];
    }

    //campo = ['tipo de comparação , tipo de pesquisa]
    public $searchParams_2 = [
        'name' => ['like', 'or'],
        'city' => ['like', 'and'],
        'state' => ['=', 'and'],
        'address' => ['like', 'and'],
        'address_neighborhood' => ['like', 'or'],
        'description' => ['like', 'or'],
    ];

    public function index()
    {
        // remover restrição para poder listar todos os itens a todos
        $this->setIsRestrict = false;
        return parent::index();
    }

    public function show($id)
    {
        $this->setIsRestrict = false;
        $this->Model = $this->Model->includeTablesRelations();
        return parent::show($id);
    }

    function createSimple()
    {
        //chama a função para acrescimo da restrição das permissões, se for o caso.
        $this->isRestrict();

        $this->data['campos']['users_level_id']['html']['fieldType'] = 'hidden';

        /* $this->data['campos'] = [
            //'users_level_id' => $this->data['campos']['users_level_id'],
            'name' => $this->data['campos']['name'],
            'email' => $this->data['campos']['email'],
            'city' => $this->data['campos']['city'],
            'state' => $this->data['campos']['state'],
            'state' => $this->data['campos']['state'], 
            'web_site' => $this->data['campos']['web_site'],
            'cell_phone' => $this->data['campos']['cell_phone'],
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
        ]; */

        return $this->mySchema();
    }

    function rootModel($model, $request = null, $query = null)
    {

        $r = $model->includeTablesRelations();

        if ($request->all() or !empty($query)) {

            if (!empty($request->q)) {
                // if numeric - search by ID
                if (is_numeric($request->q)) {
                    return $r->whereId($request->q)->latest()->paginate(10)->toArray();
                }

                $query = strip_tags($request->q);
            }

            $rTypeWhere = 'where';
            $r = $r->where(function ($qq) use ($query) {


                foreach ($this->searchParams_2 as $k => $v) {

                    /* if ($request->has($k)) {
                    $query =  $request->$k;
                } */

                    if (request()->has($k)) {
                        $query =  request()->$k;
                    }

                    //xss-input-sanitization
                    $query = strip_tags($query);

                    /* if (!empty($query)) {
                    switch ($v[0]) {
                        case 'like':
                            //echo '<br>'.$k, $v[0], '%' . $q . '%';
                            $r = $r->$rTypeWhere($k, 'like', '%' . $query . '%');
                            break;

                        default:
                            //echo '<br>'.$k, $v[0], $q;
                            $r = $r->$rTypeWhere($k, '=', $query);
                            break;
                    }
                    $rTypeWhere = 'orWhere';
                } */

                    if (!empty($query)) {
                        switch ($v[0]) {
                            case 'like':
                                //echo '<br>'.$k, $v[0], '%' . $q . '%';
                                $qq->orWhere($k, 'like', '%' . $query . '%');
                                break;

                            default:
                                //echo '<br>'.$k, $v[0], $q;
                                $qq->orWhere($k, '=', $query);
                                break;
                        }
                        //$rTypeWhere = 'orWhere';
                    }
                }
            });
        }

        //dd($query, $request->q, $request->all(), $r->toSql());
        //dd($r/* ->toSql() */->get()->toArray());
        //dd($r->toSql());

        return $r = $r->latest()->paginate(10)->toArray(); //->onEachSide(1);
    }

    ///
    public function searchAdvanced(RequestFrm $request, $query = "")
    {
        $this->setIsRestrict = false;

        //chama a função para acrescimo da restrição das permissões, se for o caso.
        //$this->isRestrict();

        try {

            $r = $this->rootModel($this->Model, $request, $query);

            $this->message = 'Pesquisa realizada com sucesso';
            $this->status = 200;

            if (!$r) {
                $this->message = 'Item não localizado ou não existente em nosso banco de dados';
                return $this->sendError($this->message, $this->status);
            }

            //return response()->json($r, 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_INVALID_UTF8_IGNORE);

            return $this->sendResponse($r, $this->message, $this->status);
        } catch (\Exception $e) {
            //$this->message = 'Erro ao pesquisar o item. Provavelmente não existe em nosso banco de dados.';
            return $this->sendError($e, $e);
        }
    }

    ///
    public function closeToMe(RequestFrm $request, $query = "")
    {
        // $this->setIsRestrict = false;
        $this->isRestrict();

        // Fetch data within clise to me 
        try {
            $r = $this->Model;
            $myLocationDistance = null;

            if ($request->has('latitude') and $request->has('longitude')) {
                $myLocationDistance = (int)($request->distance ?? env('SET_MY_LOCATION_DISTANCE'));
                $r = $this->Model->dataWithinDistance($request->latitude, $request->longitude, $myLocationDistance);
                //$r= $r->groupByRaw('city');        
            }

            $r = $this->rootModel($r, $request, $query);

            $r['myLocationDistance'] = $myLocationDistance;

            $this->message = 'Itens próximos à sua localização';
            $this->status = 200;
            return $this->sendResponse($r, $this->message, $this->status);
        } catch (\Exception $e) {
            return $this->sendError($e, $e);
        }
    }

    ///
    public function filterItems(RequestFrm $request, $query = "")
    {
        // $this->setIsRestrict = false;
        $this->isRestrict();

        // Fetch data within clise to me 
        try {
            $r = $this->Model;
            $myLocationDistance = null;

            if ($request->has('latitude') and $request->has('longitude')) {
                $myLocationDistance = (int)($request->distance ?? env('SET_MY_LOCATION_DISTANCE'));
                $r = $this->Model->dataWithinDistance($request->latitude, $request->longitude, $myLocationDistance);
                //$r= $r->groupByRaw('city');        
            }

            if ($request->has('involvement') and !empty($request->involvement)) {
                $involvement = \App\Models\TennisCourtInvolvement::where([
                    'user_id' => auth('sanctum')->user()->id,
                    'tennis_court_involvement_table_id' => $request->involvement
                ])->get('tennis_court_id')->toArray();

                $request->request->remove('involvement');
                $r = $this->Model->whereIn('id', $involvement);

                // $r = $this->Model->join('tennis_court_involvements', function ($join) {
                //     $join->on('tennis_court_involvements.user_id', '=', auth('sanctum')->user()->id);
                //     $join->on('tennis_court_involvements.tennis_court_involvement_table_id', '=', request()->involvement);
                // });
            }

            $r = $this->rootModel($r, $request, $query);

            $r['myLocationDistance'] = $myLocationDistance;

            $this->message = 'Itens próximos à sua localização';
            $this->status = 200;
            return $this->sendResponse($r, $this->message, $this->status);
        } catch (\Exception $e) {
            return $this->sendError($e, $e);
        }
    }

    public function registrationPhases()
    {
        try {
            $r = $this->Model
            ::where('user_id', auth('sanctum')->user()->id)
            ->where('registration_phase', '<>', last($this->Model->registration_phases))
            //->orWhereNull('registration_phase')
            ->get(['name', 'id', 'registration_phase']);

            $this->message = 'Itens retornados com sucesso';
            $this->status = 200;
            return $this->sendResponse($r, $this->message, $this->status);
        } catch (\Exception $e) {
            return $this->sendError($e, $e);
        }
    }

    ///fim
}
