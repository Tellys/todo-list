<?php

namespace App\Http\Controllers\Api;

use App\Models\TennisCourtDescriptionTable;

class TennisCourtDescriptionTableController extends BaseController
{
    public function __construct()
    {
        $this->Model = new TennisCourtDescriptionTable();

        $this->data['campos'] = $this->Model->campos;
        $this->NamefolderImage = 'tennis-court-description-table';
        $this->PathsView = 'tennisCourtDescriptionTable';
        $this->PathsRoute = 'tennis-court-description-table';
        $this->ClassModel = 'TennisCourtDescriptionTable';
        $this->listVars['meta'] = [
            'title' => 'Lista de Tabela de Descrições',
            'h1' => 'Lista de Tabela de Descrições',
        ];
        $this->listVars['uriBase'] = 'tennis-court-description-table/';
        $this->listVars['heads'] = [
            'name',
            'unit',
            'icon_id',
            'score',
        ];

        $this->comandos = [
            //'show'=>[$this->PathsRoute.'.show', 'user_id'],
            'edit' => [$this->PathsRoute . '.edit', 'id'],
            'destroy' => [$this->PathsRoute . '.destroy', 'id'],
            'forceDelete' => [$this->PathsRoute . '.forceDelete', 'id'],
            'restore' => [$this->PathsRoute . '.restore', 'id']
        ];

        $this->searchParams = [
            'name','score',
        ];
    }



}
