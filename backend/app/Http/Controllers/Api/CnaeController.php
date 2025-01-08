<?php

namespace App\Http\Controllers\Api;

use App\Models\Cnae;

class CnaeController extends BaseController
{
    public function __construct()
    {
        $this->Model = new Cnae();

        $this->data['campos'] = $this->Model->campos;
        $this->NamefolderImage = 'cnae';
        $this->PathsView = 'Cnae';
        $this->PathsRoute = 'cnae';
        $this->ClassModel = 'Cnae';
        $this->listVars['meta'] = [
            'title' => 'Lista de Cnae',
            'h1' => 'Lista de Cnae',
        ];
        $this->listVars['uriBase'] = 'cnae/';
        $this->listVars['heads'] = [
            'name',
            'codigo',
            'user_id',
            'created_at',
        ];

        $this->comandos = [
            //'show'=>[$this->PathsRoute.'.show', 'user_id'],
            'edit' => [$this->PathsRoute . '.edit', 'id'],
            'destroy' => [$this->PathsRoute . '.destroy', 'id'],
            'forceDelete' => [$this->PathsRoute . '.forceDelete', 'id'],
            'restore' => [$this->PathsRoute . '.restore', 'id']
        ];
    }
}
