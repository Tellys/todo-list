<?php

namespace App\Http\Controllers\Api;

use App\Models\UserLevelRoles;

class Permissoes extends BaseController
{
    //
    //public static function autorize($userLevel){
    public function autorize()
    {
        $user = auth('sanctum')->user();
        if (empty($user->id)) {
            return throw new \Exception('Unauthenticated.',401) ;//false;
        }
        //dd(request()->route()->getActionName());
        //dd(Route::getRoutes());
        //dd((string) get_class($this));

        $userLevelRoles = UserLevelRoles::where('users_level_id', $user->users_level_id)->where('name', request()->route()->getActionName())->first();

        if ($userLevelRoles) {

            /**
             * Permissões
             *
             * 'options' => [
             * 'none'=>'Negado',
             * 'restrict'=>'Acesso em suas Propriedades',
             * 'full'=>'Acesso Geral',
             * ],
             */
            switch ($userLevelRoles->action) {
                case 'none':
                    return throw new \Exception('Você não tem permissão para acessar esse serviço',403) ;//false;
                    break;

                case 'restrict':
                    //session()->flash('restrict', true);
                    return $userLevelRoles;
                    break;

                case 'full':

                    $this->createNewUser(1);
                    $this->editUser(1);

                    return $userLevelRoles;

                    break;

                // default:
                //     break;
            }
        }

        //dd($userLevel->userLevelRoles);
        return throw new \Exception('Você não tem permissão para acessar esse serviço',403) ;//false;
    }

    private function createNewUser($limitLevel = 1)
    {
        if (
            is_int(strpos(strtolower(request()->route()->action['controller']), 'user'))
        ) {
            // impede criação de usuário com level menor que $limitLevel
            if (!empty(request()->users_level_id) and request()->users_level_id < $limitLevel) {
                return false;
            }

            //dd('dentro',request()->users_level_id <= $limitLevel, request()->users_level_id, request(), request()->route());
        }

        return;
    }

    private function editUser($limitLevel = 1)
    {
        if (
            is_int(strpos(strtolower(request()->route()->action['controller']), 'user'))
            and !empty(request()->route()->parameters['user'])
            and !empty(request()->route()->parameters['user'] == $limitLevel)
        ) {
            return 'restrict';
        }

        return;
    }
}
