<?php

namespace App\Http\Controllers\Api;

use App\Models\DiscountPolicy;

class DiscountPolicyController extends BaseController
{
    public function __construct()
    {
        $this->Model = new DiscountPolicy();

        $this->data['campos'] = $this->Model->campos;
        $this->NamefolderImage = 'discount-policy';
        $this->PathsView = 'discountPolicy';
        $this->PathsRoute = 'discount-policy';
        $this->ClassModel = 'DiscountPolicy';
        $this->listVars['meta'] = [
            'title' => 'Lista de Políticas de Desconto',
            'h1' => 'Lista de Políticas de Desconto',
        ];
        $this->listVars['uriBase'] = 'discount-policy/';
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
            'name', 'description',
        ];
    }
}
