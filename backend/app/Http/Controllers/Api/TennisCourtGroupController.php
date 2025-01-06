<?php

namespace App\Http\Controllers\Api;

use App\Models\TennisCourtGroup;

class TennisCourtGroupController extends BaseController
{
    public function __construct()
    {
        $this->Model = new TennisCourtGroup();

        $this->data['campos'] = $this->Model->campos;
        $this->NamefolderImage = 'tennis-court-droup';
        $this->PathsView = 'tennisCourtGroup';
        $this->PathsRoute = 'tennis-court-group';
        $this->ClassModel = 'TennisCourtGroup';
        $this->listVars['meta'] = [
            'title' => 'Lista de Grupos',
            'h1' => 'Lista de Grupos',
        ];
        $this->listVars['uriBase'] = 'tennis-court-group/';
        $this->listVars['heads'] = [
            'name',
            'description',
            'tennis_court_description_table_id',
            'tennis_court_type_id',
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
            'description',
            'tennis_court_description_table_id',
            'tennis_court_type_id',
        ];
    }
}
