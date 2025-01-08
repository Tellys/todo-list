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
            'level',
            'time_start',
            'expires_at',
            'status',
            'check',
        ];

        $this->comandos = [
            'show'=>[$this->PathsRoute.'.show', 'id'],
            'edit' => [$this->PathsRoute . '.edit', 'id'],
            'destroy' => [$this->PathsRoute . '.destroy', 'id'],
            'forceDelete' => [$this->PathsRoute . '.forceDelete', 'id'],
            'restore' => [$this->PathsRoute . '.restore', 'id']
        ];

        $this->searchParams = [
            'user_id',
            'time_start',
            'expires_at',
            'status',
            'title',
            'body'
        ];
    }

    ///
    public function list($var = null)
    {

        $a = [
            'comandos' => $this->comandos,
            $var,
        ];
        //return $this->sendResponse($this->TableBuild(array_merge($a, $this->listVars)), 'success', 200);
        return $this->sendResponse($this->TableBuild([], auth('sanctum')->user()->id), 'success', 200);

    }

    // function show($id)
    // {
    //     $this->Model = $this->Model->includeTablesRelations();
    //     return parent::show($id);
    // }

    function listItemsToUserId($userId = NULL)
    {
        try {

            $this->isRestrict();
            $this->message = 'Sucesso';
            $this->status = 200;

            $r = $this->Model->includeTablesRelations();

            if (request()->has('oderByColumn')) {
                $r = $r->orderBy(request()->oderByColumn, request()->oderBy ?? 'asc');
            }

            $q = $r->where('user_id', $userId ?? auth('sanctum')->user()->id)->get();

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
