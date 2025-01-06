<?php

namespace App\Http\Controllers\Api;

use App\Models\ProductsDefault;

class ProductsDefaultController extends BaseController
{
    public function __construct()
    {
        $this->Model = new ProductsDefault();

        $this->data['campos'] = $this->Model->campos;
        $this->NamefolderImage = 'product-default';
        $this->PathsView = 'productsDefault';
        $this->PathsRoute = 'product-department';
        $this->ClassModel = 'ProductsDefault';
        $this->listVars['meta'] = [
            'title' => 'Lista de Produtos',
            'h1' => 'Lista de Produtos',
        ];
        $this->listVars['uriBase'] = 'product-default/';
        $this->listVars['heads'] = [
            'name',
            'description',
            'product_department_id',
            'unit',
            'price',
            'price_promo',
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
            'name', 'description', 'user_id', 'price', 'price_promo'
        ];
    }
}