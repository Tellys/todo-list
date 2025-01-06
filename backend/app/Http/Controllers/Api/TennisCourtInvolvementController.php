<?php

namespace App\Http\Controllers\Api;

use App\Classes\NossaClasse;
use App\Http\Requests\RequestFrm;
use App\Models\TennisCourtInvolvement;

class TennisCourtInvolvementController extends BaseController
{
    public function __construct()
    {
        $this->Model = new TennisCourtInvolvement();

        $this->data['campos'] = $this->Model->campos;
        $this->NamefolderImage = 'tennis-court-involvement';
        $this->PathsView = 'tennisCourtInvolvement';
        $this->PathsRoute = 'tennis-court-involvement';
        $this->ClassModel = 'TennisCourtInvolvement';
        $this->listVars['meta'] = [
            'title' => 'Lista de Envolvimentos do Item',
            'h1' => 'Lista de Envolvimentos do Item',
        ];
        $this->listVars['uriBase'] = 'tennis-court-involvement/';
        $this->listVars['heads'] = [
            'tennis_court_id',
            'tennis_court_involvement_table_id',
        ];

        $this->comandos = [
            //'show'=>[$this->PathsRoute.'.show', 'user_id'],
            'edit' => [$this->PathsRoute . '.edit', 'id'],
            'destroy' => [$this->PathsRoute . '.destroy', 'id'],
            'forceDelete' => [$this->PathsRoute . '.forceDelete', 'id'],
            'restore' => [$this->PathsRoute . '.restore', 'id']
        ];

        $this->searchParams = [
            'id',
            'tennis_court_id',
            'tennis_court_involvement_table_id',
        ];
    }

    public function getAllForUserLogged()
    {
        $this->Model = $this->Model->where('user_id', auth('sanctum')->user()->id);
        return parent::all();
    }

    function updateOrCreate(RequestFrm $request)
    {
        //chama a função para acrescimo da restrição das permissões, se for o caso.
        $this->status = 200;
        $this->message = 'Tudo Certo!';

        try {
            $this->isRestrict();

            $request->merge($this->myTraits($request));

            // validação dos dados
            $NossaClasse = new NossaClasse();
            $request->validate(
                $NossaClasse->colunasValidate($this->data['campos'])
            );

            $user_id = auth('sanctum')->user()->id;
            $request->merge(['user_id' => $user_id]);

            $q = $this->Model->where($request->all())->first();

            if ($q) {
                $q = $q->forceDelete();
            }else{
                $q = $this->Model->updateOrCreate($request->all());
            }

            return $this->sendResponse($q, $this->message, $this->status);
        } catch (\Exception $e) {
            $this->status = $e;
            $this->message = $e;
        }

        return $this->sendError($this->message, $this->status);
    }
}
