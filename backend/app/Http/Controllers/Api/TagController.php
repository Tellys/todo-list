<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;

class TagController extends BaseController
{
    public function __construct()
    {
        $this->Model = new Tag();

        $this->data['campos'] = $this->Model->campos;
        $this->NamefolderImage = 'tag';
        $this->PathsView = 'tag';
        $this->PathsRoute = 'tag';
        $this->ClassModel = 'tag';
        $this->listVars['meta'] = [
            'title' => 'Lista de tag',
            'h1' => 'Lista de tag',
        ];
        $this->listVars['uriBase'] = 'tag/';
        $this->listVars['heads'] = [
            'title',
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

        $this->searchParams = [
            'user_id', 'title'
        ];
    }
}
