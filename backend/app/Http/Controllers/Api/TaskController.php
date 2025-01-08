<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;

class TaskController extends BaseController
{
    public function __construct()
    {
        $this->Model = new Task();

        $this->data['campos'] = $this->Model->campos;
        $this->NamefolderImage = 'task';
        $this->PathsView = 'task';
        $this->PathsRoute = 'task';
        $this->ClassModel = 'task';
        $this->listVars['meta'] = [
            'title' => 'Lista de Tarefas',
            'h1' => 'Lista de Tarefas',
        ];
        $this->listVars['uriBase'] = 'task/';
        $this->listVars['heads'] = [
            'title',
            'body',
            'level',
            'time_start',
            'time_end',
            'status',
            'views',

            'user_id',

            'expiration_notices_sent',
            'expires_at',
        ];

        $this->comandos = [
            //'show'=>[$this->PathsRoute.'.show', 'user_id'],
            'edit' => [$this->PathsRoute . '.edit', 'id'],
            'destroy' => [$this->PathsRoute . '.destroy', 'id'],
            'forceDelete' => [$this->PathsRoute . '.forceDelete', 'id'],
            'restore' => [$this->PathsRoute . '.restore', 'id']
        ];

        $this->searchParams = [
            'user_id',
            'title',
            'body'
        ];
    }

    // function show($id)
    // {
    //     $this->Model = $this->Model->includeTablesRelations();
    //     return parent::show($id);
    // }
}
