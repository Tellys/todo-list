<?php

namespace App\Http\Controllers\Api;

use App\Models\TennisCourtInvolvementTable;

class TennisCourtInvolvementTableController extends BaseController
{
    public function __construct()
    {
        $this->Model = new TennisCourtInvolvementTable();

        $this->data['campos'] = $this->Model->campos;
        $this->NamefolderImage = 'tennis-court-involvement-table';
        $this->PathsView = 'tennisCourtInvolvementTable';
        $this->PathsRoute = 'tennis-court-involvement-table';
        $this->ClassModel = 'TennisCourtInvolvementTable';
        $this->listVars['meta'] = [
            'title' => 'Lista de Tipos de Envolvimentos',
            'h1' => 'Lista de Tipos de Envolvimentos',
        ];
        $this->listVars['uriBase'] = 'tennis-court-involvement-table/';
        $this->listVars['heads'] = [
            'name',
            'icon_id',
            'involvement',
            'description'
        ];

        $this->comandos = [
            //'show'=>[$this->PathsRoute.'.show', 'user_id'],
            'edit' => [$this->PathsRoute . '.edit', 'id'],
            'destroy' => [$this->PathsRoute . '.destroy', 'id'],
            'forceDelete' => [$this->PathsRoute . '.forceDelete', 'id'],
            'restore' => [$this->PathsRoute . '.restore', 'id']
        ];

        $this->searchParams = [
            'name',
        ];
    }

    public function all()
    {
        // remover restrição para poder listar todos os itens a todos
        $this->setIsRestrict = false;
        //$this->Model = $this->Model->whereId(auth('sanctum')->user()->id);
        return parent::all();
    }
}
