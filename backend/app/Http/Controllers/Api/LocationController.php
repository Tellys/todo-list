<?php

namespace App\Http\Controllers\Api;

use App\Models\Location;

class LocationController extends BaseController
{
    public function __construct()
    {
        $this->Model = new Location();

        $this->data['campos'] = $this->Model->campos;
        $this->NamefolderImage = 'location';
        $this->PathsView = 'location';
        $this->PathsRoute = 'location';
        $this->ClassModel = 'Location';
        $this->listVars['meta'] = [
            'title' => 'Lista Localidades',
            'h1' => 'Lista de Localidades',
        ];
        $this->listVars['uriBase'] = 'location/';
        $this->listVars['heads'] = [
            'name',
            'lat',
            'lng',
            'country',
            'state',
            'timezone',
            'updated_at',
        ];

        $this->comandos = [
            //'show'=>[$this->PathsRoute.'.show', 'user_id'],
            'edit' => [$this->PathsRoute . '.edit', 'id'],
            'destroy' => [$this->PathsRoute . '.destroy', 'id'],
            'forceDelete' => [$this->PathsRoute . '.forceDelete', 'id'],
            'restore' => [$this->PathsRoute . '.restore', 'id']
        ];

        $this->searchParams = [
            'name',
            'lat',
            'lng',
            'country',
            'state',
        ];
    }
}
