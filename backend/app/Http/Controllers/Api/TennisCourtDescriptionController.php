<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\RequestFrm;
use App\Models\TennisCourt;
use App\Models\TennisCourtDescription;
use App\Models\TennisCourtDescriptionTable;

class TennisCourtDescriptionController extends BaseController
{
    public $tennis_court_id;

    public function __construct()
    {
        $this->Model = new TennisCourtDescription();

        $this->data['campos'] = $this->Model->campos;
        $this->NamefolderImage = 'tennis-court-description';
        $this->PathsView = 'tennisCourtDescription';
        $this->PathsRoute = 'tennis-court-description';
        $this->ClassModel = 'TennisCourtDescription';
        $this->listVars['meta'] = [
            'title' => 'Lista de Descrições',
            'h1' => 'Lista de Descrições',
        ];
        $this->listVars['uriBase'] = 'tennis-court-description/';
        $this->listVars['heads'] = [
            'tennis_court_description_table_id',
            'tennis_court_id',
        ];

        $this->comandos = [
            //'show'=>[$this->PathsRoute.'.show', 'user_id'],
            'edit' => [$this->PathsRoute . '.edit', 'id'],
            'destroy' => [$this->PathsRoute . '.destroy', 'id'],
            'forceDelete' => [$this->PathsRoute . '.forceDelete', 'id'],
            'restore' => [$this->PathsRoute . '.restore', 'id']
        ];

        $this->searchParams = [
            'tennis_court_description_table_id',
            'tennis_court_id',
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

            $item = (new TennisCourt)->includeTablesRelations()->findOrFail($tennis_court_id);

            $values = ['tennis_court_id' => $item->id];
            $r['config']['urlFormAction'] = route($this->PathsRoute . '.store');          

            // pega a fila de itens para montar o form
            $tennisCourtDescriptionTable = TennisCourtDescriptionTable::findOrFail(
                 explode(',', $item->tennis_court_group->tennis_court_description_table_id)
            );   

            if ($item->tennis_court_description->count()) {
                $r['config']['urlFormAction'] = route($this->PathsRoute . '.update', $item->id);
                $r['config']['methodForm'] = $r['config']['method_field'] = 'PUT';
                //$imoveisDescription = array_column($imoveisDescription->toArray(), 'value', 'tennis_court_description_table_id');

                //aqui pegaremos os valores de cada item da fila que montara o form
                $myTennisCourtDescriptionValueArray = array_column($item->tennis_court_description->toarray(), 'value', 'tennis_court_description_table_id');
                $myTennisCourtDescriptionIdArray = array_column($item->tennis_court_description->toarray(), 'id', 'tennis_court_description_table_id');
            }

            unset($campos['tennis_court_description_table_id'], $campos['value']);

            $i = 1;
            foreach ($tennisCourtDescriptionTable as $v) {
                $idRelation = $v->id;
                $valueRelation = $myTennisCourtDescriptionValueArray[$v->id] ?? '';

                $campos['tennis_court_description_table_id' . $idRelation] = [
                    'html' => [
                        'fieldType' => 'text',
                        'label' => $i . ') ' . $v->name . ' (' . $v->unit . ')',
                        'validation' => ['required', 'max:255'],
                        //'options' => [$v->id => $v->name],
                        //'disabled ' => 'disabled '
                        'placeholder' => $v->name . ' (' . $v->unit . ')',
                    ],
                    'db' => [],
                ];

                if (trim(strtolower($v->unit)) == 'sim/não') {
                    $campos['tennis_court_description_table_id' . $idRelation]['html']['fieldType'] = 'radio';
                    $campos['tennis_court_description_table_id' . $idRelation]['html']['options'] = ['sim' => 'sim', 'não' => 'não'];
                }

                if (trim(strtolower($v->unit)) == 'unidade(s)') {
                    $campos['tennis_court_description_table_id' . $idRelation]['html']['fieldType'] = 'number';
                }

                $values['tennis_court_description_table_id' . $idRelation] = $valueRelation;
                $i++;
            }

            $this->data['campos'] = $campos + $r;
            return $this->mySchema($values);
        }

        $this->message = 'Erro ao compor os dados';
        $this->status = 409;

        return $this->sendError(request()->tennis_court_id, $this->tennis_court_id, $this->status);
    }

    public function store(RequestFrm $request)
    {
        //chama a função para acrescimo da restrição das permissões, se for o caso.
        $this->isRestrict();
        $this->status = 200;
        $this->message = 'Itens Criados com sucesso!';

        $user_id = 1;
        if (!empty(auth('sanctum')->user()->id)) {
            $user_id = auth('sanctum')->user()->id;
        }

        $q = [];

        foreach ($request->except(['_token', 'user_id', 'urlReturn', '_method', 'tennis_court_id']) as $k => $v) {
            $q[] = [
                'tennis_court_description_table_id' => (int) (explode('tennis_court_description_table_id', $k))[1],
                'value' => $v,
                'user_id' => $user_id,
                'tennis_court_id' => $request->tennis_court_id,
                'created_at'=>now(),
            ];
        }

        try {
            $this->Model->insert($q);

            return $this->sendResponse($q, $this->message, $this->status);
        } catch (\Exception $e) {
            $this->status = $e;
            $this->message = $e;
        }

        return $this->sendError($this->message, $this->status);
    }

    public function update(RequestFrm $request, $id)
    {
        //chama a função para acrescimo da restrição das permissões, se for o caso.
        $this->isRestrict();
        $this->status = 200;
        $this->message = 'Itens Editados com sucesso!';

        $user_id = 1;
        if (!empty(auth('sanctum')->user()->id)) {
            $user_id = auth('sanctum')->user()->id;
        }
        
        foreach ($request->except(['_token', 'user_id', 'urlReturn', '_method', 'tennis_court_id']) as $k => $v) {
            try {
                $q = $this->Model->where('tennis_court_id', $id);
                $q->where('tennis_court_description_table_id', (int) (explode('tennis_court_description_table_id', $k))[1]);
                $q->update([
                    'value' => $v,
                    'user_id' => $user_id
                ]);
            } catch (\Exception $e) {
                $this->status = $e;
                $this->message = $e;
                return $this->sendError($this->message, $this->status);
            }
        }
        return $this->sendResponse($q, $this->message, $this->status);        
    }
}
