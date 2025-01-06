<?php

namespace App\Http\Controllers\Api;

use App\Models\TennisCourtType;

class TennisCourtTypeController extends BaseController
{
    public function __construct()
    {
        $this->Model = new TennisCourtType();

        $this->data['campos'] = $this->Model->campos;
        $this->NamefolderImage = 'tennis-court-type';
        $this->PathsView = 'tennisCourtType';
        $this->PathsRoute = 'tennis-court-type';
        $this->ClassModel = 'TennisCourtType';
        $this->listVars['meta'] = [
            'title' => 'Tipo de Quadra',
            'h1' => 'Ex.: Quadra urbana coberta',
        ];
        $this->listVars['uriBase'] = 'tennis-court-type/';
        $this->listVars['heads'] = [
            'name',
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
            'name'
        ];
    }
}
