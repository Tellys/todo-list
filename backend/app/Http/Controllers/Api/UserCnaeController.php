<?php

namespace App\Http\Controllers\Api;

use App\Classes\NossaClasse;
use App\Http\Requests\RequestFrm;
use App\Models\UserCnae;

class UserCnaeController extends BaseController
{
    public function __construct()
    {
        $this->Model = new UserCnae();

        $this->data['campos'] = $this->Model->campos;
        $this->NamefolderImage = 'user-cnae';
        $this->PathsView = 'UserCnae';
        $this->PathsRoute = 'user-cnae';
        $this->ClassModel = 'UserCnae';
        $this->listVars['meta'] = [
            'title' => 'Lista de Cnae Users',
            'h1' => 'Lista de Cnae Users',
        ];
        $this->listVars['uriBase'] = 'user-cnae/';
        $this->listVars['heads'] = [
            'name',
            'cnae_id',
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
    }

    /**
     *
     */
    public function store(RequestFrm $request)
    {
        //chama a função para acrescimo da restrição das permissões, se for o caso.
        $this->isRestrict();

        // validação dos dados
        $NossaClasse = new NossaClasse();
        $request->validate(
            $NossaClasse->colunasValidate($this->data['campos'])
        );

        try {
            $user_id = auth('sanctum')->user()->id;

            $q = ['user_id' => $user_id];

            $id_cliente = \App\Models\User::where('cpf', $request->cnpj)->firstOrFail();

            /* return $this->sendResponse([
                'id_cliente',
                $request->cnpj,
                $id_cliente
            ], 'ok', 200); */

            $tableCnae = \App\Models\Cnae::firstOrCreate([
                'codigo' => $request->cnae_fiscal,
                'name' => $request->cnae_fiscal_descricao,
                'user_id' => $user_id,
            ]);

            $this->Model->firstOrCreate([
                'name' => $tableCnae->name,
                'type' => 'fiscal',
                'cnae_id' => $tableCnae->id,
                'user_id' => $user_id,
                'cliente_id' => $id_cliente->id,
            ]);

            foreach ($request->cnaes_secundarios as $k => $v) {

                $tableUserCnae = \App\Models\Cnae::firstOrCreate([
                    'codigo' => $v['codigo'],
                    'name' => $v['descricao'],
                    'user_id' => $user_id,
                ]);

                $this->Model->firstOrCreate([
                    'name' => $tableUserCnae->name,
                    'type' => 'secundario',
                    'cnae_id' => $tableUserCnae->id,
                    'user_id' => $user_id,
                    'cliente_id' => $id_cliente->id,
                ]);
            }

            $this->message = 'Item CRIADO com sucesso!';
            $this->status = 200;

            return $this->sendResponse($tableCnae, $this->message, $this->status);
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
        }

        return $this->sendError( $this->message, $this->status);
    }
}
