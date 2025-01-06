<?php

namespace App\Classes;

use Illuminate\Routing\Router as BaseRouter;
use Illuminate\Support\Str;

class Router extends BaseRouter
{
    //['restore', true] =  model inUrl = /{model}/restore
    public array $customApiResourceMethods = [
        'all' =>  'get',
        'list' =>  'get',
        'search' =>  ['get', true],
        'restore' => ['patch', true],
        'forceDelete' => ['delete', true],
        'migrate' => 'get',
        'uploadTemporary' => 'post',
        'removeTemporary' => 'post',
    ];
    public array $customResourceMethods = [
        'all' =>  'get',
        'list' =>  'get',
        'search' =>  ['get', true],
        'restore' => ['patch', true],
        'forceDelete' => ['delete', true],
        'migrate' => 'get',
        'uploadTemporary' => 'post',
        'removeTemporary' => 'post',
    ];

    public function customApiResource($name, $controller, array $options = [])
    {
        $model = Str::singular($name); // this is optional, i need it for Route Model Binding

        foreach ($this->customApiResourceMethods as $k => $v) {
            $modelInUrl = null;

            if (is_array($v)) {
                list($v, $modelInUrl) = $v;
            }

            $nameParam1 = '/';
            if ($modelInUrl == true) {
                $nameParam1 = '/{' . str_replace('-', '_', $model) . '}/';
            }

            $nameParam2 = Str::snake($k, '-') . '/';

            $nameParam3 = '';
            if (is_string($modelInUrl)) {
                $nameParam3 = '{' . $modelInUrl . '}/';
            }

            switch ($k) {
                case 'search':
                    $nameParam1 = '/search/';
                    $nameParam2 = '{search?}';
                    break;
            }

            $this->addRoute(
                strtoupper($v ?? 'get'),
                $name . $nameParam1 . $nameParam2 . $nameParam3,
                $controller . '@' . $k
            )
                ->name($name . '.' . $k);
        }

        return $this->apiResource($name, $controller, $options);
    }

    public function customResource($name, $controller, array $options = [])
    {
        $model = Str::singular($name); // this is optional, i need it for Route Model Binding

        foreach ($this->customResourceMethods as $k => $v) {
            $modelInUrl = null;

            if (is_array($v)) {
                list($v, $modelInUrl) = $v;
            }

            $nameParam1 = '/';
            if ($modelInUrl == true) {
                $nameParam1 = '/{' . str_replace('-', '_', $model) . '}/';
            }

            $nameParam2 = Str::snake($k, '-') . '/';

            $nameParam3 = '';
            if (is_string($modelInUrl)) {
                $nameParam3 = '{' . $modelInUrl . '}/';
            }

            switch ($k) {
                case 'search':
                    $nameParam1 = '/search/';
                    $nameParam2 = '{search?}';
                    break;
            }

            $this->addRoute(
                strtoupper($v ?? 'get'),
                $name . $nameParam1 . $nameParam2 . $nameParam3,
                $controller . '@' . $k
            )
                ->name($name . '.' . $k);
        }
        return $this->Resource($name, $controller, $options);
    }
}
