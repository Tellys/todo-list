<?php

namespace App\Http\Controllers\Api;

use App\Models\ProductDepartment;

class ProductDepartmentController extends BaseController
{
    public function __construct()
    {
        $this->Model = new ProductDepartment();

        $this->data['campos'] = $this->Model->campos;
        $this->NamefolderImage = 'product-department';
        $this->PathsView = 'productDepartment';
        $this->PathsRoute = 'product-department';
        $this->ClassModel = 'ProductDepartment';
        $this->listVars['meta'] = [
            'title' => 'Lista de Datas',
            'h1' => 'Lista de Datas',
        ];
        $this->listVars['uriBase'] = 'product-department/';
        $this->listVars['heads'] = [
            'name',
            'description',
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
            'name', 'description', 'user_id'
        ];
    }
}
