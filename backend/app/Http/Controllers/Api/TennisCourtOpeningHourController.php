<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\RequestFrm;
use App\Http\Resources\TennisCourtOpeningHourCollection;
use App\Models\TennisCourtOpeningHour;

class TennisCourtOpeningHourController extends BaseController
{
    public $tennis_court_id;

    public function __construct()
    {
        $this->Model = new TennisCourtOpeningHour();

        $this->data['campos'] = $this->Model->campos;
        $this->NamefolderImage = 'tennis-court-opening-hour';
        $this->PathsView = 'tennisCourtOpeningHour';
        $this->PathsRoute = 'tennis-court-opening-hour';
        $this->ClassModel = 'TennisCourtOpeningHour';
        $this->listVars['meta'] = [
            'title' => 'Horários de Funcionamento',
            'h1' => 'Horários de Funcionamento',
        ];
        $this->listVars['uriBase'] = 'tennis-court-opening-hour/';
        $this->listVars['heads'] = [
            'day',
            'hour_start',
            'hour_end',
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
            'hour_start', 'hour_end', 'day'
        ];
    }

    public function edit($id)
    {
        //chama a função para acrescimo da restrição das permissões, se for o caso.
        $this->isRestrict();

        $this->tennis_court_id = (int) $id;

        return $this->create();
    }

    public function create()
    {
        //chama a função para acrescimo da restrição das permissões, se for o caso.
        $this->isRestrict();

        $values = null;
        $r['config'] =
            [
                'method_field' => 'POST',
                'urlFormAction' => route($this->PathsRoute . '.store'),
                'methodForm' => 'POST',
                //'urlReturn' => 'imoveis-media.create',
            ];

        if (!empty(request()->tennis_court_id) or !empty($this->tennis_court_id)) {
            $tennis_court_id = (int)($this->tennis_court_id ?? request()->tennis_court_id);
            $campos = $this->data['campos'];

            //$item = $this->Model->where('tennis_court_id', $tennis_court_id);
            $values = ['tennis_court_id' => $tennis_court_id];
            $r['config']['urlFormAction'] = route($this->PathsRoute . '.store');

            $this->data['campos'] = $campos + $r;
            return $this->mySchema($values);
        }

        $this->message = 'Erro ao compor os dados';
        $this->status = 409;

        return $this->sendError(request()->id, $this->tennis_court_id, $this->status);
    }

    function store(RequestFrm $request)
    {
        if ($request->hour_end <= $request->hour_start) {
            return $this->sendError('O horário Inicial deve ser menor que o horário final', 409);
        }

        if ($this->isExists($request)) {
            return $this->sendError('Os horários ja existem, confira e tente novamente', 409);
        }

        return parent::store($request);
    }

    public function isExists(RequestFrm $request)
    {
        //return
        $r = $this->Model
            ->where(
                [
                    ['tennis_court_id', $request->tennis_court_id],
                    ['day', $request->day],
                ]
            )
            ->where(
                [
                    ['hour_start', '>=', $request->hour_start],
                    ['hour_start', '<', $request->hour_end],
                    //['hour_start', '<=', $request->hour_end]
                ]
            )
            //->whereBetween('hour_start', [$request->hour_start, $request->hour_end])
            //->orWhereBetween('hour_end', [$request->hour_start, $request->hour_end])
            //->toSql();
            ->get()->toArray();

            return $r;
            //dd($r);
    }

    public function listItemsToTennisCourtId($tennis_court_id)
    {
        try {
            $q = $this->Model->includeTablesRelations();

            $q = $q->where('tennis_court_id', $tennis_court_id)->orderByRaw("FIELD(day, 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday')")
            ->orderByRaw("CAST('hour_start' AS UNSIGNED)")
            ->orderByRaw("CAST('hour_end' AS UNSIGNED)")
            //->orderBy('hour_start', 'desc')->orderBy('hour_end', 'ASC')
            ->get();

            //$q = new TennisCourtOpeningHourCollection($q);
 
            $this->status = 200;
            $this->message = 'Itens listados com sucesso!';

            return $this->sendResponse($q, $this->message, $this->status);
        } catch (\Exception $e) {
            $this->status = $e;
            $this->message = $e;
        }

        return $this->sendError($this->message, $this->status);
    }

    function numberOfHoursPerDay($tennis_court_id) {
        try {
            $q = $this->Model->numberOfHoursPerDay($tennis_court_id)
            ->get();

            $q = array_column($q->toArray(), 'soma_diferencas', 'day');

            $this->status = 200;
            $this->message = 'Itens listados com sucesso!';

            return $this->sendResponse($q, $this->message, $this->status);
        } catch (\Exception $e) {
            $this->status = $e;
            $this->message = $e;
        }

        return $this->sendError($this->message, $this->status);
    } 

}
