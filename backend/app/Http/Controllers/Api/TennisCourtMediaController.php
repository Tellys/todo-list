<?php

namespace App\Http\Controllers\Api;

use App\Models\TennisCourtMedia;

class TennisCourtMediaController extends BaseController
{
    public $tennis_court_id;

    public function __construct()
    {

        /* if (empty(request()->id)) {
            return Redirect::to(route('imoveis.list'))->send();
        } */

        $this->Model = new TennisCourtMedia();

        $this->data['campos'] = $this->Model->campos;
        $this->NamefolderImage = 'tennisCourtMedia';
        $this->PathsView = 'tennisCourtMedia';
        $this->PathsRoute = 'tennis-court-media';
        $this->ClassModel = 'tennisCourtMedia';
        $this->listVars['meta'] = [
            'title' => 'Cadastro de Fotos',
            'h1' => 'Cadastro de Fotos',
        ];
        $this->listVars['uriBase'] = 'tennis-court-media/';
        $this->listVars['heads'] = [
            'name',
            'description',
            'text_anternative',
            'order',
            'tennis_court_id',
        ];

        $this->comandos = [
            //'show'=>[$this->PathsRoute.'.show', 'user_id'],
            'edit' => [$this->PathsRoute . '.edit', 'id'],
            'destroy' => [$this->PathsRoute . '.destroy', 'id'],
            'forceDelete' => [$this->PathsRoute . '.forceDelete', 'id'],
            'restore' => [$this->PathsRoute . '.restore', 'id']
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

        return $this->sendError($this->message, $this->status);
    }

    public function listImagesToItem($id)
    {
        try {
            $q = $this->Model->where('tennis_court_id', $id)->orderBy('order')->get();

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
