<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;

class ProductController extends BaseController
{
    public function __construct()
    {
        $this->Model = new Product();

        $this->data['campos'] = $this->Model->campos;
        $this->NamefolderImage = 'product';
        $this->PathsView = 'product';
        $this->PathsRoute = 'product';
        $this->ClassModel = 'product';
        $this->listVars['meta'] = [
            'title' => 'Lista de Produtos',
            'h1' => 'Lista de Produtos',
        ];
        $this->listVars['uriBase'] = 'product/';
        $this->listVars['heads'] = [
            'products_default_id',
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
            'user_id', 'price', 'price_promo'
        ];
    }

    function show($id)
    {
        $this->Model = $this->Model->includeTablesRelations();
        return parent::show($id);
    }

    public function create()
    {
        //chama a função para acrescimo da restrição das permissões, se for o caso.
        $this->isRestrict();

        $values = null;

        if (!empty(request()->tennis_court_id)) {
            $values = ['tennis_court_id' => request()->tennis_court_id];
            return $this->mySchema($values);
        }

        $this->message = 'Erro ao compor os dados';
        $this->status = 409;

        return $this->sendError($this->message, $this->status);
    }

    public function listItemsToTennisCourtId($tennis_court_id)
    {
        try {
            $q = $this->Model->includeTablesRelations()->where('tennis_court_id', $tennis_court_id)->get();

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
