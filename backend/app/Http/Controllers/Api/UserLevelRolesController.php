<?php

namespace App\Http\Controllers\Api;

use App\Models\UserLevelRoles;

class UserLevelRolesController extends BaseController
{
    public function __construct()
    {

        $this->Model = new UserLevelRoles();

        $this->data['campos'] = $this->Model->campos;
        $this->NamefolderImage = 'UserLevelRoles';
        $this->PathsView = 'userLevelRoles';
        $this->PathsRoute = 'user-level-roles';
        $this->ClassModel = 'UserLevelRoles';
        $this->listVars['meta'] = [
            'title' => 'Página para Regras de Permissões de Usuários',
            'h1' => 'Página para Regras de Permissões de Usuários',
        ];
        $this->listVars['uriBase'] = 'user-level-roles';
        $this->listVars['heads'] = [
            'name',
            'users_level_id',
            'action',
            'user_id',
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
